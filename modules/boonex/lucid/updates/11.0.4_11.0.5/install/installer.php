<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxLucidUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function update($aParams)
    {
        $aResult = parent::update($aParams);
        if(!$aResult['result'])
            return $aResult;

        $oCacheUtilities = BxDolCacheUtilities::getInstance();
        $oCacheUtilities->clear('css');
        $oCacheUtilities->clear('template');

        return $aResult;
    }
}
