<?php

namespace Product\ProductModule\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface ProductRepositoryInterface{
    
    /**
     * @param int $id
     * @return \Product\ProductModule\Api\Data\ProductInterface
     */
    public function ApiGetId($id);
}