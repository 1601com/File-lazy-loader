<?php

use Agentur1601com\FileLazyLoader\EventListener\Loader2\Loader2JS;
use Agentur1601com\FileLazyLoader\EventListener\Loader2\Loader2Style;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('fileLazyLoader_files_js_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('fileLazyLoaderJsPath', 'fileLazyLoader_files_js_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fileLazyLoaderJsFiles', 'fileLazyLoader_files_js_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout');

PaletteManipulator::create()
    ->addLegend('fileLazyLoader_files_style_legend', 'fileLazyLoader_files_js_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('fileLazyLoaderStylePath', 'fileLazyLoader_files_style_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fileLazyLoaderStyleFilesLoad', 'fileLazyLoader_files_style_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout');

$GLOBALS['TL_DCA']['tl_layout']['fields']['fileLazyLoaderJsPath'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['fileLazyLoaderJsPath'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'eval' => [
        'multiple' => true,
        'fieldType' => 'checkbox',
        'mandatory' => false,
        'files' => true,
        'filesOnly' => true,
        'tl_class' => 'w50 m12 fileLazyLoaderJsPath',
        'extensions' => 'js,JS',
    ],
    'sql' => "BLOB NULL",
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['fileLazyLoaderJsFiles'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['fileLazyLoaderJsFiles'],
    'exclude' => true,
    'inputType' => 'multiColumnWizard',
    'eval' => [
        'multiple' => true,
        'tl_class' => 'clr m12',
        'dragAndDrop' => true,
        'columnFields' => [
            'select' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['js_select'],
                'exclude' => true,
                'inputType' => 'checkbox',
                'eval' => [
                    'style' => 'width:20px'
                ],
            ],
            'js_files_path' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['js_files_path'],
                'exclude' => true,
                'inputType' => 'text',
                'eval' => [
                    "readonly" => true
                ],
            ],
            'js_files_path_min' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['js_files_path_min'],
                'exclude' => true,
                'inputType' => 'text',
                'eval' => [
                    'hideBody' => true,
                    "hideHead" => true,
                    "style" => "display:none!important; margin:0!important; padding:0!important; border:0!important; opacity:0;"
                ]
            ],
            'js_param' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['js_param'],
                'exclude' => true,
                'inputType' => 'select',
                'options' => [
                    'async' => 'Async',
                    'defer' => 'Defer',
                    'preload' => 'Preload',
                    'preload_push' => 'Preload push'
                ],
                'eval' => [
                    'style' => 'width:150px',
                    'includeBlankOption' => true,
                    'chosen' => true
                ],
            ],
            'js_minimize' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['js_minimize'],
                'inputType' => 'checkbox',
                'eval' => [
                    'style' => 'width:20px',
                ],
            ],
            'js_files_extFile' => [
                'label' => [''],
                'inputType' => 'checkbox',
                'eval' => [
                    'hideBody' => true,
                    "hideHead" => true,
                    "style" => "display:none!important; margin:0!important; padding:0!important; border:0!important; opacity:0;"
                ]
            ],
        ],
        'buttons' => [
            'new' => false,
            'delete' => false,
            'copy' => false,
        ],
    ],
    'load_callback' => [
        [Loader2JS::class, 'getMultiColumnWizardFiles']
    ],
    'sql' => "blob NULL"
];

/*$GLOBALS['TL_DCA']['tl_layout']['fields']['fileLazyLoaderModifyExtJs'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['fileLazyLoaderModifyExtJs'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'w50 m12 fileLazyLoaderJsPath',
    ],
    'sql' => "char(1) NOT NULL default ''",
];*/

$GLOBALS['TL_DCA']['tl_layout']['fields']['fileLazyLoaderStylePath'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['fileLazyLoaderStylePath'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'eval' => [
        'multiple' => true,
        'fieldType' => 'checkbox',
        'mandatory' => false,
        'files' => true,
        'filesOnly' => true,
        'extensions' => 'css,scss,less',
        'tl_class' => 'fileLazyLoaderStylePath'
    ],
    'sql' => "BLOB NULL",
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['fileLazyLoaderStyleFilesLoad'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['fileLazyLoaderStyleFilesLoad'],
    'inputType' => 'multiColumnWizard',
    'eval' => [
        'multiple' => true,
        'tl_class' => 'clr m12',
        'dragAndDrop' => true,
        'columnFields' => [
            'select' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['style_select'],
                'exclude' => true,
                'inputType' => 'checkbox',
                'eval' => [
                    'style' => 'width:20px'
                ],
            ],
            'style_files_path' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['style_files_path'],
                'inputType' => 'text',
                'eval' => [
                    "readonly" => true
                ],
            ],
            'style_param' => [
                'label' => &$GLOBALS['TL_LANG']['tl_layout']['style_param'],
                'inputType' => 'select',
                'options' => [
                    'head' => 'Head',
                    'delay' => 'Script Delay',
                    'preload' => 'Preload',
                    'preload_push' => 'Preload push'
                ],
                'eval' => [
                    'style' => 'width:150px',
                    'chosen' => true
                ],
            ],
            'style_version' => [
                'label' => [],
                'exclude' => true,
                'inputType' => 'text',
                'eval' => [
                    'hideBody' => true,
                    "hideHead" => true,
                    "style" => "display:none!important; margin:0!important; padding:0!important; border:0!important; opacity:0;"
                ]
            ],
        ],
        'buttons' => [
            'copy' => false,
            'new' => false,
            'delete' => false,
        ],
    ],
    'load_callback' => [
        [Loader2Style::class, 'getMultiColumnWizardFiles']
    ],
    'sql' => "blob NULL"
];
