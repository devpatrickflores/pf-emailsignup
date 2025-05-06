<?php
namespace PF\EmailSignup\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use PF\EmailSignup\Helper\TokenGenerator;

class Create extends Action
{
    protected $pageFactory;
    protected $tokenHelper;

    public function __construct(Context $context, PageFactory $pageFactory, TokenGenerator $tokenHelper)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->tokenHelper = $tokenHelper;
    }

    public function execute()
    {
        $email = $this->getRequest()->getParam('email');
        $token = $this->getRequest()->getParam('token');

        if (!$this->tokenHelper->validateToken($email, $token)) {
            $this->messageManager->addErrorMessage(__('Invalid or expired token.'));
            return $this->_redirect('/');
        }

        return $this->pageFactory->create();
    }
}
