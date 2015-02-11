<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @page objects
 * @section uploader Uploader
 * @ref BxDolUploader
 */

/**
 * Uploaders are disigned to work as form field in forms. @see BxDolForm.
 *
 *
 * To add file to any form, use the following form field array:
 * @code
 * 'attachment' => array(
 *     'type' => 'files', // this is new form type, which enable upholders automatically
 *     'storage_object' => 'sample', // the storage object, where uploaded files are going to be saved
 *     'images_transcoder' => 'sample2', // images transcoder object to use for images preview
 *     'uploaders' => array ('sys_simple', 'sys_html5'), // the set of uploaders to use to upload files
 *     'upload_buttons_titles' => array('sys_simple' => 'Upload one by one', 'sys_html5' => 'Upload several files in bulk'); // change default button titles, array with button names, or string to assign to all bnuttons
 *     'multiple' => true, // allow to upload multiple files per one upload
 *     'content_id' => 4321, // content id to associate ghost files with
 *     'ghost_template' => $mixedGhostTemplate, // template for nested form
 *     'name' => 'attachment', // name of file form field, resulted file id is assigned to this field name
 *     'caption' => _t('Attachments'), // form field caption
 * ),
 * @endcode
 *
 *
 * Available uploaders:
 * - sys_simple - upload files using standard HTML forms.
 * - sys_html5 - upload files using AJAX uploader with multiple files selection support (without flash),
 *   it works in Firefox and WebKit(Safari, Chrome) browsers only, but has fallback for other browsers (IE, Opera).
 *
 *
 * Uploaded files are showed as "nested" forms.
 * You can pass nested form in 'ghost_template' parameter.
 * If you don't pass anything in 'ghost_template' parameter, then only file id is passed upon form submission.
 * The nested form can be declared using the different ways:
 *
 *
 * 1. Pass template as string - just plain string with HTML, for example:
 *
 * @code
 * <div id="bx-uploader-file-{file_id}" class="bx-uploader-ghost">
 *     <div style="border:2px dotted green; padding:10px; margin-bottom:10px;">
 *         <input type="hidden" name="f[]" value="{file_id}" />
 *         {file_name} <br />
 *         <a href="javascript:void(0);" onclick="{js_instance_name}.deleteGhost('{file_id}')">delete</a>
 *     </div>
 * </div>
 * @endcode
 *
 *
 * 2. Pass form array - regular form array, but with inputs array only, for example:
 *
 * @code
 *  array (
 *      'inputs' => array (
 *          'file_name' => array (
 *              'type' => 'text',
 *              'name' => 'file_name[]',
 *              'value' => '{file_title}',
 *              'caption' => _t('Caption'),
 *          ),
 *          'file_desc' => array (
 *              'type' => 'textarea',
 *              'name' => 'file_desc[]',
 *              'caption' => _t('Description'),
 *          ),
 *      ),
 *  );
 * @endcode
 *
 * Array is automatically modified to add necessary form attributes to work as nested form,
 * file id field is added automatically as hidden input as well.
 *
 *
 * 3. Pass instance of BxDolFormNested class - use BxDolFormNested class or its custom subclass;
 * to create instance use the same form array as in the previous variant, for example:
 *
 * @code
 * $oFormNested = new BxDolFormNested('attachment', $aFormNested, 'do_submit');
 * @endcode
 *
 * - 'attachment' is the name of file form field from main form.
 * - $aFormNested is form array from previous example.
 * - 'do_submit' is main form submit_name; field name of submit form input to determine if form is submitted or not.
 *
 *
 * All 3 variants can have the following replace markers to substitute with real values:
 * - {file_id} - uploaded file id
 * - {file_name} - uploaded file name with extension
 * - {file_title} - uploaded file name without extension
 * - {file_icon} - URL to file icon automatically determined by file extension
 * - {file_url} - URL to the original file
 * - {js_instance_name} - instance of BxDolUploader javascript class
 *
 */
abstract class BxDolUploader extends BxDol
{
    protected $_oTemplate;

    protected $_aObject; ///< object properties
    protected $_sStorageObject; ///< storage object name

    protected $_sUniqId; ///< uniq id used to generate UploaderJsInstance, ResultContainerId, UploadInProgressContainerId and PopupContainerId
    protected $_sUploaderJsInstance; ///< uplooader js object instance name
    protected $_sResultContainerId; ///< uploading/uploaded objects container id
    protected $_sUploadInProgressContainerId; ///< container id where upload in progress element resides
    protected $_sPopupContainerId; ///< popup container id

    protected $_sUploadErrorMessages; ///< upload error message

    protected $_sButtonTemplate; ///< template name for displaying upload button

