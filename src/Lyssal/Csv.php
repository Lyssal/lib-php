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
    private $header = array();
    /**
     * @var array<array<string>> En-têtes du CSV
     */
    private $lignes = array();

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
     * @param array<string> $header En-têtes du CSV
     * @return \Lyssal\Csv Instance
     */
    public function setHeader(array $header)
    {
        $this->header = $header;
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
        $this->lignes[] = $ligne;
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
                if ($ligne = fgetcsv($csv, 1100, $this->separateurChamp, $this->encadrementTexte))
                    $this->enTetes = $ligne;
            }
            
            while (($ligne = fgetcsv($csv, 1100, $this->separateurChamp, $this->encadrementTexte)) !== false)
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
                fputs($out, implode($this->enTetes, $this->separateurChamp)."\n");
            else fputcsv($out, $this->enTetes, $this->separateurChamp, $this->encadrementTexte);
        }
        
        foreach ($this->lignes as $ligne)
        {
            if (null === $this->encadrementTexte)
                fputs($out, implode($ligne, $this->separateurChamp)."\n");
            else fputcsv($out, $ligne, $this->separateurChamp, $this->encadrementTexte);
        }
        
        fclose($out);
    }
}
