<?php declare(strict_types=1);
/**
 * Craftsman Test. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace CraftsmanTest\RelatedProduct\Api\Data;

/**
 * @api
 */
interface DependencyInformationInterface
{
    const PRODUCT_ATTRIBUTE_HAS_DEPENDENCY = 'has_dependency';
    const PRODUCT_ATTRIBUTE_DEPENDENCY_ON_SKU = 'dependency_on_sku';
}
