<?php

declare(strict_types=1);

namespace Bwojcik\Customer\Controller\Edit;

use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements AccountInterface, HttpGetActionInterface
{
    private PageFactory $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Page
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
