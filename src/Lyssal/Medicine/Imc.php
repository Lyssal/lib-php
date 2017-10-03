<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Medicine;

/**
 * Calcul de l'IMC.
 */
class Imc
{
    /**
     * @var float The mass in kg
     */
    protected $mass;

    /**
     * @var float The body height in m
     */
    protected $height;


    /**
     * Constructor.
     *
     * @param float $mass   The mass in kg
     * @param float $height The body height in m
     */
    public function __construct($mass, $height)
    {
        $this->mass = $mass;
        $this->height = $height;
    }


    /**
     * Set the mass.
     *
     * @param float $mass The mass in kg
     * @return \Lyssal\Medicine\Imc This
     */
    public function setMass($mass)
    {
        $this->mass = $mass;
        return $this;
    }

    /**
     * Get the mass in kg.
     *
     * @return float The mass
     */
    public function getMass()
    {
        return $this->mass;
    }

    /**
     * Set the height.
     *
     * @param float $height The height in m
     * @return \Lyssal\Medicine\Imc This
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get the height in kg.
     *
     * @return float The height
     */
    public function getHeight()
    {
        return $this->height;
    }


    /**
     * Get the IMC in kg/m².
     *
     * @return float The IMC
     */
    public function getImc()
    {
        return $this->mass / ($this->height ** 2);
    }
}
