<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Text;

/**
 * Abstract class to manipulate text.
 */
abstract class AbstractText
{
    /**
     * @var string Manipulated text
     */
    protected $text;


    /**
     * Constructor.
     *
     * @param string $text Text to manipulate
     */
    public function __construct($text)
    {
        $this->text = $text;
    }


    /**
     * Return the manipulated text.
     *
     * @return string The text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the text.
     *
     * @param string $text Tthe new text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


    /**
     * Replace in the text (as str_replace()).
     *
     * @param string|array<string> $search  Value to search
     * @param string|array<string> $replace Replacement
     */
    public function replace($search, $replace)
    {
        $this->text = str_replace($search, $replace, $this->text);
    }

    /**
     * Replace <br> into \n.
     *
     * @return \Lyssal\Text The string
     */
    public function br2nl()
    {
        $this->text = preg_replace('/\<br(\s*)?(\/)?\>/i', PHP_EOL, $this->text);

        return $this;
    }

    /**
     * Encode text in HTML.
     *
     * @return \Lyssal\Text HTML
     */
    public function encodeHtml()
    {
        $this->text = str_replace(array('&lt;','&gt;','&amp;'), array('<','>','&'), htmlentities($this->text, ENT_NOQUOTES, 'UTF-8'));

        return $this;
    }
}
