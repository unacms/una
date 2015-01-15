<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralSearchResult');

class BxBaseModTextSearchResult extends BxBaseModGeneralSearchResult
{
    protected $aUnitViews = array('extended' => 'unit.html', 'gallery' => 'unit_gallery.html');
    protected $sUnitViewDefault = 'gallery';
    protected $sUnitViewParamName = 'unit_view';

    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);

        if (!empty($aParams['unit_view']))
            $this->sUnitViewDefault = $aParams['unit_view'];

        $this->aGetParams = array($this->sUnitViewParamName);
        $this->sUnitTemplate = $this->aUnitViews[$this->sUnitViewDefault];
        if (isset($this->aUnitViews[bx_get($this->sUnitViewParamName)]))
            $this->sUnitTemplate = $this->aUnitViews[bx_get($this->sUnitViewParamName)];

        if ('unit_gallery.html' == $this->sUnitTemplate)
            $this->addContainerClass (array('bx-def-margin-sec-neg', 'bx-base-text-unit-gallery-wrapper'));
    }

    protected function getCurrentOnclick($aAdditionalParams = array(), $bReplacePagesParams = true) 
    {
        // always add UnitView as additional param
        $sUnitView = bx_process_input(bx_get($this->sUnitViewParamName));
        if ($sUnitView && isset($this->aUnitViews[$sUnitView]))
            $aAdditionalParams = array_merge(array($this->sUnitViewParamName => $sUnitView), $aAdditionalParams);

        return parent::getCurrentOnclick($aAdditionalParams, $bReplacePagesParams);
    }

    function _getPseud ()
    {
        return array(
            'id' => 'id',
            'title' => 'title',
            'text' => 'text',
            'added' => 'added',
            'author' => 'author',
            'photo' => 'photo',
        );
    }
}

/** @} */
