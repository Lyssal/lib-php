<?php
namespace Lyssal;

/**
 * Classe permettant des traitements sur des fichiers.
 * 
 * @author Rémi Leclerc
 */
class Fichier
{
    /**
     * @var \SplFileInfo SplFileInfo du fichier
     */
    private $splFileInfo;
    
    /**
     * Constructeur d'un fichier.
     *
     * @param string $fichierPath Chemin du fichier à traiter
     */
    public function __construct($fichierPath)
    {
        $this->initSplFileInfo($fichierPath);
    }
    
    /**
     * Initialise le SplFileInfo.
     *
     * @return void
     */
    private function initSplFileInfo($fichierPath)
    {
        $this->splFileInfo = new \SplFileInfo($fichierPath);
    }
    
    /**
     * Retourne le chemin du fichier.
     * 
     * @return string Chemin (pathname) du fichier
     */
    public function getChemin()
    {
        return $this->splFileInfo->getRealPath();
    }
    
    /**
     * Retourne le nom (filename) du fichier.
     * 
     * @return string Nom du fichier
     */
    public function getNom()
    {
        return $this->splFileInfo->getFilename();
    }
    
    /**
     * Retourne l'extension du fichier.
     * 
     * @return string Extension
     */
    public function getExtension()
    {
        return $this->splFileInfo->getExtension();
    }
    
    /**
     * Retourne le dossier du fichier.
     * 
     * @return string Dossier
     */
    public function getDossier()
    {
        return $this->splFileInfo->getPath();
    }
    
    /**
     * Déplace le fichier.
     *
     * @param string $nouveauChemin Nouveau chemin du fichier
     * @param boolean $remplaceSiExistant Si FAUX le nom du fichier pourra être modifié pour ne pas à avoir à remplacer un fichier existant
     * @return boolean VRAI si le déplacement a réussi
     */
    public function move($nouveauChemin, $remplaceSiExistant = false)
    {
        if (false === $remplaceSiExistant)
            $nouveauChemin = self::getCheminLibre($nouveauChemin, '-');
    
        $deplacementEstReussi = false;
        
        if (is_uploaded_file($this->getChemin()))
            $deplacementEstReussi = move_uploaded_file($this->getChemin(), $nouveauChemin);
        else $deplacementEstReussi = rename($this->getChemin(), $nouveauChemin);
        
        if ($deplacementEstReussi)
            $this->initSplFileInfo($nouveauChemin);

        return $deplacementEstReussi;
    }
    
    /**
     * Copie le fichier.
     * 
     * @param string $chemin Chemin où sera copié le fichier
     * @param boolean $remplaceSiExistant Si FAUX le nom du fichier pourra être modifié pour ne pas à avoir à remplacer un fichier existant
     * @return \Lyssal\Fichier|NULL Fichier créé ou NIL si la copie a échoué
     */
    public function copy($chemin, $remplaceSiExistant = false)
    {
        if (false === $remplaceSiExistant)
            $chemin = self::getCheminLibre($chemin, '-');
    
        if (copy($this->getChemin(), $chemin))
            return new Fichier($chemin);
        return null;
    }
    
    /**
     * Modifie le nom du fichier en le minifiant. Ne pas donner l'extension.
     *
     * @param string $nouveauNom Nom nom (non minifié) du fichier
     * @param string $separateur Le séparateur remplaçant les caractères spéciaux
     * @param boolean $toutEnMinuscule VRAI ssi le nom doit être en minuscule
     * @param integer|NULL $longueurMaximale Longueur maximale du fichier (extension comprise)
     * @param boolean $remplaceSiExistant Si FAUX le nom du fichier pourra être modifié pour ne pas à avoir à remplacer un fichier existant
     * @return \Lyssal\Fichier Le fichier
     */
    public function setNomMinifie($nouveauNom, $separateur = '-', $toutEnMinuscule = true, $longueurMaximale = null, $remplaceSiExistant = false)
    {
        $chaineFichierNom = new Chaine($nouveauNom);
        $chaineFichierNom->minifie($separateur, $toutEnMinuscule);
        $fichierNom = $chaineFichierNom->getTexte();
        
        $longueurMaximaleSoustrait = strlen($this->getExtension()) + 1;
        // Réduire la longueur si le fichier existe déjà (à cause de l'ajout d'un suffixe)
        if ($remplaceSiExistant)
            $longueurMaximaleSoustrait += strlen(self::getCheminLibre($this->getDossier().'/'.$fichierNom, $separateur)) - strlen($this->getDossier().'/'.$fichierNom);
        
        if (null !== $longueurMaximale)
            $fichierNom = substr($fichierNom, 0, $longueurMaximale - $longueurMaximaleSoustrait);
        $fichierNom .= '.'.$this->getExtension();
        
        $this->move($this->getDossier().'/'.$fichierNom, $remplaceSiExistant);
        
        return $this;
    }
    
    /**
    * Retourne le chemin du fichier s'il est libre, sinon un autre chemin libre
    *
    * @param string $fichier Chemin du fichier
    * @param string $separateur Le séparateur en cas de renommage du fichier
    * @return string Le chemin du fichier libre
    */
    public static function getCheminLibre($fichier, $separateur)
    {
        if (file_exists($fichier))
        {
            $fichierExtension = substr($fichier, strrpos($fichier, '.') + 1);
        
            $fichierMatch = array();
            if (false !== preg_match('/(.*)(\\'.$separateur.'){1}([0-9]+)([\.]){1}([a-zA-Z0-9]){1,5}/', $fichier, $fichierMatch))
            {
                if (count($fichierMatch) > 4)
                    return self::getCheminLibre($fichierMatch[1].$separateur.(intval($fichierMatch[3]) + 1).'.'.$fichierExtension, $separateur);
            }
            // Premier passage, sans le séparateur
            return self::getCheminLibre(substr($fichier, 0, strlen($fichier) - strlen($fichierExtension) - 1).$separateur.'1'.'.'.$fichierExtension, $separateur);
        }
        return $fichier;
    }
}
