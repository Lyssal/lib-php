<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\File;

use Lyssal\Exception\InvalidArgumentException;
use Lyssal\Exception\IoException;

/**
 * Class to get or set content from a file.
 */
class FileContent
{
    /**
     * @var \Lyssal\File\File The file
     */
    protected $file;

    /**
     * @var boolean If cUrl can be used
     */
    protected $curl = true;

    /**
     * @var string The proxy URL
     */
    protected $proxyName;

    /**
     * @var string|null The proxy host
     */
    protected $proxyHost;

    /**
     * @var int|null The proxy type
     */
    protected $proxyType;

    /**
     * @var int|null The proxy post
     */
    protected $proxyPort;


    /**
     * The constructor.
     *
     * @param \Lyssal\File\File|string $fileOrPathname A File object or a pathname
     */
    public function __construct($fileOrPathname)
    {
        if ($fileOrPathname instanceof File) {
            $this->file = $fileOrPathname;
        } elseif (is_string($fileOrPathname)) {
            $this->file = new File($fileOrPathname);
        } else {
            throw new InvalidArgumentException('The argument of the constructor\'s class FileInfo have to be a string or a '.File::class.' object.');
        }
    }


    /**
     * Get the file.
     *
     * @return \Lyssal\File\FileContent The file
     */
    public function getFile()
    {
      return $this->file;
    }

    /**
     * Set the file.
     *
     * @param \Lyssal\File\File $file The file
     * @return \Lyssal\File\FileContent This
     */
    public function setFile($file)
    {
      $this->file = $file;

      return $this;
    }

    /**
     * Return if cUrl can be used.
     *
     * @return boolean If cUrl can be used
     */
    public function isCurl()
    {
        return $this->file->isUrl() && $this->curl;
    }

    /**
     * Set if cUrl can be used.
     *
     * @param boolean $curl If cUrl can be used
     * @return \Lyssal\File\FileContent The file
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;

        return $this;
    }

    /**
     * Return if cUrl must to be used to get the file content.
     *
     * @return bool If cUrl must be used
     */
    protected function mustUseCurl()
    {
        return ($this->isCurl() && function_exists('curl_init'));
    }


    /**
     * Set a proxy URL (evently with scheme and port).
     *
     * @param string $proxy A proxy
     */
    public function setProxyName($proxy)
    {
        $this->proxyName = trim($proxy, '/');

        $schemePositionEnd = strpos($proxy, '://');
        if (false !== $schemePositionEnd) {
            $this->setProxyScheme(substr($proxy, 0, $schemePositionEnd));
            $proxy = substr($proxy, $schemePositionEnd + strlen('://'));
        }

        $portPositionStart = strpos($proxy, ':');
        if (false !== $portPositionStart) {
            $this->setProxyPort(trim(substr($proxy, $portPositionStart + 1), '/'));
            $proxy = substr($proxy, 0, $portPositionStart);
        }

        $this->setProxyHost($proxy);
    }

    /**
     * Set the proxy host.
     *
     * @param string $proxyHost The proxy host
     */
    public function setProxyHost($proxyHost)
    {
        $this->proxyHost = $proxyHost;
    }

    /**
     * Set the proxy scheme.
     *
     * @param string $proxyScheme The proxy scheme
     */
    public function setProxyScheme($proxyScheme)
    {
        switch ($proxyScheme) {
            case 'http':
                $this->setProxyType(CURLPROXY_HTTP);
                break;
            case 'https':
                if (defined('CURLPROXY_HTTPS')) {
                    $this->setProxyType(CURLPROXY_HTTPS);
                }
                break;
            case 'socks4':
                $this->setProxyType(CURLPROXY_SOCKS4);
                break;
            case 'socks4a':
                if (defined('CURLPROXY_SOCKS4A')) {
                    $this->setProxyType(CURLPROXY_SOCKS4A);
                }
                break;
            case 'socks5':
                $this->setProxyType(CURLPROXY_SOCKS5);
                break;
            case 'socks5h':
                if (defined('CURLPROXY_SOCKS5_HOSTNAME')) {
                    $this->setProxyType(CURLPROXY_SOCKS5_HOSTNAME);
                }
                break;
        }
    }