    /**
     * constructor
     */
    protected function __construct($aObject, $sStorageObject, $sUniqId)
    {
        parent::__construct();
        $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_aObject = $aObject;
        $this->_sStorageObject = $sStorageObject;

        $this->_sUniqId = $sUniqId;

        $this->_sUploaderJsInstance = 'glUploader_' . $sUniqId . '_' . $this->_aObject['object'];
        $this->_sUploadInProgressContainerId = 'bx-form-input-files-' . $sUniqId . '-upload-in-progress-' . $this->_aObject['object'];
        $this->_sPopupContainerId = 'bx-form-input-files-' . $sUniqId . '-popup-wrapper-' . $this->_aObject['object'];

        $this->_sResultContainerId = 'bx-form-input-files-' . $sUniqId . '-upload-result';
        $this->_sErrorsContainerId = 'bx-form-input-files-' . $sUniqId . '-errors';
        $this->_sFormContainerId = 'bx-form-input-files-' . $sUniqId . '-form-cont';
    }

    static public function getObjectInstance($sObject, $sStorageObject, $sResultContainerId)
    {
        $aObject = BxDolUploaderQuery::getUploaderObject($sObject);
        if (!$aObject || !is_array($aObject) || !$aObject['active'])
            return false;

        $sClass = $aObject['override_class_name'];
        if (!empty($aObject['override_class_file']))
            require_once(BX_DIRECTORY_PATH_ROOT . $aObject['override_class_file']);

        $o = new $sClass($aObject, $sStorageObject, $sResultContainerId);

        if (!$o->isInstalled() || !$o->isAvailable())
            return false;

        return $o;
    }

    /**
     * Is uploader available?
     * @return boolean
     */
    function isAvailable()
    {
        return $this->_aObject['active'] ? true : false;
    }

    /**
     * Are required php modules installed for this uploader ?
     * @return boolean
     */
    public function isInstalled()
    {
        return true;
    }

    public function getNameJsInstanceUploader()
    {
        return $this->_sUploaderJsInstance;
    }

    public function getIdContainerResult()
    {
        return $this->_sResultContainerId;
    }

    public function getIdContainerUploadInProgress()
    {
        return $this->_sUploadInProgressContainerId;
    }

    public function getIdContainerPopup()
    {
        return $this->_sPopupContainerId;
    }

    public function getIdContainerErrors()
    {
        return $this->_sErrorsContainerId;
    }

    /**
     * Handle uploads here.
     * @param $mixedFiles as usual $_FILES['some_name'] array, but maybe some other params depending on the uploader
     * @return nothing, but if some files failed to upload, the actual error message can be determined by calling BxDolUploader::getUploadErrorMessages()
     */
    public function handleUploads ($iProfileId, $mixedFiles, $isMultiple = true, $iContentId = false, $bPrivate = true)
    {
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        if (false == ($aMultipleFiles = $oStorage->convertMultipleFilesArray($mixedFiles)))
            $aMultipleFiles = array($mixedFiles);

        if (!$isMultiple)
            $this->cleanupGhostsForProfile($iProfileId, $iContentId);

        foreach ($aMultipleFiles as $aFile) {

            $iId = $oStorage->storeFileFromForm($aFile, $bPrivate, $iProfileId, $iContentId);
            if (!$iId)
                $this->appendUploadErrorMessage(_t('_sys_uploader_err_msg', $aFile['name'], $oStorage->getErrorString()));

            if (!$isMultiple)
                break;
        }

        echo '<script>window.parent.' . $this->getNameJsInstanceUploader() . '.onUploadCompleted(\'' . bx_js_string($this->getUploadErrorMessages(), BX_ESCAPE_STR_APOS) . '\');</script>';
    }

    public function getUploadErrorMessages ($sFormat = 'HTML')
    {
        if ('HTML' == $sFormat)
            return nl2br($this->_sUploadErrorMessages);
        else
            return $this->_sUploadErrorMessages;
    }

    /**
     * Show uploader button.
     * @return HTML string
     */
    public function getUploaderButton($mixedGhostTemplate, $isMultiple = true, $aParams = array())
    {
        $this->addCssJs ();

        $sJsValue = '';
        if (is_array($mixedGhostTemplate)) {
            $sJsValue = "{\n";
            foreach ($mixedGhostTemplate as $iFileId => $s) {
                $sJsValue .= $iFileId . ':' . "'" . bx_js_string($s, BX_ESCAPE_STR_APOS) . "',\n";
            }
            $sJsValue = substr($sJsValue, 0, -2);
            $sJsValue .= "}\n";
        } else {
            $sJsValue = "'" . bx_js_string($mixedGhostTemplate, BX_ESCAPE_STR_APOS) . "'";
        }

        $aParamsDefault = array (
            'uploader_instance_name' => $this->getNameJsInstanceUploader(),
            'engine' => $this->_aObject['object'],
            'storage_object' => $this->_sStorageObject,
            'uniq_id' => $this->_sUniqId,
            'template_ghost' => $sJsValue,
            'multiple' => $isMultiple ? 1 : 0,
            'storage_private' => isset($aParams['storage_private']) ? $aParams['storage_private'] : 1,
        );
        $aParams = array_merge($aParamsDefault, $aParams);
        return $this->_oTemplate->parseHtmlByName($this->_sButtonTemplate, $aParams);
    }

