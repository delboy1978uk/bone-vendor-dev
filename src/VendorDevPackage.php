<?php

declare(strict_types=1);

namespace Bone\VendorDev;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Console\CommandRegistrationInterface;
use Bone\VendorDev\Command\VendorDevCommand;

class VendorDevPackage implements RegistrationInterface, CommandRegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
    }

    /**
     * @param Container $container
     * @return VendorDevCommand[]
     */
    public function registerConsoleCommands(Container $container): array
    {
        return [new VendorDevCommand('vendor:check')];
    }
}
