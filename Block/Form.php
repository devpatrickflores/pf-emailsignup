<?php
namespace PF\EmailSignup\Block;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    protected $_template = 'PF_EmailSignup::form.phtml';
    
    public function getEmail()
    {
        return $this->getData('email');
    }
}
