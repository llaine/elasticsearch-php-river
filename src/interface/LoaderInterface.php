<?php

namespace ElasticRiver;


/**
 * Interface LoaderInterface
 *
 * A Loader in the other part of the river.
 * Data should be loaded from a source before they can be importer into a destination.
 *
 * @author llaine
 */
interface LoaderInterface {

    /**
     * Loader constructor.
     * Should select a database with credentials.
     * @param $database
     * @param array $credentials
     */
    public function __construct($database, array $credentials);

    /**
     * Should connect the Loader to the database and with the credentials used in constructor.
     * @return mixed
     */
    public function connect();

    /**
     * Should load all the data from a specific query.
     * @param $query
     * @return mixed
     */
    public function load($query);

    /**
     * Get credentials for the current connnection
     * @return array
     */
    public function getCredentials();


    /**
     * Get the instance of the database for the current connection
     * @return mixed
     */
    public function getInstance();

    /**
     * Get the database name for the current connection.
     * @return string
     */
    public function getDatabaseName();

}