<?php

declare(strict_types=1);

namespace Mlevent\Ivd;

use Exception;

class IvdException extends Exception
{
    /**
     * @var array
     */
    private $request;

    /**
     * @var array
     */
    private $response;

    public function __construct(
        string    $message  = null, 
        mixed     $request  = null,
        mixed     $response = null,
        int       $code     = 0, 
        Exception $previous = null, 
    ) {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * getResponse
     *
     * @return mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * hasResponse
     *
     * @return boolean
     */
    public function hasResponse(): bool
    {
        return $this->response !== null;
    }

    /**
     * getRequest
     *
     * @return mixed
     */
    public function getRequest(): mixed
    {
        return $this->request;
    }
}