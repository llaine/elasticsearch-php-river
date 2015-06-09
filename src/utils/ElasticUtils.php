<?php

namespace Utils;


use Elastica\Client;
use Elastica\Index;
use Elastica\Status;

/**
 * Class ElasticUtils
 * @package Utils
 */
class ElasticUtils {

    /**
     * @var Status
     */
    private $status;

    /**
     * @param Client $client
     */
    function __construct(Client $client) {

        $this->status = new Status($client);

    }

    /**
     * @param $indexName
     * @return bool
     */
    public function indexExists($indexName) {
        return $this->status->indexExists($indexName);

    }

    /**
     * @return array
     */
    public function getStatus() {
        return $this->status->getServerStatus();
    }

    /**
     * Test si la connection à l'importer est bien opérationnelle.
     * @return mixed
     */
    public function testConnection()
    {
        $connection = new \Elastica\Connection();

        return $connection->isEnabled();
    }


    /**
     * Rafraichit la table (l'index) sélectionné.
     * Permet de maintenir les segments de l'index up-to-date.
     * @param $name
     * @return mixed
     */
    public function refresh(Index $index)
    {
        $index->refresh();
    }


}