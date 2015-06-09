<?php


/**
 * Interface ImporterInterface
 * The importer "import" data from a source to a destination.
 *
 * An Importer could be, Elasticsearch, MongoDb or Mysql, etc.
 *
 * @author llaine
 */
interface ImporterInterface {

    /**
     * Must create the destination database with specific options for the new database.
     * @param $name
     * @param null $opts
     * @return mixed
     */
    public function createDatabase($name, array $opts = null);

    /**
     * Must create the destination table, (in the destination database) with specific options
     * @param $name
     * @param null $opts
     * @return mixed
     */
    public function createTable($name, array $opts = null);

    /**
     * Create a simple record on the table, in the database.
     * @param $data
     * @return mixed
     */
    public function createRecord(array $data);

    /**
     * Useful for creating large amount of records.
     * Must create records using the bulk system.
     * @param array $data
     * @return mixed
     */
    public function createRecordsWithBulk(array $data);

    /**
     * Helper function which provide a way to refresh the database (index, or collections, etc).
     * @param $name
     * @return mixed
     */
    public function refreshTable();

    /**
     * Must test the importer connection.
     * @return boolean
     */
    public function testConnection();

    /**
     * Must create a document.
     * This function is used in the createRecord and createRecordsWithBulk.
     * @param array $data
     * @return mixed
     */
    public function createDocument(array $data);

}