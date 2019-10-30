<?php

namespace Product\ProductModule\Helper;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data;

class ProductHelper
{

    /**
     * @var Data
     */
    private $priceHelper;
    /**
     * @param Data $priceHelper
     */
    public function __construct(
        Data $priceHelper
    ){
        $this->priceHelper = $priceHelper;
    }
    public function formatPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }
    public function getProductImageArray($product)
    {
        $images = $product->getMediaGalleryImages();
        $imageArray = array();
        foreach($images as $image){
            $imageArray[] = $image->getUrl();
        }
        return $imageArray;
    }
}