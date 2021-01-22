<?php

namespace Agentur1601com\FileLazyLoader\EventListener\Loader;

use MatthiasMullie\Minify\CSS;
use Contao\DataContainer;
use Contao\FilesModel;
use Agentur1601com\FileLazyLoader\Service\Helper;

class StyleLoader extends AbstractLoader
{

    private static $_styleParamToTemplateMap = [
        'head' => '',
        'footer' => '',
        'preload' => '',
        'preload_push' => '',
        'delay' => '',
    ];

    protected function _wizardLoadFileList($filesActive, DataContainer $dc): string
    {
        if (TL_MODE == 'BE') {
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/filelazyloader/js/lazy-style-loader.js|static';
        }

        if(!$filesActive) {
            return '';
        }

        $filesAvailable = $this->_getFilesAvailable($dc->activeRecord->fileLazyLoaderStylePath);

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

        foreach (array_reverse($filesAvailable) as $fileAvailablePath => $bool) {
            $result[] = [
                'style_files_path' => $fileAvailablePath,
                'style_param' => '',
            ];
        }

        return serialize($result);
    }

    protected function _generatePageCallback($page, $layout, $pageRegular): bool
    {
        if (!$styleFiles = unserialize($layout->fileLazyLoaderStyleFilesLoad)) {
            //nothing to do
            return true;
        }

        foreach ($styleFiles as $styleFileSingle) {
            if (!file_exists($styleFileSingle['style_file_path'])) {
                continue;
            }
            $minimiser = new CSS();
            $minimiser->addFile($styleFileSingle['style_file_path']);
            $minimiser->execute('/web/assets/css/');
        }

        return true;
    }

    private function _getFilesAvailable($fileTree)
    {
        if(!$fileTree) {
            return [];
        };

        $paths = [];
        foreach (unserialize($fileTree) as $filePath) {
            $paths[] = FilesModel::findByPk($filePath)->path;
        }

        $helper = new Helper();
        $result = [];
        foreach ($paths as $path)
        {
            // Array-Aufbau zur Überprüfung bereits vorhandener Einträge
            foreach(str_replace(TL_ROOT, '', $helper->searchDir(TL_ROOT . "/" . $helper::safePath($path), ['css', 'less', 'scss'])) as $filePath) {
                $result[$filePath] = true;
            }
        }

        return $result;
    }
}