    /**
     * Set the proxy type (constant CURLPROXY_*).
     *
     * @param int $proxyType The proxy type
     */
    public function setProxyType($proxyType)
    {
        $this->proxyType = $proxyType;
    }

    /**
     * Set the proxy port.
     *
     * @param int $proxyPort The proxy port
     */
    public function setProxyPort($proxyPort)
    {
        $this->proxyPort = $proxyPort;
    }


    /**
     * Get the file content.
     *
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return string|null Content or NULL is unreadable
     */
    public function get($streamContext = null)
    {
        return $this->getPortion(0, null, $streamContext);
    }

    /**
     * Get a portion of the file content.
     *
     * @param int                                            $start         The start'th position ; if negative, starts at the start'th character from the end
     * @param int|null                                       $length        The length of the returned content (if null, get the file length - $length)
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     *
     * @return string|null Content or NULL is unreadable
     */
    public function getPortion($start, $length = null, $streamContext = null)
    {
        if ($start < 0) {
            $start = $this->getFile()->getSize() + $start;
        }

        if ($this->mustUseCurl()) {
            $content = $this->getWithCurl([
                CURLOPT_RESUME_FROM => $start
            ]);

            if (null !== $length) {
                $content = substr($content, 0, $length);
            }
        } else {
            if (null === $streamContext) {
                $streamContext = $this->getDefaultStreamContext();
            }

            if (null === $length) {
                $content = file_get_contents($this->file->getRealPathname(), false, StreamContext::getRealStreamContext($streamContext), $start);
            } else {
                $content = file_get_contents($this->file->getRealPathname(), false, StreamContext::getRealStreamContext($streamContext), $start, $length);
            }

            if (false === $content) {
                $content = null;
            }
        }

        return $content;
    }

    /**
     * Get content with cUrl.
     *
     * @param array<string, string> $curlOptions The cUrl options
     *
     * @return string|null Content or NULL is unreadable
     */
    protected function getWithCurl($curlOptions = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->file->getRealPathname());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (null !== $this->proxyHost) {
            curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($curl, CURLOPT_PROXY, $this->proxyHost);
            if (null !== $this->proxyType) {
                curl_setopt($curl, CURLOPT_PROXYTYPE, $this->proxyType);
            }
            if (null !== $this->proxyPort) {
                curl_setopt($curl, CURLOPT_PROXYPORT, $this->proxyPort);
            }
        }

        foreach ($curlOptions as $curlOption => $value) {
            curl_setopt($curl, $curlOption, $value);
        }

        $content = curl_exec($curl);
        curl_close($curl);

        if (false === $content) {
            return null;
        }
        return $content;
    }

    /**
     * Set content in file.
     *
     * @param string                                         $content       New content
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @throws \Lyssal\Exception\IoException If can not write in file
     */
    public function set($content, $streamContext = null)
    {
        if (null === $streamContext) {
            $streamContext = $this->getDefaultStreamContext();
        }
        $contentSize = file_put_contents($this->file->getPathname(), $content, null, StreamContext::getRealStreamContext($streamContext));

        if (false === $contentSize) {
            throw new IoException('Not authorized to write in "'.$this->file->getFilename().'".');
        } else {
            clearstatcache(true, $this->file->getPathname());
        }
    }


    /**
     * Get the default stream context.
     *
     * @return \Lyssal\File\StreamContext The default stream context
     */
    protected function getDefaultStreamContext()
    {
        $streamContext = new StreamContext();
        $streamContext->addOption('http.ignore_errors', true);

        if (null !== $this->proxyName && null !== $this->file->getUrl()) {
            $streamContext->addOption('http.proxy', $this->proxyName);
        }

        return $streamContext;
    }
}
