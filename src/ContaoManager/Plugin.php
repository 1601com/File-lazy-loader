<?php

declare(strict_types=1);

namespace Agentur1601com\FileLazyLoader\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Agentur1601com\FileLazyLoader\FileLazyLoaderBundle;
use Agentur1601com\CoreBundle\Agentur1601comCoreBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(FileLazyLoaderBundle::class)
                ->setLoadAfter([Agentur1601comCoreBundle::class]),
        ];
    }
}
