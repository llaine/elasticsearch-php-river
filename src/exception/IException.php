<?php

/**
 * Interface and abstrac Exception class definition.
 * @author llaine
 * @namespace exception
 */

namespace exception;


/**
 * Interface IException
 * @package exception
 */
interface IException
{
    /* Protected methods inherited from Exception class */

    /**
     * Exception message
     * @return mixed
     */
    public function getMessage();

    /**
     * User-defined Exception code
     * @return mixed
     */
    public function getCode();

    /**
     * Source filename
     * @return mixed
     */
    public function getFile();

    /**
     * Source line
     * @return mixed
     */
    public function getLine();

    /**
     * An array of the backtrace()
     * @return mixed
     */
    public function getTrace();

    /**
     * Formated string of trace
     * @return mixed
     */
    public function getTraceAsString();

    /* Overrideable methods inherited from Exception class */

    /**
     * Formated string for display
     * @return mixed
     */
    public function __toString();

    /**
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0);
}

/**
 * Class CustomException
 * @package exception
 */
abstract class CustomException extends \Exception implements IException
{
    /**
     * Exception message
     * @var string
     */
    protected $message = 'Unknown exception';
    /**
     * Unknow
     * @var
     */
    private   $string;
    /**
     * User-defined exception code
     * @var int
     */
    protected $code    = 0;
    /**
     * Source filename of exception
     * @var
     */
    protected $file;
    /**
     * Source line of exception
     * @var
     */
    protected $line;
    /**
     * @var
     */
    private   $trace;

    /**
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
        . "{$this->getTraceAsString()}";
    }
}