    /**
     * add necessary js, css files and js translations
     */ 
    public function addCssJs ()
    {
        $this->_oTemplate->addCss('uploaders.css');
        $this->_oTemplate->addJs('BxDolUploader.js');
        $this->_oTemplate->addJsTranslation(array(
            '_sys_uploader_confirm_leaving_page',
            '_sys_uploader_confirm_close_popup',
            '_sys_uploader_upload_canceled',
        ));
    }

    /**
     * Get uploader button title
     */
    public function getUploaderButtonTitle($mixed = false)
    {
        // it is overrided in child classes
    }

    /**
     * Show uploader form.
     * @return HTML string
     */
    public function getUploaderForm($isMultiple = true, $iContentId = false, $isPrivate = true)
    {
        // it is overrided in child classes
    }

    /**
     * Display uploaded, but not saved files - ghosts
     * @param $iProfileId - profile id to get orphaned files from
     * @param $sFormat - output format, only 'json' output formt is supported
     * @param $sImagesTranscoder - transcoder object for files preview for images and videos, false by default - no preview
     * @param $iContentId - content id to get orphaned files from, false by default
     * @return JSON string
     */
    public function getGhosts($iProfileId, $sFormat, $sImagesTranscoder = false, $iContentId = false)
    {
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        $oImagesTranscoder = false;
        if ($sImagesTranscoder)
            $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($sImagesTranscoder);

        $a = '';
        $aGhosts = $oStorage->getGhosts($iProfileId, $iContentId);
        foreach ($aGhosts as $aFile) {
            $sFileIcon = '';

            if ($oImagesTranscoder && (0 == strncmp($aFile['mime_type'], 'image/', 6) || 0 == strncmp($aFile['mime_type'], 'video/', 6)))
                $sFileIcon = $oImagesTranscoder->getFileUrl($aFile['id']);

            if (!$sFileIcon)
                $sFileIcon = $this->_oTemplate->getIconUrl($oStorage->getIconNameByFileName($aFile['file_name']));

            $aVars = array (
                'file_id' => $aFile['id'],
                'file_name' => $aFile['file_name'],
                'file_title' => $oStorage->getFileTitle($aFile['file_name']),
                'file_icon' => $sFileIcon,
                'file_url' => $oStorage->getFileUrlById($aFile['id']),
                'js_instance_name' => $this->_sUploaderJsInstance,
            );

            $a[$aFile['id']] = array_merge($aVars, $this->getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder));
        }

        if ('json' == $sFormat) {
            return json_encode($a);
        } else { // html format is not suported for this data type
            return false;
        }
    }

    /**
     * Delete file by file id, usually ghost file
     * @return 'ok' string on success or error string on error
     */
    public function deleteGhost($iFileId, $iProfileId)
    {
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        $aFile = $oStorage->getFile ($iFileId);
        if (!$aFile)
            return _t('_Error Occured');

        if ($aFile['profile_id'] != $iProfileId)
            return _t('_Error Occured');

        if (!$oStorage->deleteFile($iFileId))
            return $oStorage->getErrorString();

        return 'ok';
    }

    /**
     * Delete all ghosts files for the specified profile
     * @return number of delete ghost files
     */
    public function cleanupGhostsForProfile($iProfileId, $iContentId = false)
    {
        $iCount = 0;

        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

        $aGhosts = $oStorage->getGhosts($iProfileId, $iContentId);
        foreach ($aGhosts as $aFile)
            $iCount += $oStorage->afterUploadCleanup($aFile['id'], $iProfileId);

        return $iCount;
    }

    protected function cleanUploadErrorMessages ()
    {
        $this->_sUploadErrorMessages = '';
    }

    protected function appendUploadErrorMessage ($s)
    {
        $this->_sUploadErrorMessages .= ($this->_sUploadErrorMessages ? "\n" : '') . $s;
    }

    protected function getRestrictionsText ()
    {
        $sTextRestrictions = '';
        $oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);
        if (!$oStorage)
            return '';

        $a = $oStorage->getRestrictionsTextArray(bx_get_logged_profile_id());
        foreach ($a as $s)
            $sTextRestrictions .= '<div class="bx-uploader-msg-info bx-def-font-grayed">' . $s . '</div>';

        return $sTextRestrictions;
    }

    protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
        return array();
    }
}

/** @} */
