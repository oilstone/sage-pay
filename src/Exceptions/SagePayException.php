<?php

namespace Oilstone\SagePay\Exceptions;

use Exception;
use Throwable;

/**
 * Class SagePayException
 * @package Oilstone\SagePay\Exceptions
 */
class SagePayException extends Exception
{
    /**
     * @var string
     */
    protected static $logId;

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var null|Throwable
     */
    protected $previous;

    /**
     * SagePayException constructor.
     * @param int $errorCode
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(int $errorCode, string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorCode = $errorCode;

        $this->message = $message;

        $this->code = $code;

        $this->previous = $previous;
    }

    /**
     * @param string $logId
     */
    public static function setLogId(string $logId)
    {
        static::$logId = $logId;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
}