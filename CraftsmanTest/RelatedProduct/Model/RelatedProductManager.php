<?php declare(strict_types=1);
/**
 * Craftsman Test. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace CraftsmanTest\RelatedProduct\Model;

use function sprintf;
use CraftsmanTest\RelatedProduct\Api\RelatedProductManagerInterface;
use CraftsmanTest\RelatedProduct\Api\Data\DependencyInformationInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Psr\Log\LoggerInterface;

class RelatedProductManager implements RelatedProductManagerInterface
{
    /**
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param CustomerCart $cart
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private StoreManagerInterface $storeManager,
        private CustomerCart $cart,
        private LoggerInterface $logger
    ) {}

    /**
     * @param int $productId
     * @return bool
     */
    public function hasProductDependency(int $productId): bool
    {
        $product = $this->getProduct($productId);

        return (bool)$product->getCustomAttribute(DependencyInformationInterface::PRODUCT_ATTRIBUTE_HAS_DEPENDENCY)->getValue();
    }

    /**
     * @param ProductInterface $product
     * @return string
     * @throws \Exception
     */
    private function getRelatedSku(ProductInterface $product): string
    {
        $relatedSku = $product->getCustomAttribute(DependencyInformationInterface::PRODUCT_ATTRIBUTE_DEPENDENCY_ON_SKU)->getValue();
        if (!$relatedSku) {
            throw new \Exception('Product Sku is not provided');
        }

        return $relatedSku;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     * @throws \Exception
     */
    private function hasCartRelatedProduct(ProductInterface $product): bool
    {
        try {
            $relatedSku = $this->getRelatedSku($product);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        
        foreach ($this->cart->getQuote()->getItems() as $item) {
            if ($relatedSku === $item->getSku()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $productId
     * @return string
     */
    public function getDependencyMessage(int $productId): string
    {
        $message = '';
        $product = $this->getProduct($productId);
        $relatedSku = $product->getCustomAttribute(DependencyInformationInterface::PRODUCT_ATTRIBUTE_DEPENDENCY_ON_SKU)->getValue();
        try {
            if (!$this->hasCartRelatedProduct($product)) {
                $message = __(
                    sprintf('Product with SKU %s has to be added in cart', $relatedSku)
                );
            }
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return $message;
    }

    /**
     * @param int $productId
     * @return ProductInterface|null
     */
    private function getProduct(int $productId): ProductInterface|null
    {
        try {
            return $this->productRepository->getById($productId, false, $this->storeManager->getStore()->getId());
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
