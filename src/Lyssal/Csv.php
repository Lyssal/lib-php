<?php
namespace Lyssal;

/**
 * Classe permettant de lire et créer des fichiers CSV.
 * 
 * @author Rémi Leclerc
 */
class Csv
{
    /**
     * @var string Chemin ou nom du fichier CSV
     */
    private $fichierCsv;

    /**
     * @var char Séparateur de champ
     */
    private $separateurChamp;

    /**
     * @var char Caractère d'encadrement de texte
     */
    private $encadrementTexte;
    
    /**
     * @var array<string> En-têtes du CSV
     */
    private $enTetes = array();

    /**
     * @var array<array<string>> En-têtes du CSV
     */
    private $lignes = array();
    
    /**
     * @var string Jeu de caractères de la source
     */
    private $charsetSource = null;
    
    /**
     * @var string Jeu de caractères de la cible
     */
    private $charsetTarget = null;


    /**
     * Constructeur de Csv.
     *
     * @param string $chemin Chemin ou nom (s'il s'agit juste d'un export) du fichier CSV
     * @param char $separateurChamp Le séparateur de champ
     * @param char|NULL $encadrementTexte Le caractère d'encadrement de texte
     */
    public function __construct($fichierCsv, $separateurChamp = ';', $encadrementTexte = '"')
    {
        $this->fichierCsv = $fichierCsv;
        $this->separateurChamp = $separateurChamp;
        $this->encadrementTexte = $encadrementTexte;
    }


    /**
     * Spécifie le séparateur de colonne.
     * 
     * @param char $separateurChamp Le séparateur de champ
     * @return \Lyssal\Csv Instance
     */
    public function setSeparateur($separateur)
    {
        $this->separateur = $separateur;
        
        return $this;
    }
    
    /**
     * Spécifie le caractère d'encadrement de texte.
     * 
     * @param char|NULL $encadrementTexte Le caractère d'encadrement de texte
     * @return \Lyssal\Csv Instance
     */
    public function setEncadrementTexte($encadrementTexte)
    {
        $this->encadrementTexte = $encadrementTexte;
        
        return $this;
    }
    
    /**
     * Spécifie les en-têtes du CSV.
     * 
     * @param array<string> $enTetes En-têtes du CSV
     * @return \Lyssal\Csv Instance
     */
    public function setEnTetes(array $enTetes)
    {
        $this->enTetes = $this->processLigne($enTetes);
        
        return $this;
    }
    
    /**
     * Retourne les en-têtes du CSV.
     * 
     * @return array<string> En-têtes
     */
    public function getEnTetes()
    {
        return $this->enTetes;
    }
    
    /**
     * Retourne les lignes du CSV.
     * 
     * @return array<string> Lignes du CSV
     */
    public function getLignes()
    {
        return $this->lignes;
    }
    
    /**
     * Ajoute une ligne au CSV.
     * 
     * @param array<string> $ligne Ligne supplémentaire du CSV
     */
    public function addLigne(array $ligne)
    {
        $this->lignes[] = $this->processLigne($ligne);
    }
    
    /**
     * Change le jeu de caractères.
     * 
     * @param string $charsetSource Encodage source
     * @param string $charsetTarget Encodage cible
     * @return void
     */
    public function changeCharset($charsetSource = null, $charsetTarget = null)
    {
        if ((null === $charsetSource && null !== $charsetTarget) || (null !== $charsetSource && null === $charsetTarget))
            throw new \Exception('Les encodages source et cible doivent être définis tous les deux.');

        $this->charsetSource = $charsetSource;
        $this->charsetTarget = $charsetTarget;
    }
    
    /**
     * Effectue des opérations sur la ligne pour la rendre conforme.
     * 
     * @param array<string> Ligne du CSV
     * @return array<string> Ligne conforme
     */
    protected function processLigne(array $ligne)
    {
        return $this->iconvLigne($ligne);
    }
    
    /**
     * Convertit la ligne du CSV avec le bon charset si défini.
     * 
     * @param array<string> Ligne du CSV
     * @return array<string> Ligne avec le bon encodage
     */
    protected function iconvLigne(array $ligne)
    {
        if (null !== $this->charsetSource && null !== $this->charsetTarget)
        {
            foreach ($ligne as $i => $celluleValeur)
            {
                $ligne[$i] = iconv($this->charsetSource, $this->charsetTarget, $celluleValeur);
            }
        }
        
        return $ligne;
    }
    
    
    /**
     * Importe un fichier CSV.
     * 
     * @param boolean $aHeader VRAI ssi le CSV comporte une première ligne d'en-têtes
     * @throws \Exception Exception si le fichier n'est pas lisible
     * @return void
     */
    public function importe($aHeader = false)
    {
        if (($csv = fopen($this->fichierCsv, 'r')) !== false)
        {
            if ($aHeader)
            {
                if ($ligne = fgetcsv($csv, null, $this->separateurChamp, $this->encadrementTexte))
                    $this->setEnTetes($ligne);
            }

            while (($ligne = fgetcsv($csv, null, $this->separateurChamp, $this->encadrementTexte)) !== false)
                $this->addLigne($ligne);
            
            fclose($csv);
        }
        else throw new \Exception('Le fichier CSV "'.$this->fichierCsv.'" n\'est pas lisible.');
    }
    
    /**
     * Exporte le fichier vers l'utilisateur.
     * 
     * @return void
     */
    public function exporte()
    {
        header('Content-Type: application/csv-tab-delimited-table');
        header('Content-disposition: filename='.$this->fichierCsv);
        $out = fopen('php://output', 'w');
        
        if (count($this->enTetes) > 0)
        {
            if (null === $this->encadrementTexte)
                fputs($out, implode($this->processLigne($this->enTetes), $this->separateurChamp)."\n");
            else fputcsv($out, $this->processLigne($this->enTetes), $this->separateurChamp, $this->encadrementTexte);
        }
        
        foreach ($this->lignes as $ligne)
        {
            if (null === $this->encadrementTexte)
                fputs($out, implode($this->processLigne($ligne), $this->separateurChamp)."\n");
            else fputcsv($out, $this->processLigne($ligne), $this->separateurChamp, $this->encadrementTexte);
        }
        
        fclose($out);
    }
}
