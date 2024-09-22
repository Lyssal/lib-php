<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Text;
use Lyssal\Exception\LyssalException;

/**
 * Class to manipulate a string.
 */
class SimpleString extends AbstractText
{
    /**
     * @var string The allowed characters when we minify the string
     */
    public static $MINIFICATION_ALLOWED_CHARACTERS = 'a-zA-Z0-9';

    /**
     * @var bool If the intl extension is activated
     */
    private static bool $INTL_ACTIVATED;

    /**
     * Check if the intl extension is activated.
     *
     * @return bool
     */
    private static function isIntlActivated(): bool
    {
        if (!isset(self::$INTL_ACTIVATED)) {
            self::$INTL_ACTIVATED = \extension_loaded('intl');
        }

        return self::$INTL_ACTIVATED;
    }

    /**
     * Delete accents.
     *
     * @return \Lyssal\Text\SimpleString The Lyssal SimpleString instance
     */
    public function deleteAccents(): self
    {
        $this->text = str_replace(
            array('À','Â','à','á','â','ã','ä','å','Ô','ò','ó','ô','õ','ö','ō','ø','È','É','Ê','è','é','ê','ë','Ç','ç','Î','ì','í','î','ï','ù','ú','û','ü','ū','ÿ','ñ'),
            array('A','A','a','a','a','a','a','a','O','o','o','o','o','o','o','o','E','E','E','e','e','e','e','C','c','I','i','i','i','i','u','u','u','u','u','y','n'),
            $this->text
        );

        return $this;
    }

    /**
     * Transliterate the text into latin ASCII.
     *
     * @throws \Lyssal\Exception\LyssalException
     *
     * @return \Lyssal\Text\SimpleString The Lyssal SimpleString instance
     */
    public function transliterateToLatinAscii(): self
    {
        if (!self::isIntlActivated()) {
            throw new LyssalException('The intl extension is not activated.');
        }

        $this->text = (\Transliterator::create('Any-Latin; Latin-ASCII'))->transliterate($this->text);

        return $this;
    }

    /**
     * Minify the string ; for example to use in file names or for an URL.
     *
     * @param string  $separator Separator in replacement of some special characters like spaces
     * @param boolean $lowercase If result is in lowercase
     *
     * @return \Lyssal\Text\SimpleString The Lyssal SimpleString instance
     */
    public function minify(string $separator = '-', bool $lowercase = true): self
    {
        if (self::isIntlActivated()) {
            $this->transliterateToLatinAscii();
        } else {
            $this->deleteAccents();
        }

        $this->text = trim(
            preg_replace(
                '/([^'.self::$MINIFICATION_ALLOWED_CHARACTERS.'])+/',
                $separator,
                str_replace(
                    array(',', ';', '?', '!', ':', '"', '{', '}', '(', ')', '[', ']', '«', '»', '='),
                    '',
                    str_replace(
                        array('²', 'œ', 'Œ', ''),
                        array('2', 'oe', 'Oe', 'Oe'),
                        $this->text
                    )
                )
            )
        );

        if ($lowercase) {
            $this->text = strtolower($this->text);
        }

        // Delete separators in double and in beggining and end
        if ('' !== $separator) {
            $this->text = preg_replace('/(\\'.$separator.')+/', $separator, trim($this->text, $separator));
        }

        return $this;
    }

    /**
     * Return if the string has a letter.
     *
     * @return boolean If has a letter
     */
    public function hasLetter(): bool
    {
        $stringWithoutAccent = new SimpleString($this->text);
        $stringWithoutAccent->deleteAccents();

        return (1 === preg_match('/[a-zA-Z]/', $stringWithoutAccent->getText()));
    }

    /**
     * Return if the string has a digit.
     *
     * @return boolean If has a digit
     */
    public function hasDigit(): bool
    {
        return (1 === preg_match('/\d/', $this->text));
    }
}
