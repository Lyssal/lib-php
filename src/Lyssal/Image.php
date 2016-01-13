<?php
namespace Lyssal;

/**
 * Classe permettant de manipuler des images.
 * 
 * @author Rémi Leclerc
 */
class Image extends Fichier
{
    /**
     * @var resource Ressource GD
     */
    private $resourceGd;
    
    /**
     * @var integer Largeur de l'image (en pixels)
     */
    private $largeur;
    
    /**
     * @var integer Hauteur de l'image (en pixels)
     */
    private $hauteur;
    
    /**
     * @var integer Type d'image. cf. IMAGETYPE_XXX
     */
    private $type;
    
    /**
     * Constructeur d'une image.
     *
     * @param string $imagePath Chemin de l'image à traiter
     */
    public function __construct($imagePath)
    {
        parent::__construct($imagePath);
        
        $this->initProprietes();
        $this->initResourceGd();
    }
    
    /**
     * Retourne la largeur de l'image (en pixels).
     * 
     * @return integer Largeur
     */
    public function getLargeur()
    {
        return $this->largeur;
    }
    /**
     * Retourne la hauteur de l'image (en pixels).
     *
     * @return integer Hauteur
     */
    public function getHauteur()
    {
        return $this->hauteur;
    }
    
    /**
     * Initialise la resource GD.
     * 
     * @return void
     */
    protected function initResourceGd()
    {
        switch ($this->type)
        {
            case IMAGETYPE_JPEG:
                $this->resourceGd = imagecreatefromjpeg($this->getPathname());
                break;
            case IMAGETYPE_PNG:
                $this->resourceGd = imagecreatefrompng($this->getPathname());
                break;
            case IMAGETYPE_GIF:
                $this->resourceGd = imagecreatefromgif($this->getPathname());
                break;
            default:
                throw new \Exception('Le format de l\'image n\'est pas correct.');
        }
    }
    
    /**
     * Initialise les dimensions et le type de l'image.
     * 
     * @return void
     */
    protected function initProprietes()
    {
        $dimensions = getimagesize($this->getPathname());

        $this->largeur = $dimensions[0];
        $this->hauteur = $dimensions[1];
        $this->type = $dimensions[2];
    }
    
    /**
     * Copie l'image.
     * 
     * @param string $chemin Chemin où sera copié l'image
     * @param boolean $remplaceSiExistant Si FAUX le nom du fichier pourra être modifié pour ne pas à avoir à remplacer un fichier existant
     * @return \Lyssal\Image|NULL Image créée ou NIL si la copie a échoué
     */
    public function copy($chemin, $remplaceSiExistant = false)
    {
        $fichier = parent::copy($chemin, $remplaceSiExistant);
        if (null !== $fichier) {
            return $this->getImageFromFichier($fichier);
        }
        
        return null;
    }
    
    /**
     * Retourne une Image depuis un Fichier.
     *
     * @param \Lyssal\Fichier $fichier
     */
    private function getImageFromFichier($fichier)
    {
        return new Image($fichier->getPathname());
    }
    
    /**
     * Modifie la taille de l'image.
     *
     * @param integer|NULL $nouvelleLargeur Largeur de la nouvelle image en pixels (NULL si garder les mêmes proportions par rapport à la hauteur)
     * @param integer|NULL $nouvelleHauteur Hauteur de la nouvelle image en pixels (NULL si garder les mêmes proportions par rapport à la largeur)
     * @param boolean $conserveProportions Si FAUX, l'image sera éventuellement rognée sur un côté plutôt qu'être déformée
     * @return void
     */
    public function redimensionne($nouvelleLargeur, $nouvelleHauteur, $conserveProportions = true)
    {
        $pointSourceCoordonnees = array(0, 0);
        $sourceDimensions = array($this->largeur, $this->hauteur);
        
        if ($conserveProportions)
        {
            if (null === $nouvelleLargeur)
                $nouvelleLargeur = intval($this->largeur / $this->hauteur * $nouvelleHauteur);
            elseif (null === $nouvelleHauteur)
                $nouvelleHauteur = intval($this->hauteur / $this->largeur * $nouvelleLargeur);
        }
        else
        {
            if (($nouvelleHauteur / $nouvelleLargeur) > ($this->hauteur / $this->largeur)) // Rogner la largeur
            {
                $pointSourceCoordonnees[0] = intval(($this->largeur - $this->hauteur) * ($nouvelleLargeur / $nouvelleHauteur) / 2);
                $sourceDimensions[0] = $sourceDimensions[1];
            }
            elseif (($nouvelleHauteur / $nouvelleLargeur) < ($this->hauteur / $this->largeur)) // Rogner la hauteur
            {
                $pointSourceCoordonnees[1] = intval(($this->hauteur - $this->largeur) * ($nouvelleHauteur / $nouvelleLargeur) / 2);
                $sourceDimensions[1] = $sourceDimensions[0];
            }
        }
    
        if ($this->largeur != $nouvelleLargeur || $this->hauteur != $nouvelleHauteur)
        {
            $nouvelleRessourceGd = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
            if (in_array($this->type, array(IMAGETYPE_PNG, IMAGETYPE_GIF)))
            {
                imagealphablending($nouvelleRessourceGd, false);
                imagesavealpha($nouvelleRessourceGd, true);
            }
            imagecopyresampled($nouvelleRessourceGd, $this->resourceGd, 0, 0, $pointSourceCoordonnees[0], $pointSourceCoordonnees[1], $nouvelleLargeur, $nouvelleHauteur, $sourceDimensions[0], $sourceDimensions[1]);
            $this->saveFromResourceGd($nouvelleRessourceGd);
            
            $this->largeur = $nouvelleLargeur;
            $this->hauteur = $nouvelleHauteur;
        }
    }
    
    /**
     * Enregistre la ressource GD.
     * 
     * @param resource $resourceGd Resource GD
     * @throws \Exception Si le format n'est reconnu
     * @return void
     */
    private function saveFromResourceGd($resourceGd)
    {
        $this->resourceGd = $resourceGd;
        
        switch ($this->type)
        {
            case IMAGETYPE_JPEG:
                imagejpeg($resourceGd, $this->getPathname());
                break;
            case IMAGETYPE_PNG:
                imagepng($resourceGd, $this->getPathname());
                break;
            case IMAGETYPE_GIF:
                imagegif($resourceGd, $this->getPathname());
                break;
            default:
                throw new \Exception('Le format de l\'image n\'est pas correct.');
        }
    }
}
