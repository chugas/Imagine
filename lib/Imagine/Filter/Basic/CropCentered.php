<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Filter\Basic;

use Imagine\Image\ImageInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\PointInterface;
use Imagine\Filter\FilterInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * A crop filter
 */
class CropCentered implements FilterInterface {

  /**
   * @var PointInterface
   */
  private $start;

  /**
   * @var BoxInterface
   */
  private $size;

  /**
   * Constructs a Crop filter with given x, y, coordinates and crop width and
   * height values
   *
   * @param PointInterface $start
   * @param BoxInterface   $size
   */
  public function __construct(BoxInterface $size) {
    $this->size = $size;
  }

  public function getStart(ImageInterface $image, $w, $h) {
    $cx = $image->getSize()->getWidth() / 2;
    $cy = $image->getSize()->getHeight() / 2;
    $x = $cx - $w / 2;
    $y = $cy - $h / 2;

    if ($x < 0)
      $x = 0;
    if ($y < 0)
      $y = 0;
    $this->start = new Point($x, $y);
  }

  /**
   * {@inheritdoc}
   */  
  public function apply(ImageInterface $image) {
    $w = $image->getSize()->getWidth();
    $h = $image->getSize()->getHeight();

    $width = $this->size->getWidth();
    $height = $this->size->getHeight();   
    
    if ($w > $h) {
      $cheight = $height * $w / $width;
      $cwidth = $w;

      if ($cheight > $h) {
        $cheight = $h;
        $cwidth = $h * $width / $height;
      }
    } else {
      $cwidth = $width * $h / $height;
      $cheight = $h;

      if ($cwidth > $w) {
        $cwidth = $w;
        $cheight = $w * $height / $width;
      }
    }
    $this->getStart($image, $cwidth, $cheight);

    $new_image = $image->crop($this->start, new Box($cwidth, $cheight));

    return $new_image->resize($this->size);
  }  
  
}
