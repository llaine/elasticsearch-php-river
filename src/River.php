<?php


require_once "interface/ImporterInterface.php";
require_once "interface/LoaderInterface.php";


/**
 * Class River
 *
 * The class used to load and import the data.
 * @author llaine
 *
 */
class River {

    /**
     * @var LoaderInterface
     */
    private $loader;
    /**
     * @var ImporterInterface
     */
    private $importer;
    /**
     * @var string
     */
    private $query;

    /**
     * @param ImporterInterface $importer
     * @param LoaderInterface $loader
     * @param $query
     */
    function __construct(ImporterInterface $importer, LoaderInterface $loader, $query) {
        $this->loader = $loader;

        $this->importer = $importer;

        $this->query = $query;

    }

    /**
     * Send the river using the $importer and the $loader.
     * Use the $importer Bulk system to import large amount
     * of data.
     *
     * @param $indexName
     * @param $typeName
     * @throws Exception
     */
    public function send($indexName, $typeName) {

        $this->loader->connect();

        if(!$this->importer->testConnection()) {
            throw new RiverException("Tentative de connection à l'importer échoué lors de l'envoie d'un bulk", 500);
        }

        $this->importer->createDatabase($indexName);

        $this->importer->createTable($typeName);

        $queryResult = $this->loader->load($this->query);

        $documents = [];

        foreach($queryResult as $document) {
            array_push(
                $documents,
                $this->importer->createDocument($document)
            );
        }

        $this->importer->createRecordsWithBulk($documents);

        $this->importer->refreshTable();

    }
}