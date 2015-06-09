<?php

/**
 * Derived Exception from CustomException class.
 * Useful for each class of the api
 *
 * @author llaine
 * @namespace src/exception
 */
require_once "IException.php";


/**
 * Class LoaderException
 */
class LoaderException extends \exception\CustomException { };

/**
 * Class ImporterException
 */
class ImporterException extends \exception\CustomException { };

/**
 * Class RiverException
 */
class RiverException extends \exception\CustomException { };