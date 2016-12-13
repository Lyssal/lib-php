<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Http;

/**
 * A URL Status. You can know the type of a status code.
 *
 * @see \Lyssal\Url::__construct() To know the status of an URL
 */
class Status
{
    /**
     * @var int Code for OK
     */
    const OK = 200;

    /**
     * @var int Code for Created
     */
    const CREATED = 201;

    /**
     * @var int Code for Accepted
     */
    const ACCEPTED = 202;

    /**
     * @var int Code for Moved Permanently
     */
    const MOVED_PERMANENTLY = 301;

    /**
     * @var int Code for Not Modified
     */
    const NOT_MODIFIED = 304;

    /**
     * @var int Code for Bad Request
     */
    const BAD_REQUEST = 400;

    /**
     * @var int Code for Unauthorized
     */
    const UNAUTHORIZED = 401;

    /**
     * @var int Code for Forbidden
     */
    const FORBIDDEN = 403;

    /**
     * @var int Code for Not found
     */
    const NOT_FOUND = 404;

    /**
     * @var int Code for Internal Server Error
     */
    const INTERNAL_SERVER_ERROR = 500;


    /**
     * @var int Code
     */
    protected $code;


    /**
     * StatusCode constructor.
     * @param int $statusCode Code
     */
    public function __construct($statusCode)
    {
        $this->code = $statusCode;
    }


    /**
     * Return status code.
     * @return int Code
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * Return if status is informational.
     * @return bool If informational
     */
    public function isInformational()
    {
        return ($this->code >= 100 && $this->code < 200);
    }

    /**
     * Return if status is success.
     * @return bool If success
     */
    public function isSuccess()
    {
        return ($this->code >= 200 && $this->code < 300);
    }

    /**
     * Return if status is redirection.
     * @return bool If redirection
     */
    public function isRedirection()
    {
        return ($this->code >= 300 && $this->code < 400);
    }

    /**
     * Return if status is client error.
     * @return bool If client error
     */
    public function isClientError()
    {
        return ($this->code >= 400 && $this->code < 500);
    }

    /**
     * Return if status is server error.
     * @return bool If server error
     */
    public function isServerError()
    {
        return ($this->code >= 500 && $this->code < 600);
    }
}