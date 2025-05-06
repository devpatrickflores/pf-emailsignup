<?php
namespace PF\EmailSignup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use PF\EmailSignup\Helper\TokenGenerator;

class EmailHelper extends AbstractHelper
{
    protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;
    protected $tokenGenerator;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        TokenGenerator $tokenGenerator
    ) {
        parent::__construct($context);
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function sendRegistrationEmail($customer)
    {
        $this->inlineTranslation->suspend();

        $store = $this->storeManager->getStore();
        $token = $this->tokenGenerator->generateToken($customer->getEmail());
        $registrationUrl = $store->getBaseUrl() . 'pf-newsletter/account/create?email=' . urlencode($customer->getEmail()) . '&token=' . urlencode($token);
        $emailSubject = __('Please Complete Your Registration');

        // Create the transport object
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('pf_newsletter_email')
            ->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $store->getId()
            ])
            ->setTemplateVars([
                'registration_url' => $registrationUrl
            ])
            ->setFrom('general')  
            ->addTo($customer->getEmail())
            ->getTransport();

        $message = $transport->getMessage();
        $message->setSubject($emailSubject);  
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
