<?php

namespace ProcessMaker\Services\OAuth2;

/**
 * Simple PmPDO storage for all storage types
 * based on \OAuth2\Storage\Pdo
 *
 * @author Erik Amaru Ortiz <aortiz.erik at gmail dot com>
 */
class PmPdo implements \OAuth2\Storage\AuthorizationCodeInterface,
    \OAuth2\Storage\AccessTokenInterface,
    \OAuth2\Storage\ClientCredentialsInterface,
    \OAuth2\Storage\UserCredentialsInterface,
    \OAuth2\Storage\RefreshTokenInterface,
    \OAuth2\Storage\JwtBearerInterface
{

    protected $db;
    protected $config;

    public function __construct($connection, $config = array())
    {
        if (!$connection instanceof \PDO) {
            if (!is_array($connection)) {
                throw new \InvalidArgumentException('First argument to OAuth2\Storage\Pdo must be an instance of PDO or a configuration array');
            }
            if (!isset($connection['dsn'])) {
                throw new \InvalidArgumentException('configuration array must contain "dsn"');
            }
            // merge optional parameters
            $connection = array_merge(array(
                'username' => null,
                'password' => null,
            ), $connection);
            $connection = new \PDO($connection['dsn'], $connection['username'], $connection['password']);
        }
        $this->db = $connection;

        // debugging
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->config = array_merge(array(
            'client_table' => 'OAUTH_CLIENTS',
            'access_token_table' => 'OAUTH_ACCESS_TOKENS',
            'refresh_token_table' => 'OAUTH_REFRESH_TOKENS',
            'code_table' => 'OAUTH_AUTHORIZATION_CODES',
            'user_table' => 'USERS',
            'jwt_table' => 'OAUTH_JWT',
        ), $config);
    }

    /* OAuth2_Storage_ClientCredentialsInterface */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $stmt = $this->db->prepare(sprintf('SELECT * from %s WHERE CLIENT_ID = :client_id', $this->config['client_table']));
        $stmt->execute(compact('client_id'));
        $result = self::expandCase($stmt->fetch());

        // make this extensible
        return $result['client_secret'] == $client_secret;
    }

    public function getClientDetails($client_id)
    {
        $stmt = $this->db->prepare(sprintf('SELECT * from %s WHERE CLIENT_ID = :client_id', $this->config['client_table']));
        $stmt->execute(compact('client_id'));

        return self::expandCase($stmt->fetch());
    }

    public function checkRestrictedGrantType($client_id, $grant_type)
    {
        $details = $this->getClientDetails($client_id);
        if (isset($details['grant_types'])) {
            $grant_types = explode(' ', $details['grant_types']);

            return in_array($grant_type, (array) $grant_types);
        }

        // if grant_types are not defined, then none are restricted
        return true;
    }

    /* OAuth2_Storage_AccessTokenInterface */
    public function getAccessToken($access_token)
    {
        $stmt = $this->db->prepare(sprintf('SELECT * from %s WHERE ACCESS_TOKEN = :access_token', $this->config['access_token_table']));

        $token = $stmt->execute(compact('access_token'));
        if ($token = self::expandCase($stmt->fetch())) {
            // convert date string back to timestamp
            $token['expires'] = strtotime($token['expires']);
        }

        return $token;
    }

    public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);

        // if it exists, update it.
        if ($this->getAccessToken($access_token)) {
            $stmt = $this->db->prepare(sprintf('UPDATE %s SET CLIENT_ID=:client_id, EXPIRES=:expires, USER_ID=:user_id, SCOPE=:scope WHERE ACCESS_TOKEN=:access_token', $this->config['access_token_table']));
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO %s (ACCESS_TOKEN, CLIENT_ID, EXPIRES, USER_ID, SCOPE) VALUES (:access_token, :client_id, :expires, :user_id, :scope)', $this->config['access_token_table']));
        }
        return $stmt->execute(compact('access_token', 'client_id', 'user_id', 'expires', 'scope'));
    }

    /* OAuth2_Storage_AuthorizationCodeInterface */
    public function getAuthorizationCode($code)
    {
        $stmt = $this->db->prepare(sprintf('SELECT * FROM %s WHERE AUTHORIZATION_CODE = :code', $this->config['code_table']));
        $stmt->execute(compact('code'));

        if ($code = self::expandCase($stmt->fetch())) {
            // convert date string back to timestamp
            $code['expires'] = strtotime($code['expires']);
        }

        return $code;
    }

    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);

        // if it exists, update it.
        if ($this->getAuthorizationCode($code)) {
            $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET CLIENT_ID=:client_id, USER_ID=:user_id, REDIRECT_URI=:redirect_uri, EXPIRES=:expires, SCOPE=:scope where AUTHORIZATION_CODE=:code', $this->config['code_table']));
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO %s (AUTHORIZATION_CODE, CLIENT_ID, USER_ID, REDIRECT_URI, EXPIRES, SCOPE) VALUES (:code, :client_id, :user_id, :redirect_uri, :expires, :scope)', $this->config['code_table']));
        }
        return $stmt->execute(compact('code', 'client_id', 'user_id', 'redirect_uri', 'expires', 'scope'));
    }

    public function expireAuthorizationCode($code)
    {
        $stmt = $this->db->prepare(sprintf('DELETE FROM %s WHERE AUTHORIZATION_CODE = :code', $this->config['code_table']));

        return $stmt->execute(compact('code'));
    }

    /* OAuth2_Storage_UserCredentialsInterface */
    public function checkUserCredentials($username, $password)
    {
        if ($user = $this->getUser($username)) {
            return $this->checkPassword($user, $password);
        }
        return false;
    }

    public function getUserDetails($username)
    {
        return $this->getUser($username);
    }

    /* OAuth2_Storage_RefreshTokenInterface */
    public function getRefreshToken($refresh_token)
    {
        $stmt = $this->db->prepare(sprintf('SELECT * FROM %s WHERE REFRESH_TOKEN = :refresh_token', $this->config['refresh_token_table']));

        $token = $stmt->execute(compact('refresh_token'));
        if ($token = self::expandCase($stmt->fetch())) {
            // convert expires to epoch time
            $token['expires'] = strtotime($token['expires']);
        }

        return $token;
    }

    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
    {
        // convert expires to datestring
        $expires = date('Y-m-d H:i:s', $expires);

        $stmt = $this->db->prepare(sprintf('INSERT INTO %s (REFRESH_TOKEN, CLIENT_ID, USER_ID, EXPIRES, SCOPE) VALUES (:refresh_token, :client_id, :user_id, :expires, :scope)', $this->config['refresh_token_table']));

        return $stmt->execute(compact('refresh_token', 'client_id', 'user_id', 'expires', 'scope'));
    }

    public function unsetRefreshToken($refresh_token)
    {
        $stmt = $this->db->prepare(sprintf('DELETE FROM %s WHERE REFRESH_TOKEN = :refresh_token', $this->config['refresh_token_table']));

        return $stmt->execute(compact('refresh_token'));
    }

    // plaintext passwords are bad!  Override this for your application
    protected function checkPassword($user, $password)
    {
        return $user['USR_PASSWORD'] == md5($password);
    }

    public function getUser($username)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT * FROM %s WHERE USR_USERNAME=:username', $this->config['user_table']));
        $stmt->execute(array('username' => $username));

        if (!$userInfo = $stmt->fetch()) {
            return false;
        }

        $userInfo = self::expandCase($userInfo);

        // the default behavior is to use "username" as the user_id
        return array_merge(array(
            'user_id' => $userInfo['USR_UID'] //$username
        ), $userInfo);
    }

    public function setUser($username, $password, $firstName = null, $lastName = null)
    {
        // do not store in plaintext
        $password = sha1($password);

        // if it exists, update it.
        if ($this->getUser($username)) {
            $stmt = $this->db->prepare($sql = sprintf('UPDATE %s SET PASSWORD=:password, FIRST_NAME=:firstName, LAST_NAME=:lastName WHERE USERNAME=:username', $this->config['user_table']));
        } else {
            $stmt = $this->db->prepare(sprintf('INSERT INTO %s (USERNAME, PASSWORD, FIRST_NAME, LAST_NAME) VALUES (:username, :password, :firstName, :lastName)', $this->config['user_table']));
        }
        return $stmt->execute(compact('username', 'password', 'firstName', 'lastName'));
    }

    /* OAuth2_Storage_JWTBearerInterface */
    public function getClientKey($client_id, $subject)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT PUBLIC_KEY from %s WHERE CLIENT_ID=:client_id AND SUBJECT=:subject', $this->config['jwt_table']));

        $stmt->execute(array('client_id' => $client_id, 'subject' => $subject));
        return self::expandCase($stmt->fetch());
    }

    protected static function expandCase($a, $case = CASE_LOWER)
    {
        if (! is_array($a)) {
            return $a;
        }

        return array_merge($a, array_change_key_case($a, $case));
    }
}

