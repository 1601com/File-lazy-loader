<?php

namespace Agentur1601com\FileLazyLoader\EventListener;

use Agentur1601com\FileLazyLoader\Service\Helper;
use Agentur1601com\FileLazyLoader\EventListener\Loader\JsLoader;
use Agentur1601com\FileLazyLoader\EventListener\Loader\StyleLoader;
use Contao\DataContainer;

class SeoSeeFiles
{
    private $_jsLoader;
    private $_styleLoader;
    private $_helper;

    public function __construct(JsLoader $_jsLoader, StyleLoader $_styleLoader, Helper $_helper)
    {
        $this->_jsLoader = $_jsLoader;
        $this->_styleLoader = $_styleLoader;
        $this->_helper = $_helper;
    }

    public function loadJsFiles($savedFiles, DataContainer $dc)
    {
        if (TL_MODE == 'BE') $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/filelazyloader/js/scriptLoader.js|static';

        $paths = $this->_helper->getPathsByUUIDs($dc->activeRecord->fileLazyLoaderJsPath);

        if(empty($paths))
        {
            return serialize([]);
        }

        $pathLoadedFiles = [];

        foreach ($paths as $path)
        {
            $pathLoadedFiles = array_merge($pathLoadedFiles, $this->_helper->searchDir(TL_ROOT . "/" . $this->_helper::safePath($path)));
        }

        $removeExternalFiles = $dc->activeRecord->fileLazyLoaderModifyExtJs === '1' ? false : true;
        
        return $this->_jsLoader->returnMultiColumnWizardArray($pathLoadedFiles, unserialize($savedFiles), $removeExternalFiles);
    }

    /**
     * @param $savedFiles
     * @param DataContainer $dc
     * @return string
     * @throws \Exception
     */
    public function loadStyleFiles($savedFiles, DataContainer $dc)
    {
        if (TL_MODE == 'BE') $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/seosee/styleLoader.js|static';

        $StyleLoader = new StyleLoader();
        #$Helper = new Helper();

        $files = $this->_helper->getPathsByUUIDs($dc->activeRecord->seoseeStyleFiles);

        if (empty($files))
        {
            return serialize([]);
        }

        if (!$savedFiles)
        {
            $savedFiles = '';
        }

        return $StyleLoader->returnMultiColumnWizardArray($files, $savedFiles);
    }
}
