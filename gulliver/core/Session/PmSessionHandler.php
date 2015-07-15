<?php

/**
 * Class PmSessionHandler
 *
 * This class is a Database based PHP Session Handler
 *
 * Singleton Class
 *
 * @version   1.0
 * @author    Erik Amaru Ortiz <aortiz.erik@gmail.com>
 */
class PmSessionHandler //implements SessionHandlerInterface
{
    private $savePath;
    private $db;
    private $rstmt;
    private $wstmt;
    private $dstmt;
    private $gcstmt;

    /**
     * $dsn Data source name for session DB Storage
     * @var string
     */
    private $dsn = '';
    private $dbUser = '';
    private $dbPassword = '';
    private $dbtable = 'SESSION_STORAGE';

    /**
     * $httponly Session accessibility boolean key
     * By default the session cookie is not accessable via javascript. 
     * @var boolean
     */
    private $httponly = true;

    private $debug = false;

    /**
     * The Construct 
     * Initialize object and set database credentials passed as arguments
     * @param string $user     Db User name
     * @param string $password Db User password
     * @param string $dsn      Data source string with PDO fotmat
     */
    public function __construct($user, $password, $dsn)
    {
        $this->dbUser = $user;
        $this->dbPassword = $password;
        $this->dsn = $dsn;

        //session_set_save_handler($this, true);
        session_set_save_handler(
            array($this, 'open'), 
            array($this, 'close'), 
            array($this, 'read'), 
            array($this, 'write'), 
            array($this, 'destroy'), 
            array($this, 'gc')
        );

        // moved from open() for php 5.3.x compatibility
        $this->db = new PDO(
            $this->dsn, // 
            $this->dbUser, 
            $this->dbPassword,
            array(
                /*
                 * The web applications will benefit from making persistent connections to database servers. 
                 * Persistent connections are not closed at the end of the script, but are cached and re-used 
                 * when another script requests a connection using the same credentials. 
                 * The persistent connection cache allows you to avoid the overhead of establishing a new connection 
                 * every time a script needs to talk to a database, resulting in a faster web application.    
                 */
                PDO::ATTR_PERSISTENT => false,  // <- using "true", is causing open many mysql connections, disabled by now
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            )
        );
 
        // This line prevents unexpected effects when using objects as save handlers.
        register_shutdown_function('session_write_close');
    }

    function start_session($sessionName, $secure)
    {

        // Hash algorithm to use for the sessionid. (use hash_algos() to get a list of available hashes.)
        $sessionHash = 'sha512';

        // Check if hash is available
        if (in_array($sessionHash, hash_algos())) {
          // Set the has function.
          ini_set('session.hash_function', $sessionHash);
        }
        // How many bits per character of the hash.
        // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
        ini_set('session.hash_bits_per_character', 5);

        // Force the session to only use cookies, not URL variables.
        ini_set('session.use_only_cookies', 1);

        // Get session cookie parameters 
        $cookieParams = session_get_cookie_params(); 
        // Set the parameters
        session_set_cookie_params(
            $cookieParams["lifetime"], 
            $cookieParams["path"], 
            $cookieParams["domain"], 
            $secure, 
            $httponly
        ); 
        // Change the session name 
        session_name($sessionName);
        // Now we cat start the session
        session_start();
        // This line regenerates the session and delete the old one. 
        // It also generates a new encryption key in the database. 
        session_regenerate_id(true);
    }

    /**
     * Open method, it is called when a session starts
     * @param  string $savePath    save path, it is passed from PHP Core
     * @param  string $sessionName session name, it is passed from PHP Core
     * @return bool                it always returns true
     */
    public function open($savePath, $sessionName)
    {
        // routines moved to __construct() for php 5.3.x compatibility


        $this->log("open($savePath, $sessionName) was called");

        return true;
    }

    /**
     * Close method, it is called when the script finish its execution
     * @return bool true always returns true
     */
    public function close()
    {
        // Upon successful connection to the database, an instance of the PDO class is returned 
        // to your script. The connection remains active for the lifetime of that PDO object. 
        // To close the connection, you need to destroy the object by ensuring that all 
        // remaining references to it are deleted--you do this by assigning NULL to the variable 
        // that holds the object. If you don't do this explicitly, PHP will automatically 
        // close the connection when your script ends.
        
        // this was commented to take advantage of PDO persistence connections
        $this->db = null;

        $this->log("close() was called");

        return true;
    }

    /**
     * Write method, it writes data when a session ariable was created or modified from scripts
     * @param  string $id  the session id
     * @param  mixed $data the DATA stored on session record
     * @return bool        always returns true
     */
    public function write($id, $data)
    {
        if (empty($this->db)) {
            return null;
        }

        $time = time();
        
        if(! isset($this->wstmt)) {
            $sql = "REPLACE INTO {$this->dbtable} (ID, SET_TIME, DATA, SESSION_KEY) VALUES (?, ?, ?, ?)";
            $this->wstmt = $this->db->prepare($sql);
        }

        $key = 'K' . rand();
        $data = base64_encode(serialize($data));

        //$this->wstmt->bind_param('siss', $id, $time, $data, $key);
        $this->wstmt->execute(array($id, $time, $data, $key));

        $this->log("write($id, array()) was called");

        return true;
    }

    /**
     * Read method, it is called when a session variable is requested from scripts
     * 
     * @param  string $id the session id
     * @return mixed returns the DATA stored on session record
     */
    public function read($id)
    {
        if(! isset($this->rstmt)) {
            $this->rstmt = $this->db->prepare("SELECT DATA FROM {$this->dbtable} WHERE ID = ? LIMIT 1");
        }

        $this->rstmt->execute(array($id));
        $data = $this->rstmt->fetch();
        $data = unserialize(base64_decode($data['DATA']));

        $this->log("read($id) was called");

        return $data;
    }

    /**
     * Destroy method, it is called when a session has expired
     * @param  string $id the session id
     * @return bool always returns true
     */
    public function destroy($id)
    {
        if(! isset($this->dstmt)) {
            $this->dstmt = $this->db->prepare("DELETE FROM {$this->dbtable} WHERE ID = ?");
        }

        $this->log("destroy($id) was called");

        $this->dstmt->execute(array($id));
        
        return true;
    }

    /**
     * Garbase Collection method 
     * 
     * @param int $maxlifetime max time that specifies if the session is active or not
     * @return bool always returns true
     */
    public function gc($maxlifetime)
    {
        $time = time() - $maxlifetime;

        if(! isset($this->gcstmt)) {
            $this->gcstmt = $this->db->prepare("DELETE FROM {$this->dbtable} WHERE SET_TIME < ?");
        }

        $this->gcstmt->execute(array($time));

        $this->log("gc($maxlifetime) was called");
        
        return true;
    }

    public function log($data)
    {
        if (! $this->debug) {
            return false;
        }

        error_log('PM Session Handler :: ' . print_r($data, true));
    }
}
