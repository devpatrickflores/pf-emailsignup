<?php
namespace PF\EmailSignup\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;
use Magento\Framework\Encryption\EncryptorInterface;

class Submit extends Action
{
    protected $session, $customerFactory, $customerResource, $encryptor;

    public function __construct(
        Context $context,
        Session $session,
        CustomerFactory $customerFactory,
        CustomerResource $customerResource,
        EncryptorInterface $encryptor
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->customerFactory = $customerFactory;
        $this->customerResource = $customerResource;
        $this->encryptor = $encryptor;
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $email = $params['email'] ?? null;

        $customer = $this->customerFactory->create();
        $this->customerResource->loadByEmail($customer, $email);

        if (!$customer->getId()) {
            $this->messageManager->addErrorMessage(__('Customer not found.'));
            return $this->_redirect('/');
        }

        $customer->setFirstname($params['firstname']);
        $customer->setLastname($params['lastname']);
        $customer->setDob($params['dob']);
        $customer->setPasswordHash($this->encryptor->getHash($params['password'], true));
        $this->customerResource->save($customer);

        $this->session->setCustomerDataAsLoggedIn($customer);
        return $this->_redirect('customer/account');
    }
}
