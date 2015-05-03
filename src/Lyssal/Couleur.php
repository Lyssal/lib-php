<?php
namespace Lyssal;

/**
 * Classe permettant de manipuler des couleurs.
 * 
 * @author Rémi Leclerc
 */
class Couleur
{
    /**
     * @var integer Nuance de rouge
     */
    private $rouge;

    /**
     * @var integer Nuance de vert
     */
    private $vert;
    
    /**
     * @var integer Nuance de bleu
     */
    private $bleu;

    
    /**
     * Constructeur de Couleur.
     *
     * @param string $texte Couleur en hexadécimal
     */
    public function __construct($couleurHexadecimal)
    {
        $this->initCouleurHexadecimal($couleurHexadecimal);
    }
    
    
    /**
     * Retourne la couleur en hexadécimal.
     * 
     * @return string Couleur
     */
    public function getCouleurHexadecimal()
    {
        return '#'.str_pad(dechex($this->rouge), 2, 0, STR_PAD_LEFT).str_pad(dechex($this->vert), 2, 0, STR_PAD_LEFT).str_pad(dechex($this->bleu), 2, 0, STR_PAD_LEFT);
    }
    
    
    /**
     * Initialise la couleur à partir de son code hexadecimal.
     * 
     * @throws \Exception Erreur si le code hexadécimal est incorrect
     * @return void
     */
    private function initCouleurHexadecimal($couleurHexadecimal)
    {
        $couleurHexadecimal = str_replace('#', '', $couleurHexadecimal);
        
        if (strlen($couleurHexadecimal) == 3)
            $couleurHexadecimal = str_repeat(substr($couleurHexadecimal, 0, 1), substr($couleurHexadecimal, 1, 1), substr($couleurHexadecimal, 2, 1));
        else if (strlen($couleurHexadecimal) != 6)
            throw new \Exception('Le code couleur hexadécimal "'.$couleurHexadecimal.'" est incorrect.');
        
        list($rouge, $vert, $bleu) = str_split($couleurHexadecimal, 2);
        $this->rouge = hexdec($rouge);
        $this->vert = hexdec($vert);
        $this->bleu = hexdec($bleu);
    }

    /**
     * Éclaircit la couleur.
     * 
     * @param integer $pourcentage Pourcentage d'éclaircissement
     * @return \Lyssal\Couleur
     */
    public function lighten($pourcentage)
    {
        return $this->adjustLuminosite($pourcentage);
    }
    
    /**
     * Obscurcit la couleur.
     * 
     * @param integer $pourcentage Pourcentage d'obscurcissement
     * @return \Lyssal\Couleur
     */
    public function darken($pourcentage)
    {
        return $this->adjustLuminosite(- $pourcentage);
    }
    
    /**
     * Ajuste la luminosité de la couleur.
     * 
     * @param integer $pourcentageSigne Pourcentage (de -100 à 100).
     * @return \Lyssal\Couleur
     */
    private function adjustLuminosite($pourcentageSigne)
    {
        $this->rouge = max(0, min(255, $this->rouge + 2.55 * $pourcentageSigne));
        $this->vert = max(0, min(255, $this->vert + 2.55 * $pourcentageSigne));
        $this->bleu = max(0, min(255, $this->bleu + 2.55 * $pourcentageSigne));
        
        return $this;
    }
}
