<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Market Market
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxMarketFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_market';
        parent::__construct($aInfo, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

	    if (isset($this->aInputs[$CNF['FIELD_FILE']])) {
            $this->aInputs[$CNF['FIELD_FILE']]['storage_object'] = $CNF['OBJECT_STORAGE_FILES'];
            $this->aInputs[$CNF['FIELD_FILE']]['uploaders'] = $CNF['OBJECT_UPLOADERS'];
            $this->aInputs[$CNF['FIELD_FILE']]['images_transcoder'] = '';
            $this->aInputs[$CNF['FIELD_FILE']]['storage_private'] = 1;
            $this->aInputs[$CNF['FIELD_FILE']]['multiple'] = true;
            $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = 0;
            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = '';
        }
    }

	function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (isset($this->aInputs[$CNF['FIELD_FILE']])) {

            $aContentInfo = false;
            if ($aValues && !empty($aValues['id'])) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById ($aValues['id']);
                $this->aInputs[$CNF['FIELD_FILE']]['content_id'] = $aValues['id'];
            }

            $aVars = array (
                'name' => $this->aInputs[$CNF['FIELD_FILE']]['name'],
                'content_id' => $this->aInputs[$CNF['FIELD_FILE']]['content_id'],
                'editor_id' => $CNF['FIELD_TEXT_ID'],
                'thumb_id' => isset($aContentInfo[$CNF['FIELD_PACKAGE']]) ? $aContentInfo[$CNF['FIELD_PACKAGE']] : 0,
                'bx_if:set_thumb' => array (
                    'condition' => true,
                    'content' => array(
            			'name_thumb' => $CNF['FIELD_PACKAGE'],
            		),
                ),
            );
            $this->aInputs[$CNF['FIELD_FILE']]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template_file.html', $aVars);
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

	public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aPackage = bx_process_input(bx_get($CNF['FIELD_PACKAGE']), BX_DATA_INT);
		$aValsToAdd[$CNF['FIELD_PACKAGE']] = 0;
		if(!empty($aPackage) && is_array($aPackage) && ($iFilePackage = array_pop($aPackage)))
			$aValsToAdd[$CNF['FIELD_PACKAGE']] = $iFilePackage;

        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        if(!empty($iContentId))
            $this->_processFiles($CNF['FIELD_FILE'], $iContentId, true, $CNF['OBJECT_STORAGE_FILES']);

        return $iContentId;
    }

	function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

		$aPackage = bx_process_input(bx_get($CNF['FIELD_PACKAGE']), BX_DATA_INT);
		$aValsToAdd[$CNF['FIELD_PACKAGE']] = 0;
		if(!empty($aPackage) && is_array($aPackage) && ($iFilePackage = array_pop($aPackage)))
			$aValsToAdd[$CNF['FIELD_PACKAGE']] = $iFilePackage;

        $iResult = parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->_processFiles($CNF['FIELD_FILE'], $iContentId, false, $CNF['OBJECT_STORAGE_FILES']);

        return $iResult;
    }
}

/** @} */
