<?php

namespace Agentur1601com\FileLazyLoader\EventListener\Loader;

use MatthiasMullie\Minify\CSS;

class StyleLoader extends AbstractLoader
{

    private static $_styleParamToTemplateMap = [
        'head' => '',
        'footer' => '',
        'preload' => '',
        'preload_push' => '',
        'delay' => '',
    ];

    public function wizardLoadFileList($filesActive, \DataContainer $dc)
    {
        if (TL_MODE == 'BE') {
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/fileLazyLoader/lazy-style-loader.js|static';
        }

        if(!$filesActive) {
            return '';
        }

        //todo modify external js

        $filesAvailable = $this->_getFilesAvailable($dc->activeRecord->FileLazyLoaderStyleFiles);

        $result = [];
        foreach (unserialize($filesActive) as $fileActiveSingle) {
            if (!isset($filesAvailable[$fileActiveSingle['style_files_path']])) {
                continue;
            }
            $result[] = [
                'style_files_path' => $fileActiveSingle['style_files_path'],
                'style_param' => $fileActiveSingle['style_param'],
            ];
            unset($filesAvailable[$fileActiveSingle['style_files_path']]);
        }
        //reverse so order is as desired
        foreach (array_reverse($filesAvailable) as $fileAvailablePath => $dummy) {
            $result[] = [
                'style_files_path' => $fileAvailablePath,
                'style_param' => '',
            ];
        }
        return serialize($result);
    }

    public function generatePageCallback($page, $layout, $pageRegular)
    {
        if (!$styleFiles = unserialize($layout->fileLazyLoaderStyleFilesLoad)) {
            return;
        }

        foreach ($styleFiles as $styleFileSingle) {
            if (!file_exists($styleFileSingle['style_file_path'])) {
                continue;
            }
            $minimiser = new CSS();
            $minimiser->addFile($styleFileSingle['style_file_path']);
            $minimiser->execute('/web/assets/css/');

        }
    }

    private function _getFilesAvailable($fileTree)
    {
        if(!$fileTree) {
            return [];
        };

        $result = [];
        foreach (unserialize($fileTree) as $filePath) {
            $result[\FilesModel::findByPk($filePath)->path] = true;
        }
        return $result;
    }
}
