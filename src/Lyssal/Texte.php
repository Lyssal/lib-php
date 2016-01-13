<?php
namespace Lyssal;

/**
 * Classe permettant de manipuler du texte.
 *
 * @author Rémi Leclerc
 */
abstract class Texte
{
    /**
     * @var string Texte manipulé
     */
    protected $texte;


    /**
     * Constructeur.
     *
     * @param string $texte Texte à manipuler
     */
    public function __construct($texte)
    {
        $this->texte = $texte;
    }


    /**
     * Retourne le texte manipulé.
     * 
     * @return string Texte
     */
    public function getTexte()
    {
        return $this->texte;
    }


    /**
     * Remplace dans le texte.
     * Cette méthode est semblable à str_replace().
     * 
     * @param string|array $recherche    Contenu à rechercher
     * @param string|array $remplacement Contenu de remplacement
     */
    public function replace($recherche, $remplacement)
    {
        $this->texte = str_replace($recherche, $remplacement, $this->texte);
    }

    /**
     * Encode la chaîne en HTML.
     *
     * @return \Lyssal\Chaine La chaîne de caractères HTML.
     */
    public function encodeHtml()
    {
        $this->texte = str_replace(array('&lt;','&gt;','&amp;'), array('<','>','&'), htmlentities($this->texte, ENT_NOQUOTES, 'UTF-8'));
    
        return $this;
    }
}
