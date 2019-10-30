<?php

namespace Product\ProductModule\Model;

use Product\ProductModule\Api\ConfigurableProductRepositoryInterface;
use Product\ProductModule\Api\Data\ProductInterfaceFactory;
use Product\ProductModule\Api\ProductRepositoryInterface;
use Product\ProductModule\Helper\ProductHelper;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var ProductInterfaceFactory
     */
    private $productInterfaceFactory;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productInterfaceFactory,
        ProductHelper $productHelper
    ){
        $this->productInterfaceFactory = $productInterfaceFactory;
        $this->productHelper = $productHelper;
        $this->productRepository = $productRepository;
    }
    public function ApiGetId($id)
    {
        $productInterface = $this->productInterfaceFactory->create();
        try{
            /** @var \Magento\Catalog\Api\Data\ProductRepositoryInterface $product */
            $product = $this->productRepository->getById($id);
            $productInterface->setId($product->getId());
            $productInterface->setSku($product->getSku());
            $productInterface->setName($product->getName());
            $productInterface->setDescription($product->getDescription() ? $product->getDescription() : "");
            $productInterface->setPrice($this->productHelper->formatPrice($product->getPrice()));
            $productInterface->setImage($this->productHelper->getProductImageArray($product));
            return $productInterface;
        }
        catch(NoSuchEntityException $e){
            throw NoSuchEntityException::singleField("id", $id);
        }
    }
}