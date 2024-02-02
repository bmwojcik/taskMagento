<?php

declare(strict_types=1);

namespace Bwojcik\Customer\Model\Resolver;

use Bwojcik\Customer\Model\Source\Attribute\Hobby;
use Bwojcik\Customer\Setup\Patch\Data\HobbyAttribute;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CustomerAttributes implements ResolverInterface
{
    private CustomerRepositoryInterface $customerRepositoryInterface;

    private Hobby $hobbySource;

    /**
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param Hobby $hobbySource
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface,
        Hobby $hobbySource
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->hobbySource = $hobbySource;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        /** @var CustomerInterface $customer */
        $customer = $value['model'];
        $customerId = (int) $customer->getId();
        $customer = $this->customerRepositoryInterface->getById($customerId);
        $result = null;

        if ($customer->getCustomAttribute(HobbyAttribute::ATTRIBUTE_CODE_HOBBY)) {
            $value = $customer->getCustomAttribute(HobbyAttribute::ATTRIBUTE_CODE_HOBBY)->getValue();

            foreach ($this->hobbySource->getAllOptions() as $option) {
                if ($option['value'] == $value) {
                    $result = $option['label'];
                }
            }
        }

        return $result;
    }
}
