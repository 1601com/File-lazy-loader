<?php

declare(strict_types=1);

namespace agentur1601com\StyleLazyLoader\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use agentur1601com\StyleLazyLoader\StyleLazyLoaderBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(StyleLazyLoaderBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}