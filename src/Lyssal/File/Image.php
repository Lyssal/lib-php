<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\File;

use Lyssal\Exception\IoException;
use Lyssal\Math\Geometry\Geometry2d\Dimension;
use Lyssal\Math\Geometry\Geometry2d\Point;
use Lyssal\Math\Geometry\Geometry2d\Rectangle;

/**
 * Class to manipulate images.
 */
class Image extends File
{
    /**
     * @var resource GD ressource
     */
    protected $gdResource;

    /**
     * @var \Lyssal\Math\Geometry\Geometry2d\Dimension The image dimension
     */
    protected $dimension;

    /**
     * @var int Image type (cf. IMAGETYPE_XXX)
     */
    protected $type;


    /**
     * Constructor.
     *
     * @param string $imagePathname Image pathname
     */
    public function __construct($imagePathname)
    {
        parent::__construct($imagePathname);

        $this->initProperties();
        if ($this->formatIsManaged()) {
            $this->initGdResource();
        }
    }


    /**
     * Get dimension.
     *
     * @return \Lyssal\Math\Geometry\Geometry2d\Dimension The image dimension
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Get the image width.
     *
     * @return int Width
     */
    public function getWidth()
    {
        return $this->dimension->getWidth();
    }

    /**
     * Get the image height.
     *
     * @return int Height
     */
    public function getHeight()
    {
        return $this->dimension->getHeight();
    }


    /**
     * Init the Image properties.
     */
    protected function initProperties()
    {
        $imageSize = getimagesize($this->getPathname());

        if (false !== $imageSize) {
            $this->dimension = new Dimension($imageSize[0], $imageSize[1]);
            $this->type = $imageSize[2];
        }
    }

    /**
     * Return if the image format is managed.
     *
     * @return bool If managed
     */
    public function formatIsManaged()
    {
        return (null !== $this->type);
    }

    /**
     * Init the GD resource.
     *
     * @throws \Lyssal\Exception\IoException If format is incorrect
     */
    protected function initGdResource()
    {
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $this->gdResource = imagecreatefromjpeg($this->getPathname());
                break;
            case IMAGETYPE_PNG:
                $this->gdResource = imagecreatefrompng($this->getPathname());
                break;
            case IMAGETYPE_GIF:
                $this->gdResource = imagecreatefromgif($this->getPathname());
                break;
            default:
                throw new IoException('The image format is not managed.');
        }
    }


    /**
     * Resize the image.
     *
     * @param int|null $width  The new image width in pixels ; if NULL, the proportions will be conserved
     * @param int|null $height The new image height in pixels ; if NULL, the proportions will be conserved
     */
    public function resize($width = null, $height = null)
    {
        $originalRectangle = new Rectangle(new Point(0, 0), $this->dimension);

        if (null === $width || null === $height) {
            if (null !== $width) {
                $height = (int) ($this->getHeight() / $this->getWidth() * $width);
            } elseif (null !== $height) {
                $width = (int) ($this->getWidth() / $this->getHeight() * $height);
            } else {
                return;
            }
        } elseif (($height / $width) > ($this->getHeight() / $this->getWidth())) { // Trim width
            $originalRectangle->setX((int) (($this->getWidth() - $this->getHeight()) * ($width / $height) / 2));
            $originalRectangle->setWidth($originalRectangle->getHeight());
        } elseif (($height / $width) < ($this->getHeight() / $this->getWidth())) { // Trim height
            $originalRectangle->setY((int) (($this->getHeight() - $this->getWidth()) * ($height / $width) / 2));
            $originalRectangle->setHeight($originalRectangle->getWidth());
        }
        $resizedRectangle = new Rectangle(0, 0, $width, $height);

        if ($this->getWidth() !== $resizedRectangle->getWidth() || $this->getHeight() !== $resizedRectangle->getHeight()) {
            $resizedGdResource = imagecreatetruecolor($resizedRectangle->getWidth(), $resizedRectangle->getHeight());
            if (in_array($this->type, array(IMAGETYPE_PNG, IMAGETYPE_GIF), false)) {
                imagealphablending($resizedGdResource, false);
                imagesavealpha($resizedGdResource, true);
            }
            imagecopyresampled($resizedGdResource, $this->gdResource, $resizedRectangle->getX(), $resizedRectangle->getY(), $originalRectangle->getX(), $originalRectangle->getY(), $resizedRectangle->getWidth(), $resizedRectangle->getHeight(), $originalRectangle->getWidth(), $originalRectangle->getHeight());
            $this->saveFromGdResource($resizedGdResource);
            $this->dimension = $resizedRectangle->getDimension();
        }
    }

    /**
     * Save the GD resource.
     *
     * @param resource $gdResource The GD resource
     * @throws \Exception Si le format n'est reconnu
     */
    protected function saveFromGdResource($gdResource)
    {
        switch ($this->type) {
            case IMAGETYPE_JPEG:
                imagejpeg($gdResource, $this->getPathname());
                break;
            case IMAGETYPE_PNG:
                imagepng($gdResource, $this->getPathname());
                break;
            case IMAGETYPE_GIF:
                imagegif($gdResource, $this->getPathname());
                break;
            default:
                throw new IoException('The image format is not managed.');
        }

        $this->gdResource = $gdResource;
    }
}
