<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Text;

/**
 * Util class to create slugs.
 */
class Slug extends SimpleString
{
    /**
     * Create a next slug.
     *
     * @param string $separator Separator
     */
    public function next($separator = '-')
    {
        $textMatches = array();
        if (false !== preg_match('/(.*)(\\'.$separator.'){1}([0-9]+)$/', $this->text, $textMatches) && 4 === count($textMatches)) {
            $this->text = $textMatches[1].$separator.(((int) $textMatches[3]) + 1);
        } else {
            $this->text = $this->text.$separator.'1';
        }
    }
}
