<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Math\Geometry\Geometry2d;

use Lyssal\Exception\LyssalException;

/**
 * A 2D rectangle.
 */
class Rectangle
{
    /**
     * @var \Lyssal\Math\Geometry\Geometry2d\Point Upper-left location
     */
    protected $location;

    /**
     * @var \Lyssal\Math\Geometry\Geometry2d\Dimension Dimension
     */
    protected $dimension;


    /**
     * Rectangle constructor.
     *
     * @param mixed ...$rectangle 4 parameters : The x and y locations, width and height - 2 parameters : 2D Location and 2D Dimension objects
     * @throws \Lyssal\Exception\LyssalException If parameters are wrong
     */
    public function __construct(...$rectangle)
    {
        if (4 === count($rectangle)) {
            $this->location = new Point((int) $rectangle[0], (int) $rectangle[1]);
            $this->dimension = new Dimension((int) $rectangle[2], (int) $rectangle[3]);
        } elseif (2 === count($rectangle)) {
            if (!($rectangle[0] instanceof Point)) {
                throw new LyssalException('The first parameter of the Rectangle constructor must be a Point object.');
            }
            if (!($rectangle[1] instanceof Dimension)) {
                throw new LyssalException('The second parameter of the Rectangle constructor must be a Dimension object.');
            }
            $this->location = $rectangle[0];
            $this->dimension = $rectangle[1];
        } else {
            throw new LyssalException('The Rectangle constructor must have 2 oe 4 parameters.');
        }
    }


    /**
     * Get location.
     *
     * @return \Lyssal\Math\Geometry\Geometry2d\Point Point
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set location.
     *
     * @param \Lyssal\Math\Geometry\Geometry2d\Point $location Location
     * @return \Lyssal\Math\Geometry\Geometry2d\Rectangle This
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get dimension.
     *
     * @return \Lyssal\Math\Geometry\Geometry2d\Dimension Dimension
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set dimension.
     *
     * @param \Lyssal\Math\Geometry\Geometry2d\Dimension $dimension Dimension
     * @return \Lyssal\Math\Geometry\Geometry2d\Rectangle Rectangle
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get x.
     *
     * @return int x
     */
    public function getX()
    {
        return $this->location->getX();
    }

    /**
     * Set x.
     *
     * @param int $x x
     * @return \Lyssal\Math\Geometry\Geometry2d\Rectangle This
     */
    public function setX($x)
    {
        $this->location->setX($x);

        return $this;
    }

    /**
     * Get y.
     *
     * @return int y
     */
    public function getY()
    {
        return $this->location->getY();
    }

    /**
     * Set y.
     *
     * @param int $y y
     * @return \Lyssal\Math\Geometry\Geometry2d\Rectangle This
     */
    public function setY($y)
    {
        $this->location->setY($y);

        return $this;
    }

    /**
     * Get width.
     *
     * @return int width
     */
    public function getWidth()
    {
        return $this->dimension->getWidth();
    }

    /**
     * Set width.
     *
     * @param int $width width
     * @return \Lyssal\Math\Geometry\Geometry2d\Rectangle This
     */
    public function setWidth($width)
    {
        $this->dimension->setWidth($width);

        return $this;
    }

    /**
     * Get height.
     *
     * @return int height
     */
    public function getHeight()
    {
        return $this->dimension->getHeight();
    }

    /**
     * Set height.
     *
     * @param int $height height
     * @return \Lyssal\Math\Geometry\Geometry2d\Rectangle This
     */
    public function setHeight($height)
    {
        $this->dimension->setHeight($height);

        return $this;
    }
}
