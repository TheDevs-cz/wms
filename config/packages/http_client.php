<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $httpClientConfig = $framework->httpClient();

    $httpClientConfig->scopedClient('omnicado.client')
        ->baseUri('https://api.services.omnicado.com/')
        ->header('User-Agent', 'Artaris WMS');
};
