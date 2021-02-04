<?php

use \Agentur1601com\FileLazyLoader\EventListener\Loader2\Loader2Style;
use \Agentur1601com\FileLazyLoader\EventListener\Loader2\Loader2JS;

// agentur1601core backend menu entry
$GLOBALS['BE_MOD']['agentur1601com']['file-lazy-loader'] = [
    'tables' => ['tl_theme'],
];

// language output for backend entry
$GLOBALS['TL_LANG']['MOD']['file-lazy-loader'] = ['FileLazyLoader', ''];

$GLOBALS['TL_HOOKS']['generatePage'][] = [Loader2Style::class, 'generateTemplateFiles'];

$GLOBALS['TL_HOOKS']['generatePage'][] = [Loader2JS::class, 'generateTemplateFiles'];
