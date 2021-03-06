<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Math\Geometry\Geometry2d;

/**
 * A 2D point.
 */
class Point
{
    /**
     * @var int The x coordinate
     */
    protected $x;

    /**
     * @var int The y coordinate
     */
    protected $y;


    /**
     * Constructor.
     *
     * @param int $x Coordinate x
     * @param int $y Coordinate y
     */
    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }


    /**
     * Get x.
     *
     * @return int x
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set x.
     *
     * @param int $x x
     * @return \Lyssal\Math\Geometry\Geometry2d\Point This
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get y.
     *
     * @return int y
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set y.
     *
     * @param int $y y
     * @return \Lyssal\Math\Geometry\Geometry2d\Point This
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }
}
