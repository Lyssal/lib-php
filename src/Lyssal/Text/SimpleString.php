<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Text;

/**
 * Class to manipulate a string.
 */
class SimpleString extends AbstractText
{
    /**
     * Delete accents.
     *
     * @return \Lyssal\Text\SimpleString String without accent
     */
    public function deleteAccents()
    {
        $this->text = str_replace(
            array('À','Â','à','á','â','ã','ä','å','Ô','ò','ó','ô','õ','ö','ō','ø','È','É','Ê','è','é','ê','ë','Ç','ç','Î','ì','í','î','ï','ù','ú','û','ü','ū','ÿ','ñ'),
            array('A','A','a','a','a','a','a','a','O','o','o','o','o','o','o','o','E','E','E','e','e','e','e','C','c','I','i','i','i','i','u','u','u','u','u','y','n'),
            $this->text
        );

        return $this;
    }

    /**
     * Minify the string ; for example to use in file names or for an URL.
     *
     * @param string  $separator Separator in replacement of some special characters like spaces
     * @param boolean $lowercase If result is in lowercase
     * @return \Lyssal\Text\SimpleString The minify string
     */
    public function minify($separator = '-', $lowercase = true)
    {
        $this->deleteAccents();

        $this->text = trim(
            preg_replace(
                '/([^a-zA-Z0-9])+/',
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
    public function hasLetter()
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
    public function hasDigit()
    {
        return (1 === preg_match('/\d/', $this->text));
    }
}
