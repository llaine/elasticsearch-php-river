<?php


require_once "interface/LoaderInterface.php";
require_once "exception/Exceptions.php";


/**
 * Class MySqlLoader
 *
 * Class used to load data from a specific source.
 * Derived type used to load data from MySQL using PDO.
 *
 */
class MySqlLoader implements LoaderInterface {

    /**
     * @var \PDO
     */
    private $instance;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @var string
     */
    private $database;


    /**
     * Loader constructor.
     * Should select a database with credentials.
     * @param $database
     * @param array $credentials
     */
    public function __construct($database, array $credentials) {
        $this->credentials = $credentials;
        $this->database = $database;
    }

    /**
     * Connect the Loader to the database and with the credentials used in constructor.
     * @return mixed
     */
    public function connect()
    {
        $this->instance = new PDO('mysql:host='.$this->credentials['host'].';dbname='.$this->database.';charset=utf8', $this->credentials['login'], $this->credentials['password']);
    }

    /**
     * Load all the data from a specific query.
     * @param $query
     * @return mixed
     */
    public function load($query)
    {
        $stmt = $this->instance->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the instance of the database for the current connection
     * @return \Pdo
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Get credentials for the current connnection
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Get the database name for the current connection.
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->database;
    }


}