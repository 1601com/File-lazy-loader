<?php

namespace Agentur1601com\FileLazyLoader\EventListener\Loader2;

use Agentur1601com\FileLazyLoader\Service\Helper;
use Contao\DataContainer;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\StringUtil;
use Contao\Template;
use MatthiasMullie\Minify;

class Loader2JS extends AbstractLoader2
{
    private $_helper;

    public function __construct(Helper $_helper)
    {
        $this->_helper = $_helper;
    }

    /**
     * @param PageModel $page
     * @param LayoutModel $layout
     * @param PageRegular $pageRegular
     */
    public function generateTemplateFiles(PageModel $page, LayoutModel $layout, PageRegular $pageRegular): void
    {
        if (!isset($layout->fileLazyLoaderJsFiles) || !is_array(($jsFiles = unserialize($layout->fileLazyLoaderJsFiles)))) {
            return;
        }

        foreach ($jsFiles as &$file) {
            $link = $this->_removeTrailingSlash($file["js_files_path"]);

            if (!$file["select"] || !file_exists(TL_ROOT . $link)) {
                continue;
            }

            if ($file['js_minimize']) {
                $link = $this->_minify($file["js_files_path"]);
            }

            switch ($file["js_param"]) {
                case 'preload':
                    array_unshift($GLOBALS['TL_HEAD'], "<link rel='preload' href='" . $link . "' as='script'>");
                    $GLOBALS['TL_HEAD'][] = $this->_getScriptTag($link);
                    break;
                case 'preload_push':
                    header("Link: <" . $link . ">; rel=preload; as=script", false);
                    array_unshift($GLOBALS['TL_HEAD'], $this->_getScriptTag($link));
                    break;
                case 'defer':
                    $GLOBALS['TL_HEAD'][] = "<script src='" . $link . "' defer></script>";
                    break;
                default:
                    $file["js_param"] = empty($file["js_param"]) ? 'static' : $file["js_param"];
                    $GLOBALS['TL_JAVASCRIPT'][] = $link . "|" . $file["js_param"];
            }
        }
    }

    /**
     * @param string|null $savedFiles
     * @param DataContainer $dataContainer
     * @return string
     * @throws \Exception
     */
    public function getMultiColumnWizardFiles(?string $savedFiles, DataContainer $dataContainer): string
    {
        $savedFiles = $savedFiles ? array_reverse(unserialize($savedFiles)) : [];

        $files = $this->_loadFilesByFillTree($dataContainer);

        $returnArray = [];

        foreach ($savedFiles as $savedFile) {

            foreach ($files as $pathLoadedKey => $pathLoadedFile) {
                if (str_replace(TL_ROOT, "", $pathLoadedFile) === $savedFile['js_files_path']) {
                    $arrayValue = $this->_setWizardArray(
                        $savedFile['select'],
                        $savedFile['js_files_path'],
                        $savedFile['js_files_path_min'],
                        $savedFile['js_param'],
                        $savedFile['js_minimize']
                    );

                    if ($savedFile['select']) {
                        array_unshift($returnArray, $arrayValue);
                        unset($files[$pathLoadedKey]);
                    }

                    break;
                }
            }
        }

        foreach ($files as $pathLoadedFile) {
            $returnArray[] = $this->_setWizardArray("", str_replace(TL_ROOT, "", $pathLoadedFile));
        }

        return serialize($returnArray);
    }

    /**
     * @param DataContainer $dataContainer
     * @return array
     * @throws \Exception
     */
    protected function _loadFilesByFillTree(DataContainer $dataContainer): array
    {
        if (TL_MODE == 'BE') $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/filelazyloader/js/scriptLoader.js|static';

        $paths = $this->_helper->getPathsByUUIDs($dataContainer->activeRecord->fileLazyLoaderJsPath);

        if(empty($paths)) {
            return [];
        }

        foreach ($paths as &$path) {
            $path = TL_ROOT . "/" . $path;
        }

        return $paths;
    }

    /**
     * @param string $source
     * @return string
     */
    protected function _minify(string $source): string
    {
        $link = 'assets/js/' . basename($source) . '.min.js';

        if (file_exists(TL_ROOT . '/' . $link)) {
            return $link;
        }

        $Minify = new Minify\JS();
        $Minify->add(TL_ROOT . $source);
        $Minify->minify(TL_ROOT . '/' . $link);

        return $link;
    }

    /**
     * @param string $select
     * @param string $js_files_path
     * @param string $js_files_path_min
     * @param string $js_param
     * @param string $js_minimize
     * @param string $js_files_extFile
     * @return array
     */
    private function _setWizardArray(
        string $select = "",
        string $js_files_path = "",
        string $js_files_path_min = "",
        string $js_param = "",
        string $js_minimize = "",
        string $js_files_extFile = ""
    )
    {
        return [
            "select" => $select,
            "js_files_path" => $js_files_path,
            "js_files_path_min" => $js_files_path_min,
            "js_param" => $js_param,
            "js_minimize" => $js_minimize,
            "js_files_extFile" => $js_files_extFile,
        ];
    }

    /**
     * @param $href
     * @return string
     */
    private function _getScriptTag($href): string {
        $options = StringUtil::resolveFlaggedUrl($href);
        return Template::generateScriptTag($href, $options->async, $options->mtime);
    }
}
