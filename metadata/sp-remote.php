<?php
$metadata['http(s)://{laravel_url}/saml2/eipdev/metadata'] = array(
'AssertionConsumerService' => 'http(s)://{laravel_url}/saml2/eipdev/acs',
'SingleLogoutService' => 'http(s)://{laravel_url}/saml2/eipdev/sls',
//the following two affect what the $Saml2user->getUserId() will return
'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
'simplesaml.nameidattribute' => 'uid'
);
