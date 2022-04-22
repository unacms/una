<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Wiki Form
 */
class BxBaseFormWiki extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        if (isset($this->aInputs['language']))
            $this->aInputs['language']['values'] = BxDolLanguages::getInstance()->getLanguages(false, true);
        if (isset($this->aInputs['content'])) {
            $this->aInputs['content']['attrs']['id'] = 'wiki_content_';
            $this->aInputs['content']['code'] = true;
        }

    	if(isset($this->aInputs['files'])) {
            $this->aInputs['files']['storage_object'] = 'sys_wiki_files';
            $this->aInputs['files']['images_transcoder'] = 'sys_wiki_images_preview';
            $this->aInputs['files']['uploaders'] = !empty($this->aInputs['files']['value']) ? unserialize($this->aInputs['files']['value']) : array('sys_html5');
            $this->aInputs['files']['storage_private'] = 0;
            $this->aInputs['files']['multiple'] = true;
            $this->aInputs['files']['content_id'] = 0;
            $this->aInputs['files']['ghost_template'] = '';
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        parent::initChecker ($aValues, $aSpecificValues);

        if (isset($this->aInputs['files'])) {
            if (!empty($this->aInputs['block_id']) && !empty($this->aInputs['block_id']['value'])) {
                $this->aInputs['files']['content_id'] = $this->aInputs['block_id']['value'];
                $sWikiEditorId = 'wiki_content_' . $this->aInputs['files']['content_id'];
                $this->aInputs['files']['ghost_template'] = $this->oTemplate->parseHtmlByName('wiki_uploader_file.html', array (
                    'name' => $this->aInputs['files']['name'],
                    'content_id' => (int)$this->aInputs['files']['content_id'],
                    'editor_id' => $sWikiEditorId,
                ));

                if (isset($this->aInputs['content']))
                    $this->aInputs['content']['attrs']['id'] = $sWikiEditorId;
            }
        }
    }

    function genLabel(&$aInput)
    {
        if (isset($aInput['label']) && $aInput['label'] && ('language' == $aInput['name'] || 'content_main' == $aInput['name'])) {
            $sInputID = $this->getInputId($aInput);
            return '<label for="' . $sInputID . '">' . $aInput['label'] . '</label>';
        }
        return parent::genLabel($aInput);
    }
}

/** @} */
