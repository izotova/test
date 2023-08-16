<?php declare(strict_types=1);
/**
 * Craftsman Test. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace CraftsmanTest\RelatedProduct\Setup\Patch\Data;

use function implode;
use function sprintf;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\ValidateException;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use CraftsmanTest\RelatedProduct\Api\Data\DependencyInformationInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CraftsmanTest\RelatedProduct\Setup\Patch\Data\AddDependencyAttribute
 */
class AddDependencyAttribute implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private EavSetupFactory          $eavSetupFactory,
        private LoggerInterface          $logger
    ) {}

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply(): self
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        try {
            $eavSetup->addAttribute(
                Product::ENTITY,
                DependencyInformationInterface::PRODUCT_ATTRIBUTE_HAS_DEPENDENCY,
                [
                    'group' => 'General',
                    'type' => 'int',
                    'label' => 'Product has dependency',
                    'input' => 'boolean',
                    'source' => Boolean::class,
                    'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => 0,
                    'sort_order' => 200,
                    'visible_on_front' => true,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => true,
                    'unique' => false,
                    'apply_to' => implode(',', [Type::TYPE_SIMPLE, Type::TYPE_VIRTUAL, Type::TYPE_BUNDLE, Configurable::TYPE_CODE])
                ]
            );
        } catch (LocalizedException|ValidateException $e) {
            $this->logger->error(
                sprintf(
                    'Something gone wrong with attribute %s', DependencyInformationInterface::PRODUCT_ATTRIBUTE_HAS_DEPENDENCY
                ),
                [
                    'exception' => $e->getMessage()
                ]);
        }

        try {
            $eavSetup->addAttribute(
                Product::ENTITY,
                DependencyInformationInterface::PRODUCT_ATTRIBUTE_DEPENDENCY_ON_SKU,
                [
                    'group' => 'General',
                    'type' => 'varchar',
                    'label' => 'Product has dependency on SKU',
                    'input' => 'text',
                    'class' => '',
                    'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '0',
                    'sort_order' => 205,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => true,
                    'unique' => false,
                    'apply_to' => implode(',', [Type::TYPE_SIMPLE, Type::TYPE_VIRTUAL, Type::TYPE_BUNDLE, Configurable::TYPE_CODE])
                ]
            );
        } catch (LocalizedException|ValidateException $e) {
            $this->logger->error(
                sprintf(
                    'Something gone wrong with attribute %s', DependencyInformationInterface::PRODUCT_ATTRIBUTE_DEPENDENCY_ON_SKU
                ),
                [
                    'exception' => $e->getMessage()
                ]);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
