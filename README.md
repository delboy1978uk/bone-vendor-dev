# vendordev
VendorDev package for Bone Mvc Framework
## installation
Use Composer
```
composer require delboy1978uk/bone-vendor-dev
```
## usage
Simply add to the `config/packages.php`
```php
<?php

// use statements here
use Bone\VendorDev\VendorDevPackage;

return [
    'packages' => [
        // packages here...,
        VendorDevPackage::class,
    ],
    // ...
];
```