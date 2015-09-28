<?php
namespace Lyssal;

/**
 * Classe permettant des traitements sur le système.
 * 
 * @author Rémi Leclerc
 */
class Systeme
{
    /**
     * Détermine la mémoire limite (en octet par défaut) qu'un script est autoriser à allouer.
     * 
     * @param string $memoryLimit Nombre d'octets de la mémoire limit, ou de méga-octets si suivi d'un "M" (ex. : "128M")
     * @return void
     */
    public static function setMemoryLimit($memoryLimit)
    {
        ini_set('memory_limit', $memoryLimit);
    }
    
    /**
     * Fixe le temps d'exécution d'un script à illimité.
     *
     * @return void
     */
    public static function setTimeLimitIllimite()
    {
        set_time_limit(0);
    }
    
    /**
     * Redirige l'internaute.
     * 
     * @param string $url URL cible
     */
    public static function redirect($url)
    {
        header('Location: '.$url);
        exit();
    }
}
