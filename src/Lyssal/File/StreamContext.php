<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\File;

/**
 * Classe to generate a stream context.
 */
class StreamContext
{
    /**
     * @var array The options
     */
    protected $options;

    /**
     * @var array The params
     */
    protected $params;


    /**
     * The constructor.
     *
     * @param array $options The options
     * @param array $params  The params
     */
    public function __construct(array $options = array(), array $params = array())
    {
        $this->options = $options;
        $this->params = $params;
    }


    /**
     * Get the options.
     *
     * @return array The options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get the params.
     *
     * @return array The params
     */
    public function getParams()
    {
        return $this->params;
    }


    /**
     * Add an option.
     *
     * @param string $key   The option key (for example: http.proxy)
     * @param string $value The option value
     * @return \Lyssal\File\StreamContext This
     */
    public function addOption($key, $value)
    {
        $option = array();
        $subOption = &$option;
        $optionKeys = explode('.', $key);

        foreach ($optionKeys as $optionKey) {
            $subOption[$optionKey] = array();
            $subOption = &$subOption[$optionKey];
        }
        $subOption = $value;

        $this->addOptions($option);

        return $this;
    }

    /**
     * Add options.
     *
     * @param array $options The options in adding
     * @return \Lyssal\File\StreamContext This
     */
    public function addOptions(array $options)
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    /**
     * Add params.
     *
     * @param array $params The params in adding
     * @return \Lyssal\File\StreamContext This
     */
    public function addParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }


    /**
     * Get the PHP context stream.
     *
     * @return resource The PHP context stream
     */
    public function get()
    {
        $options = (count($this->options) > 0 ? $this->options : null);
        $params = (count($this->params) > 0 ? $this->params : null);

        return stream_context_create($options, $params);
    }


    /**
     * A stream context.
     *
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return resource The PHP stream context
     */
    public static function getRealStreamContext($streamContext = null)
    {
        if (null === $streamContext) {
            return null;
        }

        if (is_array($streamContext)) {
            return stream_context_create($streamContext);
        }
        if ($streamContext instanceof StreamContext) {
            return $streamContext->get();
        }

        return $streamContext;
    }
}
