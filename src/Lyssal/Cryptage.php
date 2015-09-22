<?php
namespace Lyssal;

/**
 * Fonctions de cryptage.
 * 
 * @author Rémi Leclerc
 */
class Cryptage
{
    /**
     * Encode en 64 bits pour une URL.
     *
     * @param string $texte Texte à encoder
     */
    public static function base64UrlEncode($texte)
    {
        return rtrim(strtr(base64_encode($texte), '+/', '-_'), '=');
    }
    
    /**
     * Decode en 64 bits pour une URL.
     *
     * @param string $texte Texte à encoder
     */
    public static function base64UrlDecode($texte)
    {
        return base64_decode(str_pad(strtr($texte, '-_', '+/'), strlen($texte) % 4, '=', STR_PAD_RIGHT));
    }
}
