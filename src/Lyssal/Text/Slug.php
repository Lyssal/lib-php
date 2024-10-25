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
     *
     * @return \Lyssal\Text\Slug The Lyssal Slug instance
     */
    public function next(string $separator = '-'): self
    {
        $textMatches = [];

        if (false !== preg_match('/(.*)(\\'.$separator.'){1}([0-9]+)$/', $this->text, $textMatches) && 4 === count($textMatches)) {
            $this->text = $textMatches[1].$separator.(((int) $textMatches[3]) + 1);
        } else {
            $this->text = $this->text.$separator.'1';
        }

        return $this;
    }
}
