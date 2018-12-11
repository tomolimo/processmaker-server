<?php

namespace ProcessMaker\BusinessModel\Files;

abstract class Files
{
    /**
     * @var string Path of the directory where the files are stored.
     */
    protected $pathFiles;

    /**
     * Files constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->pathFiles = $path;
    }

    /**
     * Get path files
     *
     * @return string
     */
    public function getPathFiles()
    {
        return $this->pathFiles;
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
    abstract public function getAllFiles(
        $filter = '',
        $sort = '',
        $start = 0,
        $limit = 20,
        $dir = 'ASC'
    );

    /**
     * Download file
     *
     * @param array files
     */
    abstract public function download($files);

}