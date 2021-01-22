<?php

namespace Agentur1601com\FileLazyLoader\EventListener\Loader;

use Contao\DataContainer;

abstract class AbstractLoader
{

    abstract protected function _wizardLoadFileList($filesActive, DataContainer $dc): string;

    abstract protected function _generatePageCallback($page, $layout, $pageRegular): bool;

    final public function wizardLoadFileList($filesActive, DataContainer $dc): string
    {
        return $this->_wizardLoadFileList($filesActive, $dc);
    }

    final public function generatePageCallback($page, $layout, $pageRegular)
    {
        if (!$this->_generatePageCallback($page, $layout, $pageRegular)) {
            trigger_error('Failed to execute callback for generatePage', E_USER_WARNING);
        }
    }
}
