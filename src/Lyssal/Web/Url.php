<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Web;

use Lyssal\Web\Http\Status;

/**
 * Have informations about an URL. You can know the status page or directly get the status code.
 */
class Url
{
    /**
     * @var string Url
     */
    protected $url;

    /**
     * @var \Lyssal\Http\Status|null Status
     */
    protected $status;


    /**
     * Constructor.
     *
     * @param string $url URL
     */
    public function __construct($url)
    {
        $this->setUrl($url);
    }


    /**
     * Initialize the URL status.
     */
    protected function initStatus()
    {
        if (null === $this->status) {
            $urlMatches = [];
            $urlHeaders = get_headers($this->url);

            if (preg_match('/^HTTP\/([0-9\.]+)\ ([0-9]{3})\ /', $urlHeaders[0], $urlMatches)) {
                $this->status = new Status((int) $urlMatches[2]);
            }
        }
    }


    /**
     * Retourne l'URL.
     *
     * @return string URL
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set URL.
     * @param string $url URL
     * @return \Lyssal\Web\Url URL
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->initStatus();
        return $this;
    }

    /**
     * Return the URL status.
     * @return \Lyssal\Http\Status|null Status or NULL if not found
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Return the status code.
     * @return int|null Code or NULL if not found
     */
    public function getStatusCode()
    {
        if (null !== $this->status) {
            return $this->status->getCode();
        }

        return null;
    }

    /**
     * Return the host name.
     *
     * @return string The host name
     */
    public function getHost()
    {
        return parse_url($this->url, PHP_URL_HOST);
    }

    /**
     * Return the scheme.
     *
     * @return string The scheme
     */
    public function getScheme()
    {
        return parse_url($this->url, PHP_URL_SCHEME);
    }


    /**
     * Return if status is informational.
     * @return bool If informational
     */
    public function statusIsInformational()
    {
        if (null !== $this->status) {
            return $this->status->isInformational();
        }

        return false;
    }

    /**
     * Return if status is success.
     * @return bool If success
     */
    public function statusIsSuccess()
    {
        if (null !== $this->status) {
            return $this->status->isSuccess();
        }

        return false;
    }

    /**
     * Return if status is redirection.
     * @return bool If redirection
     */
    public function statusIsRedirection()
    {
        if (null !== $this->status) {
            return $this->status->isRedirection();
        }

        return false;
    }

    /**
     * Return if status is client error.
     * @return bool If client error
     */
    public function statusIsClientError()
    {
        if (null !== $this->status) {
            return $this->status->isClientError();
        }

        return false;
    }

    /**
     * Return if status is server error.
     * @return bool If server error
     */
    public function statusIsServerError()
    {
        if (null !== $this->status) {
            return $this->status->isServerError();
        }

        return false;
    }

    /**
     * Return if the URL exists.
     * @return bool If exists
     */
    public function exists()
    {
        $statusCode = $this->getStatusCode();

        return (null !== $statusCode && Status::NOT_FOUND !== $statusCode);
    }
}
