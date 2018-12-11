<?php
namespace ProcessMaker\Util\IO;

/**
 * Class HttpStream
 * Send a http stream from a file or string
 *
 * @package ProcessMaker\Util\IO
 * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 */
class HttpStream
{
    /**
     * @var string content stream
     */

    protected $content = "";
    protected $sourceName = "";
    protected $headers = array();
    protected $charset;
    protected $statusCode;
    protected $extension;
    protected $filename;
    protected $version;
    protected $statusText;

    protected static $mimeType = array(
        'ai' => 'application/postscript', 'bcpio' => 'application/x-bcpio', 'bin' => 'application/octet-stream',
        'ccad' => 'application/clariscad', 'cdf' => 'application/x-netcdf', 'class' => 'application/octet-stream',
        'cpio' => 'application/x-cpio', 'cpt' => 'application/mac-compactpro', 'csh' => 'application/x-csh',
        'csv' => 'application/csv', 'dcr' => 'application/x-director', 'dir' => 'application/x-director',
        'dms' => 'application/octet-stream', 'doc' => 'application/msword', 'drw' => 'application/drafting',
        'dvi' => 'application/x-dvi', 'dwg' => 'application/acad', 'dxf' => 'application/dxf',
        'dxr' => 'application/x-director', 'eot' => 'application/vnd.ms-fontobject', 'eps' => 'application/postscript',
        'exe' => 'application/octet-stream', 'ez' => 'application/andrew-inset',
        'flv' => 'video/x-flv', 'gtar' => 'application/x-gtar', 'gz' => 'application/x-gzip',
        'bz2' => 'application/x-bzip', '7z' => 'application/x-7z-compressed', 'hdf' => 'application/x-hdf',
        'hqx' => 'application/mac-binhex40', 'ico' => 'image/vnd.microsoft.icon', 'ips' => 'application/x-ipscript',
        'ipx' => 'application/x-ipix', 'js' => 'application/x-javascript', 'latex' => 'application/x-latex',
        'lha' => 'application/octet-stream', 'lsp' => 'application/x-lisp', 'lzh' => 'application/octet-stream',
        'man' => 'application/x-troff-man', 'me' => 'application/x-troff-me', 'mif' => 'application/vnd.mif',
        'ms' => 'application/x-troff-ms', 'nc' => 'application/x-netcdf', 'oda' => 'application/oda',
        'otf' => 'font/otf', 'pdf' => 'application/pdf',
        'pgn' => 'application/x-chess-pgn', 'pot' => 'application/mspowerpoint', 'pps' => 'application/mspowerpoint',
        'ppt' => 'application/mspowerpoint', 'ppz' => 'application/mspowerpoint', 'pre' => 'application/x-freelance',
        'prt' => 'application/pro_eng', 'ps' => 'application/postscript', 'roff' => 'application/x-troff',
        'scm' => 'application/x-lotusscreencam', 'set' => 'application/set', 'sh' => 'application/x-sh',
        'shar' => 'application/x-shar', 'sit' => 'application/x-stuffit', 'skd' => 'application/x-koan',
        'skm' => 'application/x-koan', 'skp' => 'application/x-koan', 'skt' => 'application/x-koan',
        'smi' => 'application/smil', 'smil' => 'application/smil', 'sol' => 'application/solids',
        'spl' => 'application/x-futuresplash', 'src' => 'application/x-wais-source', 'step' => 'application/STEP',
        'stl' => 'application/SLA', 'stp' => 'application/STEP', 'sv4cpio' => 'application/x-sv4cpio',
        'sv4crc' => 'application/x-sv4crc', 'svg' => 'image/svg+xml', 'svgz' => 'image/svg+xml',
        'swf' => 'application/x-shockwave-flash', 't' => 'application/x-troff',
        'tar' => 'application/x-tar', 'tcl' => 'application/x-tcl', 'tex' => 'application/x-tex',
        'texi' => 'application/x-texinfo', 'texinfo' => 'application/x-texinfo', 'tr' => 'application/x-troff',
        'tsp' => 'application/dsptype', 'ttf' => 'font/ttf',
        'unv' => 'application/i-deas', 'ustar' => 'application/x-ustar',
        'vcd' => 'application/x-cdlink', 'vda' => 'application/vda', 'xlc' => 'application/vnd.ms-excel',
        'xll' => 'application/vnd.ms-excel', 'xlm' => 'application/vnd.ms-excel', 'xls' => 'application/vnd.ms-excel',
        'xlw' => 'application/vnd.ms-excel', 'zip' => 'application/zip', 'aif' => 'audio/x-aiff', 'aifc' => 'audio/x-aiff',
        'aiff' => 'audio/x-aiff', 'au' => 'audio/basic', 'kar' => 'audio/midi', 'mid' => 'audio/midi',
        'midi' => 'audio/midi', 'mp2' => 'audio/mpeg', 'mp3' => 'audio/mpeg', 'mpga' => 'audio/mpeg',
        'ra' => 'audio/x-realaudio', 'ram' => 'audio/x-pn-realaudio', 'rm' => 'audio/x-pn-realaudio',
        'rpm' => 'audio/x-pn-realaudio-plugin', 'snd' => 'audio/basic', 'tsi' => 'audio/TSP-audio', 'wav' => 'audio/x-wav',
        'asc' => 'text/plain', 'c' => 'text/plain', 'cc' => 'text/plain', 'css' => 'text/css', 'etx' => 'text/x-setext',
        'f' => 'text/plain', 'f90' => 'text/plain', 'h' => 'text/plain', 'hh' => 'text/plain', 'htm' => 'text/html',
        'html' => 'text/html', 'm' => 'text/plain', 'rtf' => 'text/rtf', 'rtx' => 'text/richtext', 'sgm' => 'text/sgml',
        'sgml' => 'text/sgml', 'tsv' => 'text/tab-separated-values', 'tpl' => 'text/template', 'txt' => 'text/plain',
        'xml' => 'text/xml', 'avi' => 'video/x-msvideo', 'fli' => 'video/x-fli', 'mov' => 'video/quicktime',
        'movie' => 'video/x-sgi-movie', 'mpe' => 'video/mpeg', 'mpeg' => 'video/mpeg', 'mpg' => 'video/mpeg',
        'qt' => 'video/quicktime', 'viv' => 'video/vnd.vivo', 'vivo' => 'video/vnd.vivo', 'gif' => 'image/gif',
        'ief' => 'image/ief', 'jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg',
        'pbm' => 'image/x-portable-bitmap', 'pgm' => 'image/x-portable-graymap', 'png' => 'image/png',
        'pnm' => 'image/x-portable-anymap', 'ppm' => 'image/x-portable-pixmap', 'ras' => 'image/cmu-raster',
        'rgb' => 'image/x-rgb', 'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'xbm' => 'image/x-xbitmap',
        'xpm' => 'image/x-xpixmap', 'xwd' => 'image/x-xwindowdump', 'ice' => 'x-conference/x-cooltalk',
        'iges' => 'model/iges', 'igs' => 'model/iges', 'mesh' => 'model/mesh', 'msh' => 'model/mesh',
        'silo' => 'model/mesh', 'vrml' => 'model/vrml', 'wrl' => 'model/vrml',
        'mime' => 'www/mime', 'pdb' => 'chemical/x-pdb', 'xyz' => 'chemical/x-pdb'
    );
    protected static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );

    public function __construct($content = "", $status = 200, $headers = array())
    {
        if (! empty($content)) {
            $this->loadFromString($content);
        }

        if (! empty($headers)) {
            $this->headers = $headers;

            if (! isset($this->headers['Date'])) {
                $this->setDate(new \DateTime(null, new \DateTimeZone('UTC')));
            }
        }

        $this->setStatusCode($status);
        $this->setProtocolVersion("1.0");
    }

    /**
     * @param string $content content string to stream
     */
    public function loadFromString($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $filename file to stream
     * @throws \Exception
     */
    public function loadFromFile($filename)
    {
        if (!file_exists($filename)) {
            throw new \Exception("Unable to find file: $filename");
        }

        $this->filename = $filename;
        $this->content = file_get_contents($this->filename);
        $fileInfo = pathinfo($filename, PATHINFO_EXTENSION);
        $this->setExtension($fileInfo);
        $currentLocale = setlocale(LC_CTYPE, 0);
        setlocale(LC_CTYPE, 'en_US.UTF-8');
        $filename = basename($filename);
        setlocale(LC_CTYPE, $currentLocale);
        $this->setSourceName($filename);
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Returns the extension of the file.
     * \SplFileInfo::getExtension() is not available before PHP 5.3.6
     *
     * @return string The extension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Returns the mime type of the file. (improved by erik)
     *
     * The mime type is guessed using the functions finfo(), mime_content_type()
     * and the system binary "file" (in this order), depending on which of those
     * is available on the current operating system.
     *
     * @author Erik Amaru Ortiz <aortiz.erik@gmail.com>
     * @return string|null The guessed mime type (i.e. "application/pdf")
     */
    public function getMimeType()
    {
        if (array_key_exists($this->getExtension(), self::$mimeType)) {
            return self::$mimeType[$this->getExtension()];
        }

        if (! empty($this->filename)) {
            if (class_exists('finfo')) {
                $finfo = new \finfo;
                $mimeType = $finfo->file($this->filename, FILEINFO_MIME);

                if (preg_match('/([\w\-]+\/[\w\-]+); charset=(\w+)/', $mimeType, $match)) {
                    return $match[1];
                }
            }

            if (function_exists('mime_content_type')) {
                return mime_content_type($this->filename);
            }
        }

        return 'application/octet-stream';
    }

    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function setProtocolVersion($version)
    {
        $this->version = $version;
    }

    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    public function setDate(\DateTime $date)
    {
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->headers['Date'] = $date->format('D, d M Y H:i:s') . ' GMT';
    }

    /**
     * Retrieves the response charset.
     *
     * @return string Character set
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Sets the response charset.
     *
     * @param string $charset
     * @return string Character set
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = (int) $code;
        $this->statusText = false === $text ? '' : (null === $text ? self::$statusTexts[$this->statusCode] : $text);
    }

    public function prepare()
    {
        if (! empty($this->sourceName)) {
            $this->setHeader('Content-Disposition', 'attachment; filename="'. $this->sourceName . '"' );
        }

        // Fix Content-Type
        $charset = $this->charset ?: 'UTF-8';

        if (! isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = $this->getMimeType();
        }

        if (
            0 === strpos($this->headers['Content-Type'], 'text/') &&
            false === strpos($this->headers['Content-Type'], 'charset')
        ) {
            // add the charset
            $this->headers['Content-Type'] = $this->headers['Content-Type'].'; charset='.$charset;
        }

        // Fix Content-Length
        if (isset($this->headers['Transfer-Encoding'])) {
            unset($this->headers['Content-Length']);
        }


    }

    public function sendHeaders()
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return;
        }

        $this->prepare();

        // status
        header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));

        // headers
        foreach ($this->headers as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    header($name.': '.$v, false);
                }
            } else {
                header($name.': '.$value, false);
            }
        }
    }

    public function sendContent()
    {
        echo $this->content;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}