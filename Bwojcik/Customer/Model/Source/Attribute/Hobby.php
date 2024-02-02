<?php

declare(strict_types=1);

namespace Bwojcik\Customer\Model\Source\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Hobby extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        return [
            [
                'value' => 1,
                'label' => __('Yoga')
            ],
            [
                'value' => 2,
                'label' => __('Travelling')
            ],

            [
                'value' => 3,
                'label' => __('Hiking')
            ]
        ];
    }
}
