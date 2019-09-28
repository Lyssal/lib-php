<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Text;

use DOMDocument;
use DOMXPath;

/**
 * Class to manipulate HTML.
 */
class Html extends AbstractText
{
    /**
     * Delete tags.
     *
     * @param string[] $tags Tags to delete
     * @return \Lyssal\Text\Html This
     */
    public function deleteTags(array $tags)
    {
        $tagsToDeleteRegex = array();
        foreach ($tags as $tagToDelete) {
            $tagsToDeleteRegex[] = '@<'.$tagToDelete.'[^>]*?>.*?</'.$tagToDelete.'>@siu';
        }
        $this->text = preg_replace($tagsToDeleteRegex, '', $this->text);

        return $this;
    }

    /**
     * Transforms isolated URL into HTTM links.
     *
     * @return \Lyssal\Text\Html This
     */
    public function makeClickableLinks()
    {
        $this->text = trim(preg_replace(
            "#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i",
            "$1$3</a>",
            preg_replace_callback(
                '#([\s>])(([a-zA-Z0-9\-]+\.){1}[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
                function ($matches) {
                    return $this->makeClickableUrlCallback($matches, 'http://');
                },
                preg_replace_callback(
                    '#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
                    function ($matches) {
                        return $this->makeClickableUrlCallback($matches);
                    },
                    ' '.$this->text
                )
            )
        ));

        return $this;
    }

    /**
     * Transforms URL into HTML links (called by preg_replace_callback).
     *
     * @param array  $matches Matches
     * @param string $prefix  Prefix to add to links
     * @return string HTML
     */
    protected function makeClickableUrlCallback(array $matches, $prefix = '')
    {
        $text = '';
        $url = $matches[2];

        if ('' == $url) {
            return $matches[0];
        }

        if (in_array(substr($url, -1), array('.', ',', ';', ':'), false)) {
            $text = substr($url, -1);
            $url = substr($url, 0, -1);
        }

        return $matches[1].'<a href="'.$prefix.$url.'">'.$url.'</a>'.$text;
    }

    /**
     * Transforms isolated emails into mailto links.
     *
     * @return \Lyssal\Text\Html This
     */
    public function makeClickableEmails()
    {
        $this->text = trim(preg_replace(
            "#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i",
            "$1$3</a>",
            preg_replace_callback(
                '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i',
                function ($matches) {
                    return $this->makeClickableEmailCallback($matches);
                },
                ' '.$this->text
            )
        ));

        return $this;
    }

    /**
     * Transforms emails into mailto links (called by preg_replace_callback).
     *
     * @param array $matches Matches
     * @return string HTML
     */
    protected function makeClickableEmailCallback(array $matches)
    {
        $email = $matches[2].'@'.$matches[3];
        return $matches[1].'<a href="mailto:'.$email.'">'.$email.'</a>';
    }

    public function addTargetBlankToLinks(): self
    {
        $document = new DOMDocument();
        $document->loadHTML($this->text);

        $xPath = new DOMXPath($document);
        $links = $xPath->query('//a[starts-with(@href, "http://")]');

        /**
         * @var \DOMNode $link
         */
        foreach ($links as $link) {
            if (!$link->hasAttribute('target') && $link->hasAttribute('href')) {
                $link->setAttribute('target', '_blank');
            }
        }

        $this->text = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array("\n".'<html>', '</html>'."\n", '<body>', '</body>'), array('', '', '', ''), $document->saveHTML()));

        return $this;
    }
}
