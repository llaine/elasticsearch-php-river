# elasticsearch-php-river
Elasticsearch river based on elastica and bulk


## How to


```bash
$ php composer.phar install
```


```php
<?php


require_once "src/autoloader.php";

try {

    /* Create the elasticsearch importer */
    $importer = new ElasticaImporter(
        array("number_of_shard" => 5)
    );

    /* create the mysql loader */
    $loader = new MySqlLoader(
        "database",
        array(
            "host" => "localhost",
            "login" => "root",
            "password" => "root"
        )
    );

    /* River query */
    $query = "SELECT * FROM Articles;";

    /* Create the river using the importer and loader. */
    $river = new River($importer, $loader, $query);

    /* send the river, will create a new index and type */
    $river->send("index", "type");


} catch(Exception $e) {
    echo ($e->getMessage());
}





```