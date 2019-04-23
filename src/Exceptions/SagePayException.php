<?php

namespace Oilstone\SagePay\Exceptions;

use Exception;
use Oilstone\Logging\Log;
use Throwable;

/**
 * Class SagePayException
 * @package Oilstone\SagePay\Exceptions
 */
class SagePayException extends Exception
{
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

        Log::error('Sage Pay exception', compact('errorCode', 'message', 'code'));
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
}