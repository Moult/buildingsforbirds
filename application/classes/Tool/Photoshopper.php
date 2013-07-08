<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Tool_Photoshopper implements Cavis\Core\Tool\Photoshopper
{
    private $source;
    private $destination;

    /**
     * Sets the source image to manipulate, and the destination to save the result
     *
     * Example:
     * $photoshopper->setup('/tmp/myfile.png', '/home/user/profile.png');
     *
     * @param string $source      The path to the source image file
     * @param string $destination The path of the destination image file. If
     *                            blank, the source file will be overwritten.
     *
     * @return void
     */
    public function setup($source, $destination = NULL)
    {
        $this->source = $source;
        if ($destination === NULL)
        {
            $this->destination = $source;
        }
        else
        {
            $this->destination = $destination;
        }
    }

    /**
     * Returns image width in pixels
     *
     * @return int
     */
    public function get_width()
    {
        list($width, $height, $type, $attr) = getimagesize($this->source);
        return $width;
    }

    /**
     * Resizes an image to a width in pixels, maintaining aspect ratio
     *
     * Example:
     * $photoshopper->resize_to_width(40);
     *
     * @param int $width Width in pixels to resize to
     *
     * @return void
     */
    public function resize_to_width($width)
    {
        $image = Image::factory($this->source);
        $image->resize($width, NULL);
        $image->save($this->destination, 100);
    }

    /**
     * Blurs the image using the Gaussian algorithm
     *
     * Example:
     * $photoshopper->gaussian_blur(2.5);
     *
     * @param float $sigma The sigma magnitude of the Gaussian blur
     *
     * @return void
     */
    public function gaussian_blur($sigma)
    {
        shell_exec('convert '.escapeshellarg($this->source).' -filter Gaussian -resize 25% -define filter:sigma='.escapeshellarg($sigma).' -resize 400% '.escapeshellarg($this->destination));
    }
}
