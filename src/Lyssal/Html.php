<?php
namespace Lyssal;

use Lyssal\Texte;

/**
 * Classe permettant de manipuler du HTML.
 *
 * @author Rémi Leclerc
 */
class Html extends Texte
{
    /**
     * Transforme les URL en liens HREF.
     *
     * @return \Lyssal\HTML Instance
     */
    public function makeClickableLinks()
    {
        $this->texte = trim(preg_replace(
            "#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i",
            "$1$3</a>",
            preg_replace_callback(
                '#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
                function ($matches) {
                    return $this->makeClickableUrlCallback($matches, 'http://');
                },
                preg_replace_callback(
                    '#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
                    function ($matches) {
                        return $this->makeClickableUrlCallback($matches);
                    },
                    ' '.$this->texte
                )
            )
        ));

        return $this;
    }

    /**
     * Transforme les adresses électroniques en liens MAILTO.
     *
     * @return \Lyssal\HTML Instance
     */
    public function makeClickableEmails()
    {
        $this->texte = trim(preg_replace(
            "#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i",
            "$1$3</a>",
            preg_replace_callback(
                '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i',
                function ($matches) {
                    return $this->makeClickableEmailCallback($matches);
                },
                ' '.$this->texte
            )
        ));

        return $this;
    }

    /**
     * Transforme les URL en liens HTML (appelée par preg_replace_callback).
     *
     * @param array $matches Matches
     * @param string $prefix Préfixe ajouté automatiquement aux liens
     * @return string HTML
     */
    private function makeClickableUrlCallback(array $matches, $prefix = '')
    {
        $text = '';
        $url = $prefix.$matches[2];

        if ('' == $url) {
            return $matches[0];
        }

        if (in_array(substr($url, -1), array('.', ',', ';', ':'))) {
            $text = substr($url, -1);
            $url = substr($url, 0, strlen($url)-1);
        }

        return $matches[1].'<a href="'.$url.'">'.$url.'</a>'.$text;
    }

    /**
     * Transforme les adresses électroniques en liens mailto (appelée par preg_replace_callback).
     *
     * @param array $matches Matches
     * @return string HTML
     */
    private function makeClickableEmailCallback(array $matches)
    {
        $email = $matches[2].'@'.$matches[3];
        return $matches[1].'<a href="mailto:'.$email.'">'.$email.'</a>';
    }
}
