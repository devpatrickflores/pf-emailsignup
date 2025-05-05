<?php
/**
 * PF_EmailSignup
 *
 * @category Newsletter/Loyalty
 * @package  PF_EmailSignup
 * @author   Patrick Flores <hello@patrickianflores.com>
 */

 use Magento\Framework\Component\ComponentRegistrar;
 
 ComponentRegistrar::register(
     ComponentRegistrar::MODULE,
     'PF_EmailSignup',
     __DIR__
 );
 