<?php
namespace Lyssal;

use Lyssal\Texte;

/**
 * Classe permettant de manipuler des chaînes de caractères.
 *
 * @author Rémi Leclerc
 */
class Chaine extends Texte
{
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

    /**
     * Retourne si la chaîne contient au moins une lettre.
     *
     * @return boolean VRAI ssi la chaîne contient une lettre
     */
    public function hasLettre()
    {
        return preg_match('/[a-zA-Z]/', $this->texte);
    }
    
    /**
     * Retourne si la chaîne contient au moins un chiffre.
     *
     * @return boolean VRAI ssi la chaîne contient une chiffre
     */
    public function hasChiffre()
    {
        return preg_match('/\d/', $this->texte);
    }
    
    /**
     * Remplace les <br> en \n.
     *
     * @return \Lyssal\Chaine La chaîne de caractères
     */
    public function br2nl()
    {
        $this->texte = preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $this->texte);
    
        return $this;
    }
}
