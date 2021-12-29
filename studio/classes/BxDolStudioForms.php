<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_FORM_TYPE_FORMS', 'forms');
define('BX_DOL_STUDIO_FORM_TYPE_DISPLAYS', 'displays');
define('BX_DOL_STUDIO_FORM_TYPE_FIELDS', 'fields');
define('BX_DOL_STUDIO_FORM_TYPE_PRE_LISTS', 'pre_lists');
define('BX_DOL_STUDIO_FORM_TYPE_PRE_VALUES', 'pre_values');
define('BX_DOL_STUDIO_FORM_TYPE_SEARCH_FORMS', 'search_forms');
define('BX_DOL_STUDIO_FORM_TYPE_SEARCH_FIELDS', 'search_fields');
define('BX_DOL_STUDIO_FORM_TYPE_SEARCH_SORTABLE_FIELDS', 'search_sortable_fields');
define('BX_DOL_STUDIO_FORM_TYPE_LABELS', 'labels');
define('BX_DOL_STUDIO_FORM_TYPE_CATEGORIES', 'categories');
define('BX_DOL_STUDIO_FORM_TYPE_GROUPS_ROLES', 'groups_roles');

define('BX_DOL_STUDIO_FORM_TYPE_DEFAULT', BX_DOL_STUDIO_FORM_TYPE_FORMS);

class BxDolStudioForms extends BxTemplStudioWidget
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('builder_forms');

        $this->oDb = new BxDolStudioFormsQuery();

        $this->sPage = BX_DOL_STUDIO_FORM_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }

    public function checkAction()
    {
        $sAction = bx_get('form_action');
    	if($sAction === false)
            return false;

        $sAction = bx_process_input($sAction);

        $aResult = array('code' => 1, 'message' => _t('_adm_form_err_cannot_process_action'));
        switch($sAction) {
            case 'get-page-by-type':
                $sValue = bx_process_input(bx_get('form_value'));
                if(empty($sValue))
                    break;

                $this->sPage = $sValue;
                $aResult = array('code' => 0, 'content' => $this->getPageCode());
                break;

            default:
                $sMethod = 'action' . $this->getClassName($sAction);
                if(method_exists($this, $sMethod))
                    $aResult = $this->$sMethod();
        }

        return $aResult;
    }

    protected function getSystemName($sValue)
    {
        return str_replace(' ', '_', strtolower($sValue));
    }

    protected function getClassName($sValue)
    {
        return bx_gen_method_name($sValue);
    }
}

/** @} */
