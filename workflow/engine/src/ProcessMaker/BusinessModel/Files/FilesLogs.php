<?php

namespace ProcessMaker\BusinessModel\Files;

use Chumper\Zipper\Zipper;
use Configurations;
use Exception;
use G;
use ProcessMaker\Core\System;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class FilesLogs extends Files
{
    /**
     * Date format in list
     * @var string
     */
    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * Path of the directory where the files are stored.
     *
     * @var string
     */
    private $pathData = '';

    /**
     * FilesLogs constructor .
     */
    public function __construct()
    {
        $system = System::getSystemConfiguration();
        $configuration = new Configurations();
        $generalConfig = $configuration->getConfiguration('ENVIRONMENT_SETTINGS', '');
        if (isset($generalConfig['casesListDateFormat']) && !empty($generalConfig['casesListDateFormat'])) {
            $this->setDateFormat($generalConfig['casesListDateFormat']);
        }
        $path = PATH_DATA . 'sites' . PATH_SEP . config('system.workspace') . PATH_SEP . 'log' . PATH_SEP;
        if (isset($system['logs_location']) && !empty($system['logs_location']) && is_dir($system['logs_location'])) {
            $path = $system['logs_location'];
        }
        $this->setPathDataSaveFile(PATH_DATA_PUBLIC);
        parent::__construct($path);
    }

    /**
     * Get Date Format
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Set Date Format
     *
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * Get Path data
     *
     * @return string
     */
    public function getPathDataSaveFile()
    {
        return $this->pathData;
    }

    /**
     * Set path data
     *
     * @param string $pathData
     */
    public function setPathDataSaveFile($pathData)
    {
        G::mk_dir($pathData);
        $this->pathData = $pathData;
    }

    /**
     * This function get the list of the log files
     *
     * @param string $filter
     * @param string $sort
     * @param int $start
     * @param int $limit
     * @param string $dir related to order the column
     *
     * @return array
     */
    public function getAllFiles($filter = '', $sort = 'fileCreated', $start = 0, $limit = 20, $dir = 'DESC')
    {
        if (!file_exists($this->getPathFiles())) {
            return [
                'totalRows' => 0,
                'data' => []
            ];
        }

        $finder = new Finder();
        $finder->files()
                ->in($this->getPathFiles())
                ->name('processmaker*.log')
                ->name('audit*.log');

        if (!empty($filter)) {
            $finder->filter(function (SplFileInfo $file) use ($filter) {
                if (stristr($file->getFilename(), $filter) === false &&
                        stristr($file->getSize(), $filter) === false &&
                        stristr($file->getMTime(), $filter) === false
                ) {
                    return false;
                }
            });
        }

        //get files
        $iterator = $finder->getIterator();
        $files = iterator_to_array($iterator);

        //sort files
        switch ($sort) {
            case 'fileSize':
                uasort($files, function (SplFileInfo $a, SplFileInfo $b) use ($dir) {
                    $size1 = $a->getSize();
                    $size2 = $b->getSize();
                    if ($dir === 'ASC') {
                        return $size1 > $size2;
                    } else {
                        return $size1 < $size2;
                    }
                });
                break;
            case 'fileCreated':
                uasort($files, function ($a, $b) use ($dir) {
                    $time1 = $a->getMTime();
                    $time2 = $b->getMTime();
                    if ($dir === 'ASC') {
                        return $time1 > $time2;
                    } else {
                        return $time1 < $time2;
                    }
                });
                break;
            case 'fileName':
            default:
                uasort($files, function ($a, $b) use ($dir) {
                    $name1 = $a->getFilename();
                    $name2 = $b->getFilename();
                    if ($dir === 'ASC') {
                        return strcmp($name1, $name2);
                    } else {
                        return strcmp($name2, $name1);
                    }
                });
                break;
        }

        //count files
        $total = count($files);

        //limit files
        $files = array_slice(
                $files, !empty($start) ? $start : 0, !empty($limit) ? $limit : 20
        );

        //create out element
        $result = [];
        foreach ($files as $file) {
            $result[] = [
                'fileName' => $file->getFilename(),
                'fileSize' => $this->size($file->getSize()),
                'fileCreated' => date($this->getDateFormat(), $file->getMTime())
            ];
        }
        return [
            'totalRows' => $total,
            'data' => $result
        ];
    }

    /**
     * Change the size of a file in bytes to its literal equivalent
     *
     * @param int $size file size in bytes
     * @param string $format
     * @return string
     */
    private function size($size, $format = null)
    {
        $sizes = ['Bytes', 'Kbytes', 'Mbytes', 'Gbytes', 'Tbytes', 'Pbytes', 'Ebytes', 'Zbytes', 'Ybytes'];
        if ($format === null) {
            $format = ' % 01.2f % s';
        }
        $lastSizesLabel = end($sizes);
        foreach ($sizes as $sizeLabel) {
            if ($size < 1024) {
                break;
            }
            if ($sizeLabel !== $lastSizesLabel) {
                $size /= 1024;
            }
        }
        if ($sizeLabel === $sizes[0]) {
            // Format bytes
            $format = '%01d %s';
        }
        return sprintf($format, $size, $sizeLabel);
    }

    /**
     * Create file zip
     *
     * @param array $files file name
     *
     * @return string path file
     * @throws Exception
     */
    private function createZip($files)
    {
        try {
            $zipper = new Zipper();
            $name = str_replace('.log', '.zip', $files[0]);
            if (count($files) > 1) {
                $name = 'processmaker_logs.zip';
            }

            $zipper->zip($this->getPathDataSaveFile() . $name);

            $pathFileLogs = $this->getPathFiles();
            $pathSep = '/';
            if (strpos($pathFileLogs, '\\') !== false) {
                $pathSep = '\\';
            }
            if (substr($pathFileLogs, -1, strlen($pathSep)) !== $pathSep) {
                $pathFileLogs .= $pathSep;
            }

            foreach ($files as $key => $file) {
                $info = pathinfo($file);
                if (file_exists($pathFileLogs . $info['basename'])) {
                    $zipper->add($pathFileLogs . $info['basename']);
                }
            }
            $zipper->close();

            return $this->getPathDataSaveFile() . $name;
        } catch (Exception $error) {
            throw $error;
        }
    }

    /**
     *  Download log files compressed in a Zip format
     *
     * @param array $files files names
     *
     * @throws Exception
     */
    public function download($files)
    {
        try {
            $fileZip = $this->createZip($files);

            if (file_exists($fileZip)) {
                G::streamFile($fileZip, true);
            } else {
                throw new Exception('File not exist.');
            }
            G::rm_dir($fileZip);
        } catch (Exception $error) {
            throw $error;
        }
    }
}
