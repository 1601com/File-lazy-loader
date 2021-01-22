<?php

use Agentur1601com\FileLazyLoader\EventListener\Loader\JsLoader;
use Agentur1601com\FileLazyLoader\EventListener\Loader\StyleLoader;

// agentur1601core backend menu entry
$GLOBALS['BE_MOD']['agentur1601com']['file-lazy-loader'] = [
    'tables' => ['tl_theme'],
];

// language output for backend entry
$GLOBALS['TL_LANG']['MOD']['file-lazy-loader'] = ['FileLazyLoader', ''];

$GLOBALS['TL_HOOKS']['generatePage'][] = [StyleLoader::class, 'generatePageCallback'];
$GLOBALS['TL_HOOKS']['generatePage'][] = [JsLoader::class, 'loadJs'];
