<?php
namespace PF\EmailSignup\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Math\Random;

class TokenGenerator extends AbstractHelper
{
    protected $encryptor;
    protected $random;

    public function __construct(EncryptorInterface $encryptor, Random $random)
    {
        $this->encryptor = $encryptor;
        $this->random = $random;
    }

    public function generateToken($email)
    {
        $random = $this->random->getRandomString(16);
        return $this->encryptor->encrypt($email . $random);
    }

    public function validateToken($email, $token)
    {
        try {
            $decrypted = $this->encryptor->decrypt($token);
            return strpos($decrypted, $email) === 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}