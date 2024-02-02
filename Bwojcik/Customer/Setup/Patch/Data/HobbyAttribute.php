<?php

namespace Bwojcik\Customer\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Customer\Model\ResourceModel\Attribute as ResourceModelAttribute;


class HobbyAttribute implements DataPatchInterface
{
    /**
     * @const string ATTRIBUTE_CODE_HOBBY
     */
    public const ATTRIBUTE_CODE_HOBBY = 'customer_hobby';

    private ModuleDataSetupInterface $moduleDataSetup;

    private CustomerSetupFactory $customerSetupFactory;

    private AttributeSetFactory $attributeSetFactory;

    private ResourceModelAttribute $resourceModelAttribute;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param ResourceModelAttribute $resourceModelAttribute
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        ResourceModelAttribute $resourceModelAttribute
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->resourceModelAttribute = $resourceModelAttribute;
    }

    /**
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return $this
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = (int)$customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::ATTRIBUTE_CODE_HOBBY,
            [
                'type' => 'int',
                'label' => 'Hobby',
                'backend' => '',
                'frontend' => '',
                'input' => 'select',
                'source' => 'Bwojcik\Customer\Model\Source\Attribute\Hobby',
                'class' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => false,
                'sort_order' => 121,
                'used_in_product_listing' => false,
                'filterable_in_search' => true,
                'visible' => true,
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE_HOBBY)
            ->addData(
                [
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]
            );

        $this->resourceModelAttribute->save($attribute);

        return $this;
    }

    /**
     * Get aliases
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }
}
