<?php

declare(strict_types=1);

namespace Bwojcik\Customer\Model\CustomerData;

use Bwojcik\Customer\Model\Source\Attribute\Hobby;
use Bwojcik\Customer\Setup\Patch\Data\HobbyAttribute;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session;

class Attributes implements SectionSourceInterface
{
    private Session $customerSession;

    private Hobby $hobbySource;

    /**
     * @param Session $customerSession
     * @param Hobby $hobbySource
     */
    public function __construct(
        Session $customerSession,
        Hobby $hobbySource
    )
    {
        $this->customerSession = $customerSession;
        $this->hobbySource = $hobbySource;
    }

    /**
     * @return string[]
     */
    public function getSectionData(): array
    {
        $hobby = '';
        $customer = $this->customerSession->getCustomer();

        if ($customer->getId()) {
            if ($attribute = $customer->getCustomAttribute(HobbyAttribute::ATTRIBUTE_CODE_HOBBY)) {
                $hobby = $attribute->getValue() ?: '';
            }
        }


        return [
            'hobby' =>
            [
                'value' => $hobby,
                'options' => $this->hobbySource->getAllOptions()
            ]
        ];
    }
}
