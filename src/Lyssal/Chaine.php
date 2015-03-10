<?php
namespace Lyssal;

/**
 * Classe permettant de manipuler des chaînes de caractères.
 * 
 * @author Rémi Leclerc
 */
class Chaine
{
    /**
     * @var string Texte manipulé
     */
    private $texte;

    /**
     * Constructeur de Chaine.
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
     * Supprime les accents du texte.
     * 
     * @return string \Lyssal\Chaine La chaîne sans accents
     */
    public function supprimeAccents()
    {
        $this->texte = str_replace
        (
            array('À','Â','à','á','â','ã','ä','å','Ô','ò','ó','ô','õ','ö','ō','ø','È','É','Ê','è','é','ê','ë','Ç','ç','Î','ì','í','î','ï','ù','ú','û','ü','ū','ÿ','ñ'),
            array('A','A','a','a','a','a','a','a','O','o','o','o','o','o','o','o','E','E','E','e','e','e','e','C','c','I','i','i','i','i','u','u','u','u','u','y','n'),
            $this->texte
        );

        return $this;
    }
    
    /**
     * Simplifie une chaîne de caractères (utilisé pour les noms de fichiers ou les URL par exemple).
     * 
     * @param string $separateur Le séparateur remplaçant certains caractères spéciaux comme les espaces
     * @param boolean $toutEnMinuscule VRAI ssi le résultat doit être en minuscule
     * @return \Lyssal\Chaine La chaîne minifiée
     */
    public function minifie($separateur = '-', $toutEnMinuscule = true)
    {
        $this->supprimeAccents();
        
        if ($toutEnMinuscule)
            $this->texte = strtolower($this->texte);
        
        $this->texte = trim
        (
            str_replace
            (
                array(' ','_','-','.','\'','’','&','~','#','`','\\','/','^','@','°','+','*','$','','£','¤','%','§','<','>','…',''),
                $separateur,
                str_replace
                (
                    array(',',';','?','!',':','"','{','}','(',')','[',']','«','»','='),
                    '',
                    str_replace
                    (
                        array('²','œ','Œ',''),
                        array('2','oe','Oe','Oe'),
                        $this->texte
                    )
                )
            )
        );
        
        // Supprime les séparateurs doubles ainsi qu'en début et fin de texte
        if ('' !== $separateur)
            $this->texte = preg_replace('/(\\'.$separateur.')+/', $separateur, trim($this->texte, $separateur));
        
        return $this;
    }
}
