<?php

use Agentur1601com\FileLazyLoader\EventListener\Loader\JsLoader;
use Agentur1601com\FileLazyLoader\EventListener\Loader\StyleLoader;

// used-report backend menu entry
$GLOBALS['BE_MOD']['Agentur1601com']['file-lazy-loader'] = [
    'tables' => ['tl_file_lazy_loader'],
];

// language output for backend entry
$GLOBALS['TL_LANG']['MOD']['file-lazy-loader'] = ['File lazy loader', ''];

$GLOBALS['TL_HOOKS']['generatePage'][] = [StyleLoader::class, 'generatePageCallback'];
$GLOBALS['TL_HOOKS']['generatePage'][] = [JsLoader::class, 'loadJs'];

#$GLOBALS['BE_MOD']['system']['files']['javascript'] = 'bundles/filelazyloader/js/scriptLoader.js';
