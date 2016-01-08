<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolAcl');

define('BX_DOL_REPORT_USAGE_BLOCK', 'block');
define('BX_DOL_REPORT_USAGE_INLINE', 'inline');
define('BX_DOL_REPORT_USAGE_DEFAULT', BX_DOL_REPORT_USAGE_BLOCK);

/**
 * @page objects
 * @section reports Reports
 * @ref BxDolReport
 */

/**
 * Report any content
 *
 * Related classes:
 * - BxDolReportQuery - report database queries
 * - BxBaseReport - report base representation
 * - BxTemplReport - custom template representation
 *
 * AJAX report for any content.
 *
 * To add report section to your feature you need to add a record to 'sys_objects_report' table:
 *
 * - id - autoincremented id for internal usage
 * - name - your unique module name, with vendor prefix, lowercase and spaces are underscored
 * - table_main - table name where summary reports are stored
 * - table_track - table name where each report is stored
 * - is_on - is this report object enabled
 * - base_url - base URL for reported object
 * - trigger_table - table to be updated upon each report
 * - trigger_field_id - trigger_table table field with unique record id, primary key
 * - trigger_field_author - trigger_table table field with author
 * - trigger_field_count - trigger_table table field with reports count
 * - class_name - your custom class name, if you overrride default class
 * - class_file - your custom class path
 *
 * You can refer to BoonEx modules for sample record in this table.
 *
 *
 *
 * @section example Example of usage:
 * You can show report in any place, using the following code:
 * @code
 * $o = BxDolReport::getObjectInstance('system object name', $iYourEntryId);
 * if (!$o->isEnabled()) 
 *     return '';
 * echo $o->getElementBlock();
 * @endcode
 *
 *
 * @section acl Memberships/ACL:
 * - report
 * - report view
 *
 *
 * @section alerts Alerts:
 * Alerts type/unit - every module has own type/unit, it equals to ObjectName.
 * The following alerts are rised:
 *
 * - type: 'object name', action: doReport
 * - type: report, action: do
 *
 */

class BxDolReport extends BxDolObject
{
	protected $_oTemplate;

	protected $_sBaseUrl;

	protected $_sFormObject;
    protected $_sFormDisplayPost;

	protected $_aTypes;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit);
        if(empty($this->_sSystem))
            return;

        $this->_oQuery = new BxDolReportQuery($this);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

		$this->_sBaseUrl = BxDolPermalinks::getInstance()->permalink($this->_aSystem['base_url']);
        if(get_mb_substr($this->_sBaseUrl, 0, 4) != 'http')
            $this->_sBaseUrl = BX_DOL_URL_ROOT . $this->_sBaseUrl;

		$this->_sFormObject = 'sys_report';
		$this->_sFormDisplayPost = 'sys_report_post';

		$this->_aTypes = array('spam', 'scam', 'fraud', 'nude', 'other');
    }

    /**
     * get reports object instanse
     * @param $sSys report object name
     * @param $iId associated content id, where report is available
     * @param $iInit perform initialization
     * @return null on error, or ready to use class instance
     */
    public static function getObjectInstance($sSys, $iId, $iInit = true, $oTemplate = false)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolReport!' . $sSys . $iId]))
            return $GLOBALS['bxDolClasses']['BxDolReport!' . $sSys . $iId];

        $aSystems = self::getSystems();
        if(!isset($aSystems[$sSys]))
            return null;

        $sClassName = 'BxTemplReport';
        if(!empty($aSystems[$sSys]['class_name'])) {
            $sClassName = $aSystems[$sSys]['class_name'];
            if(!empty($aSystems[$sSys]['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aSystems[$sSys]['class_file']);
        }

        $o = new $sClassName($sSys, $iId, $iInit, $oTemplate);
        return ($GLOBALS['bxDolClasses']['BxDolReport!' . $sSys . $iId] = $o);
    }

    public static function &getSystems()
    {
        if(!isset($GLOBALS['bx_dol_report_systems']))
            $GLOBALS['bx_dol_report_systems'] = BxDolDb::getInstance()->fromCache('sys_objects_report', 'getAllWithKey', '
                SELECT
                    `id` as `id`,
                    `name` AS `name`,
                    `table_main` AS `table_main`,
                    `table_track` AS `table_track`,
                    `is_on` AS `is_on`,
                    `base_url` AS `base_url`,
                    `trigger_table` AS `trigger_table`,
                    `trigger_field_id` AS `trigger_field_id`,
                    `trigger_field_author` AS `trigger_field_author`,
                    `trigger_field_count` AS `trigger_field_count`,
                    `class_name` AS `class_name`,
                    `class_file` AS `class_file`
                FROM `sys_objects_report`', 'name');

        return $GLOBALS['bx_dol_report_systems'];
    }

    /**
     * Interface functions for outer usage
     */
	public function getBaseUrl()
    {
        return $this->_replaceMarkers($this->_sBaseUrl);
    }

    public function getStatCounter()
    {
        $aReport = $this->_oQuery->getReport($this->getId());
        return $aReport['count'];
    }

    /**
     * Actions functions
     */
    public function actionReport()
    {
        return echoJson($this->_getReport());
    }

	public function actionGetReportedBy()
    {
        if (!$this->isEnabled())
           return '';

	    if(!$this->isAllowedReportView(true))
            return $this->msgErrAllowedReportView();

        return $this->_getReportedBy();
    }

    /**
     * Permissions functions
     */
    public function isAllowedReport($isPerformAction = false)
    {
        if(isAdmin())
            return true;

        return $this->checkAction('report', $isPerformAction);
    }

    public function msgErrAllowedReport()
    {
        return $this->checkActionErrorMsg('report');
    }

    public function isAllowedReportView($isPerformAction = false)
    {
        if(isAdmin())
            return true;

        return $this->checkAction('report_view', $isPerformAction);
    }

    public function msgErrAllowedReportView()
    {
        return $this->checkActionErrorMsg('report_view');
    }

    /**
     * Internal functions
     */
    protected function _getIconDoReport($bPerformed)
    {
    	return 'exclamation-circle';
    }

    protected function _getTitleDoReport($bPerformed)
    {
    	return '_report_do_report';
    }

	protected function _getFormObject()
    {
        return BxDolForm::getObjectInstance($this->_sFormObject, $this->_sFormDisplayPost);
    }
}

/** @} */
