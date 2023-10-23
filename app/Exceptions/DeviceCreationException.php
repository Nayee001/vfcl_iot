<?php

namespace App\Exceptions;

use Exception;

class DeviceCreationException extends Exception
{
    /**
     * Unique error code for the exception.
     */
    protected $errorCode;

    /**
     * Contextual information.
     */
    protected $context = [];

    /**
     * HTTP status code for the response.
     */
    protected $httpStatusCode = 500;

    /**
     * Construct the exception.
     */
    public function __construct($message, $errorCode = 0, array $context = [], Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errorCode = $errorCode;
        $this->context = $context;
    }

    /**
     * Get the error code.
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Get the context.
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set the context.
     */
    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    /**
     * Get a detailed error report.
     */
    public function getDetailedReport(): string
    {
        return "Error Code: {$this->errorCode}, Message: {$this->message}";
    }

    /**
     * Get the HTTP status code.
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Set the HTTP status code.
     */
    public function setHttpStatusCode(int $code): void
    {
        $this->httpStatusCode = $code;
    }

    /**
     * Log the exception details.
     */
    public function logException(): void
    {
        \Log::error("Error creating device: {$this->getDetailedReport()}, Context: " . json_encode($this->getContext()));
    }
}
