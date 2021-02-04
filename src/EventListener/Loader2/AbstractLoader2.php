<?php

namespace Agentur1601com\FileLazyLoader\EventListener\Loader2;

use Contao\DataContainer;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;

abstract class AbstractLoader2 {

    /**
     * @param PageModel $page
     * @param LayoutModel $layout
     * @param PageRegular $pageRegular
     */
    abstract public function generateTemplateFiles(PageModel $page, LayoutModel $layout, PageRegular $pageRegular): void;

    /**
     * @param $savedFiles
     * @param DataContainer $dataContainer
     * @return string
     */
    abstract public function getMultiColumnWizardFiles(string $savedFiles, DataContainer $dataContainer): string;

    /**
     * @param DataContainer $dataContainer
     * @return array
     */
    abstract protected function _loadFilesByFillTree(DataContainer $dataContainer): array;

    /**
     * @param string $source
     * @return string
     */
    abstract protected function _minify(string $source): string;

    /**
     * @param string $path
     * @return string
     */
    protected function _removeTrailingSlash(string $path): string
    {
        return '/' . ltrim(rtrim($path, '/'), '/');
    }
}
