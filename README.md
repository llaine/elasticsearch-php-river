# elasticsearch-php-river
Elasticsearch river based on elastica and bulk


## How to


```bash
$ php composer.phar install
```


```php
<?php

/* import composer dependencies */
require_once "vendor/autoloader.php";

/* ElasticRiver dependecies */
require_once "src/autoloader.php";

try {

    $importer = new \ElasticRiver\ElasticaImporter();

    $loader = new \ElasticRiver\MySqlLoader(
        "mysql_database",
        array(
            "host" => "localhost",
            "login" => "root",
            "password" => "root"
        )
    );

    $river = new \ElasticRiver\River($importer, $loader);

    $river->setQuery("SELECT * FROM Articles");

    /* send the river, will create a new index and type */
    $river->send("index", "type");


} catch(Exception $e) {
    echo ($e->getMessage());
}
```


## Requirements

- Elasticsearch
- >= php 5.3
- memory limit : According to the number of row you might import using bulk, you should increase the memomry limit of your php config file.
