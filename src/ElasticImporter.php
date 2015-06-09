<?php

namespace ElasticRiver;


require_once "interface/ImporterInterface.php";
require_once "exception/Exceptions.php";
require_once "utils/ElasticUtils.php";


/**
 * Class ElasticaImporter
 *
 * The derived type from the ImporterInterface.
 * This one is used to import data to elasticsearch using Elastica.
 */
class ElasticaImporter implements ImporterInterface {

    /**
     * @var \Elastica\Client
     */
    private $clientElastic;

    /**
     * @var \Elastica\Index
     */
    private $currentIndex;

    /**
     * @var \Elastica\Type
     */
    private $currentType;


    /**
     * Connect to Elasticsearch using the \Elastica\Client
     * @param array $params
     * @param null $callback
     */
    public function __construct(array $params = array(), $callback = null)
    {
        $config = array(
            'host' => $this->_getHost(),
            'port' => $this->_getPort(),
        );

        $config = array_merge($config, $params);

        $this->clientElastic = new \Elastica\Client($config, $callback);
    }

    /**
     * Must create the destination database with specific options for the new database.
     *
     * Create an elasticsearch index
     * @param $name
     * @param array $opts
     * @throws \ImporterException
     * @return mixed
     */
    public function createDatabase($name, array $opts = null)
    {

        if(!isset($this->clientElastic)) {
            throw new \ImporterException("clientElastic non définie lors d'une tentative de créer un index. ", 404);
        }

        $index = $this->clientElastic->getIndex($name);

        $index->create(
            array("index" =>
                array(
                    "number_of_shards"   => $opts["number_of_shards"],
                    "number_of_replicas" => 0
                )
            ),
            $opts['deleteIfExists']
        );

        $this->currentIndex = $index;

    }

    /**
     * Must create the destination table, (in the destination database) with specific options
     *
     * Create an Elasticsearch type.
     * @param $name
     * @param array $opts
     * @throws ImporterException
     * @return mixed
     */
    public function createTable($name, array $opts = null)
    {
        $index = $this->currentIndex;

        if(!isset($index)) {
            throw new \ImporterException('Client $elastic non définie lors d\une tentative de créer un type ', 404);
        }

        $type = new \Elastica\Type($this->currentIndex, $name);

        if(isset($opts['mapping'])){
            $type->setMapping($opts['mapping']);
        }

        $this->currentType = $type;

    }

    /**
     * Create a simple record on the table, in the database.
     * Create an elasticsearch document.
     * @param $data
     * @return mixed
     */
    public function createRecord(array $data)
    {

        $document = $this->createDocument($data);

        $document->setType($this->currentType);

        $document->setIndex($this->currentIndex);

        $this->currentType->addDocument($document);

    }

    /**
     * Useful for creating large amount of records.
     * Must create records using the bulk system.
     * @param array $data
     * @throws \ImporterException
     * @return boolean
     */
    public function createRecordsWithBulk(array $data)
    {
        $errors = array_filter($data);

        if(empty($errors)) {
            throw new \ImporterException('Tableau de donnée vide lors d\'une tentative de Bulk sur l\'index '.$this->currentIndex.' et le type '.$this->currentType);
        }

        $bulk = new \Elastica\Bulk($this->clientElastic);

        $bulk->setType($this->currentType);

        $bulk->addDocuments($data);

        $response = $bulk->send();

        return $response->isOk();

    }


    /**
     * Must create a document.
     * This function is used in the createRecord and createRecordsWithBulk.
     *
     * @param array $data
     * @throws ImporterException
     * @return \\Elastica\Document
     */
    public function createDocument(array $data) {

        $errors = array_filter($data);

        if(empty($errors)) {
            throw new \ImporterException('Tableau de donnée vide lors d\'une tentative de création de document sur l\'index '.$this->currentIndex.' et le type '.$this->currentType);
        }

        $document = new \Elastica\Document();

        $document->setData($data);

        return $document;

    }

    /**
     * Must test the importer connection.
     * @return Boolean
     */
    public function testConnection()
    {
        return $this->elasticUtils->testConnection();
    }

    /**
     * @return string Host to es for elastica tests
     */
    public function _getHost()
    {
        return getenv('ES_HOST') ?: \Elastica\Connection::DEFAULT_HOST;
    }

    /**
     * @return int Port to es for \Elastica tests
     */
    public function _getPort()
    {
        return getenv('ES_PORT') ?: \Elastica\Connection::DEFAULT_PORT;
    }


    /**
     * @return \Elastica\Client
     */
    public function getClientElastic()
    {
        return $this->clientElastic;
    }

    /**
     * @return \Elastica\Index
     */
    public function getCurrentIndex()
    {
        return $this->currentIndex;
    }

    /**
     * @return \Elastica\Type
     */
    public function getCurrentType()
    {
        return $this->currentType;
    }

    /**
     * @param \Elastica\Client $clientElastic
     */
    public function setClientElastic($clientElastic)
    {
        $this->clientElastic = $clientElastic;
    }

    /**
     * @param \Elastica\Index $currentIndex
     */
    public function setCurrentIndex($currentIndex)
    {
        $this->currentIndex = $currentIndex;
    }

    /**
     * @param \Elastica\Type $currentType
     */
    public function setCurrentType($currentType)
    {
        $this->currentType = $currentType;
    }

}