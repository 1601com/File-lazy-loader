<?php

namespace Agentur1601com\FileLazyLoader\EventListener\Loader2;

use Contao\Combiner;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\StringUtil;
use Contao\Template;

class Loader2Style extends AbstractLoader2
{
    private $_combiner = null;

    public function __construct()
    {
        $this->_combiner = new Combiner();
    }

    /**
     * @param PageModel $page
     * @param LayoutModel $layout
     * @param PageRegular $pageRegular
     */
    public function generateTemplateFiles(PageModel $page, LayoutModel $layout, PageRegular $pageRegular): void
    {
        if (!$files = unserialize($layout->fileLazyLoaderStyleFilesLoad)) {
            return;
        }

        foreach ($files as $file) {
            if (!$file || !file_exists(TL_ROOT . '/' . $file['style_files_path']) || !$file['style_files_path']) {
                continue;
            }

            $link = $this->_minify($file['style_files_path']);

            switch ($file["style_param"]) {
                case 'preload':
                    $GLOBALS['TL_HEAD'][] = "<link rel='preload' href='" . $link . "' as='style'>";
                    $GLOBALS['TL_HEAD'][] = $this->_getStyleTag($link);
                    break;
                case 'preload_push':
                    header("Link: <" . $link . ">; rel=preload; as=style", false);
                    $GLOBALS['TL_HEAD'][] = $this->_getStyleTag($link);
                    break;
                case 'delay':
                    $GLOBALS['TL_HEAD'][] = $this->_getScriptStyle($link);
                    break;
                case 'head':
                    $GLOBALS['TL_HEAD'][] = $this->_getStyleTag($link);
                    break;
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
        if (TL_MODE == 'BE') {
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/filelazyloader/js/lazy-style-loader.js|static';
        }

        $files = $this->_loadFilesByFillTree($dataContainer);

        $savedFiles = $savedFiles ? unserialize($savedFiles) : [];

        $wizardFiles = [];

        foreach ($savedFiles as $savedFile) {
            if (!is_numeric($key = array_search($savedFile['style_files_path'], $files))) {
                continue;
            }

            unset($files[$key]);

            $wizardFiles[] = [
                'select' => $savedFile['select'],
                'style_files_path' => $savedFile['style_files_path'],
                'style_param' => $savedFile['style_param'],
                'style_version' => $savedFile['style_version']
            ];
        }

        foreach (array_reverse($files) as $file) {
            $wizardFiles[] = [
                'select' => 0,
                'style_files_path' => $file,
                'style_param' => '',
                'style_version' => ''
            ];
        }

        return serialize($wizardFiles);
    }

    /**
     * @param DataContainer $dataContainer
     * @return array
     * @throws \Exception
     */
    protected function _loadFilesByFillTree(DataContainer $dataContainer): array
    {
        $fileTree = $dataContainer->activeRecord->fileLazyLoaderStylePath;

        if (!$fileTree) {
            return [];
        }

        return array_map(function ($file) {return FilesModel::findByPk($file)->path;}, is_array($fileTree) ? $fileTree : unserialize($fileTree));
    }

    /**
     * @param string $source
     * @return string
     */
    protected function _minify(string $source): string
    {
        if (!file_exists(TL_ROOT . '/' . $source)) {
            return $source;
        }

        $combiner = new Combiner();
        $combiner->add($source);
        return $combiner->getCombinedFile();
    }

    /**
     * @param string $href
     * @return string
     */
    private function _getScriptStyle(string $href): string
    {
        $script = "<script>setTimeout(function() {document.head.innerHTML += '%s';});</script><noscript>%s</noscript>";
        return \Safe\sprintf($script, $this->_getStyleTag($href), $this->_getStyleTag($href));
    }

    /**
     * @param string $href
     * @return string
     */
    private function _getStyleTag(string $href): string
    {
        $options = StringUtil::resolveFlaggedUrl($href);
        return Template::generateStyleTag($href, $options->media, $options->mtime);
    }
}
