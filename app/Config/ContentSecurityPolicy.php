<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ContentSecurityPolicy extends BaseConfig
{
    public bool $reportOnly = false;
    public ?string $reportURI = null;
    public bool $upgradeInsecureRequests = false;

    public $defaultSrc;

    public $scriptSrc = [
        'self',
        "'unsafe-inline'",
        "'unsafe-eval'",
        'https://cdn.jsdelivr.net',
        'https://cdnjs.cloudflare.com',
        'https://code.jquery.com',
        'https://ajax.googleapis.com',
    ];

    public $styleSrc = [
        'self',
        "'unsafe-inline'",
        'https://fonts.googleapis.com',
        'https://fonts.gstatic.com',
        'https://code.ionicframework.com',
        'https://cdnjs.cloudflare.com',
    ];

    public $imageSrc = ['self', 'data:', '*'];

    public $baseURI;

    public $childSrc = 'self';

    public $connectSrc = 'self';

    public $fontSrc = [
        'self',
        'data:',
        'https://fonts.googleapis.com',
        'https://fonts.gstatic.com',
        'https://code.ionicframework.com',
        'https://cdnjs.cloudflare.com',
    ];

    public $formAction = 'self';

    public $frameAncestors;

    public $frameSrc;

    public $mediaSrc;

    public $objectSrc = 'self';

    public $manifestSrc;

    public $pluginTypes;

    public $sandbox;

    public string $styleNonceTag = '{csp-style-nonce}';

    public string $scriptNonceTag = '{csp-script-nonce}';

    public bool $autoNonce = true;
}