<?php

declare(strict_types=1);

namespace Bwojcik\Customer\Controller\Edit;

use Bwojcik\Customer\Setup\Patch\Data\HobbyAttribute;
use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Psr\Log\LoggerInterface;

class Update implements HttpPostActionInterface
{
    private RequestInterface $request;
    private ResultFactory $resultFactory;
    private CustomerRepositoryInterface $customerRepository;
    private Session $customerSession;
    private LoggerInterface $logger;
    private MessageManagerInterface $messageManager;

    /**
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param Session $customerSession
     * @param LoggerInterface $logger
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        LoggerInterface $logger,
        MessageManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->logger = $logger;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('attributes/edit');
        $postData = $this->request->getParams();

        if (!$this->request->isPost()|| !isset($postData['customer-attribute-hobby'])) {
            $this->messageManager->addErrorMessage(__('Invalid request data !'));

            return $resultRedirect;
        }

        try {
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
            $customer->setCustomAttribute(HobbyAttribute::ATTRIBUTE_CODE_HOBBY, $postData['customer-attribute-hobby']);
            $this->customerRepository->save($customer);
            $this->messageManager->addSuccessMessage(__('Successfully changed value!'));

        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            $this->messageManager->addErrorMessage(__('Could not save value!'));
        }

        return $resultRedirect;
    }

}
