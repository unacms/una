<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolSiteMaps');
bx_import('BxDolPrivacy');

/**
 * Sitemaps generator for News
 */
class BxDolTextSiteMaps extends BxDolSiteMaps
{
    protected $_oModule;

    protected function __construct($aSystem, &$oModule)
    {
        parent::__construct($aSystem);

        $this->_oModule = $oModule;
        $this->_aQueryParts = array (
            'fields' => "`id`, `uri`, `when`", // fields list
            'field_date' => "when", // date field name
            'field_date_type' => "timestamp", // date field type
            'table' => "`" . $this->_oModule->_oConfig->getDbPrefix() . "entries`", // table name
            'join' => "", // join SQL part
            'where' => "AND `status` = '" . BX_TD_STATUS_ACTIVE . "'", // SQL condition, without WHERE
            'order' => " `when` ASC ", // SQL order, without ORDER BY
        );
    }

    protected function _genUrl ($a)
    {
        return BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'view/' . $a['uri'];
    }
}
