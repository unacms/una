<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxGlsrUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_glossary_terms', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_glossary_terms` ADD `labels` text NOT NULL AFTER `text`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
