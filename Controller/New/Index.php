<?php
namespace PF\EmailSignup\Controller\New;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use PF\EmailSignup\Helper\EmailHelper;

class Index extends Action
{
    protected $customerRepository;
    protected $customerFactory;
    protected $emailHelper;

    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        CustomerInterfaceFactory $customerFactory,
        EmailHelper $emailHelper
    ) {
        parent::__construct($context);
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->emailHelper = $emailHelper;
    }

    public function execute()
    {
        $email = $this->getRequest()->getParam('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->messageManager->addErrorMessage(__('Invalid email.'));
            return $this->_redirect('/');
        }

        try {
            $customer = $this->customerRepository->get($email);
            $this->messageManager->addErrorMessage(__('This email address is already registered.'));
            return $this->_redirect('/');
        } catch (LocalizedException $e) {
            $customer = $this->customerFactory->create();
            $customer->setEmail($email);
            $customer->setGroupId(5); 

            $this->customerRepository->save($customer);
            $this->emailHelper->sendRegistrationEmail($customer);
            $this->messageManager->addSuccessMessage(__('Please check your email to complete your account.'));
        }

        return $this->_redirect('/');
    }
}
