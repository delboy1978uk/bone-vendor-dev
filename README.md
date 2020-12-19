# vendordev
If you create packagist libraries that get installed in your vendor folder, sometimes it's easier to work on those
files directly inside a project. However, sometimes you might alter files that were installed by Composer and not Git, so
you would typically clone the project again into a temp folder, move the `.git` into the composer installed one,
delete the temp folder, then see the changes in the original folder. This tool does this for every project 
in a given vendor directory, then lists projects that have had files changed.
## installation
Use Composer
```
composer require delboy1978uk/bone-vendor-dev
```
## usage
If using Bone Framework, simply add to the `config/packages.php` to add the command. If not you can add the 
`VendorDevCommand` to any existing Symfony console app, or you can call `bin/vendor-tool`
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
If using Bone Framework, you run the `bone` command, you can now call the following
```
bone vendor:check delboy1978uk
```
Again if you are not using Bone Framework you can call `bin/vendor-tool` instead or add the command to your existing 
Symfony console app.

In the above example, the command will go through each of the installed libraries in `vendor/delboy1978uk`, and if a folder does
not contain a `.git` directory, then it will clone the project into a temp folder before moving the `.git` into
the installed version.

Once it has done this, it checks to see if files have changed by doing a `git status`. Once it has checked erach
project in your vendor folder, it will list any projects that have changed. You should note that this tool only 
checks against the master branch (for now, feel free to contribute!).
#### example output
```
$ bone v:c delboy1978uk
Entering vendor/delboy1978uk.
Found 21 projects.

Checking delboy1978uk/barnacle
Checking delboy1978uk/bone
Checking delboy1978uk/bone-console
Checking delboy1978uk/bone-controller
Checking delboy1978uk/bone-db
Checking delboy1978uk/bone-firewall
Checking delboy1978uk/bone-http
Checking delboy1978uk/bone-i18n
Checking delboy1978uk/bone-log
Checking delboy1978uk/bone-router
Checking delboy1978uk/bone-server
Checking delboy1978uk/bone-vendor-dev
Checking delboy1978uk/bone-view
Checking delboy1978uk/booty
Checking delboy1978uk/cdn
Checking delboy1978uk/css
Checking delboy1978uk/form
Checking delboy1978uk/icon
Checking delboy1978uk/image
Checking delboy1978uk/router
Checking delboy1978uk/session

The following packages have been changed:

delboy1978uk/router

```