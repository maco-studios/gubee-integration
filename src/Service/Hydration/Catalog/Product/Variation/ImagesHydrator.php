<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\SDK\Model\Catalog\Product\Media\Image;
use Gubee\SDK\Model\Catalog\Product\Variation;
use Magento\Catalog\Model\Product\Gallery\ReadHandler;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

use function pathinfo;
use function sprintf;
use function str_replace;

use const PATHINFO_FILENAME;

class ImagesHydrator extends AbstractHydrator
{
    protected ObjectManagerInterface $objectManager;
    protected ReadHandler $galleryReadHandler;

    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager,
        ReadHandler $galleryReadHandler
    ) {
        parent::__construct($config);
        $this->objectManager      = $objectManager;
        $this->galleryReadHandler = $galleryReadHandler;
    }

    /**
     * Extract the images from the product.
     *
     * @param Variation $value The product variation.
     * @param object|null $object The object to extract the value from.
     * @return array
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getImages();
    }

    /**
     * Hydrate the images of the product.
     *
     * @param Variation $value The product variation.
     * @param array|null $data The data to hydrate the value with.
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $gallery = $this->getGallery($this->product);
        if (empty($gallery)) {
            $value->setImages([]);
        } else {
            $images = $this->createImagesFromGallery($gallery, $this->product);
            $value->setImages($images);
        }
        return $value;
    }

    /**
     * Get the gallery of the product.
     *
     * @param ProductInterface $product The product to get the gallery from.
     * @return array
     */
    protected function getGallery($product)
    {
        return $this->galleryReadHandler->execute($product)
            ->getMediaGalleryImages();
    }

    /**
     * Create the images from the gallery.
     *
     * @param array $gallery The gallery to create the images from.
     * @param ProductInterface $product The product to create the images for.
     * @return array
     */
    protected function createImagesFromGallery($gallery, $product)
    {
        $images = [];
        foreach ($gallery as $image) {
            $url      = $this->getImageUrl($image);
            $imageObj = $this->createImageObject($image, $url, $product);
            $images[] = $imageObj;
        }
        return $images;
    }

    /**
     * Get the image url.
     *
     * @param mixed $image The image to get the url from.
     * @return string
     */
    protected function getImageUrl($image)
    {
        $url = sprintf(
            "%scatalog%s",
            ObjectManager::getInstance()->get(
                StoreManagerInterface::class
            )->getStore()->getBaseUrl(
                UrlInterface::URL_TYPE_MEDIA
            ),
            $image->getFile()
        );
        return str_replace('http://', 'https://', $url);
    }

    /**
     * Create the image object.
     *
     * @param mixed $image The image to create the object from.
     * @param string $url The url of the image.
     * @param ProductInterface $product The product to create the image for.
     * @return Image
     */
    protected function createImageObject($image, $url, $product)
    {
        $imageObj = $this->objectManager
            ->create(
                Image::class
            );
        return $imageObj->setName(
            $image->getLabel() ?: pathinfo(
                $image->getFile(),
                PATHINFO_FILENAME
            )
        )->setUrl(
            $url
        )->setOrder(
            (int) $image->getPosition()
        )->setMain(
            $image->getFile() === $product->getThumbnail()
        );
    }
}
