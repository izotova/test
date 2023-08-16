<?php declare(strict_types=1);
/**
 * Craftsman Test. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace CraftsmanTest\RelatedProduct\Api;

/**
 * @api
 */
interface RelatedProductManagerInterface
{
    /**
     * @param int $productId
     * @return bool
     */
    public function hasProductDependency(int $productId): bool;

    /**
     * @param int $productId
     * @return string
     */
    public function getDependencyMessage(int $productId): string;
}
