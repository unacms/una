<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinInstall Dolphin Install
 * @{
 */

class BxDolInstallSiteConfig
{
    protected $_aConfig;

    public function __construct()
    {
        $this->_aConfig = array (

            // path config

            'section_site_paths_open' => array(
                'name' => _t('_sys_inst_conf_section_paths'),
                'func' => 'rowSectionOpen',
            ),

            'site_url' => array(
                'name' => _t('_sys_inst_conf_field_site_url'),
                'ex' => 'http://www.mydomain.com/path/',
                'desc' => _t('_sys_inst_conf_desc_site_url'),
                'def' => 'http://',
                'def_exp' => array('defUrl', ''),
                'check' => array('checkLength', 10),
            ),
            'dir_root' => array(
                'name' => _t('_sys_inst_conf_field_root_dir'),
                'ex' => '/home/mydomain/public_html/',
                'desc' => _t('_sys_inst_conf_desc_root_dir'),
                'def_exp' => array('defPath', ''),
                'check' => array('checkLength', 1),
            ),
            'dir_convert' => array(
                'name' => _t('_sys_inst_conf_field_path_to_binary', 'convert'),
                'ex' => '/usr/local/bin/convert',
                'desc' => _t('_sys_inst_conf_desc_path_to_binary', 'convert'),
                'def' => '/usr/local/bin/convert',
                'def_exp' => array('defImageMagickBin', 'convert'),
                'check' => array('checkLength', 7),
            ),
            'dir_composite' => array(
                'name' => _t('_sys_inst_conf_field_path_to_binary', 'composite'),
                'ex' => '/usr/local/bin/composite',
                'desc' => _t('_sys_inst_conf_desc_path_to_binary', 'composite'),
                'def' => '/usr/local/bin/composite',
                'def_exp' => array('defImageMagickBin', 'composite'),
                'check' => array('checkLength', 7),
            ),

            'section_site_paths_close' => array(
                'func' => 'rowSectionClose',
            ),

            // db config

            'section_db_config_open' => array(
                'name' => _t('_sys_inst_conf_section_db_config'),
                'func' => 'rowSectionOpen',
            ),

            'db_host' => array(
                'name' => _t('_sys_inst_conf_field_db_host'),
                'ex' => 'localhost',
                'desc' => _t('_sys_inst_conf_desc_db_host'),
                'def' => 'localhost',
                'check' => array('checkLength', 1),
            ),
            'db_port' => array(
                'name' => _t('_sys_inst_conf_field_db_port'),
                'ex' => '5506',
                'desc' => _t('_sys_inst_conf_desc_db_port'),
            ),
            'db_sock' => array(
                'name' => _t('_sys_inst_conf_field_db_sock'),
                'ex' => '/tmp/mysql.sock',
                'desc' => _t('_sys_inst_conf_desc_db_sock'),
            ),
            'db_name' => array(
                'name' => _t('_sys_inst_conf_field_db_name'),
                'ex' => 'mydomian_dolphin',
                'desc' => _t('_sys_inst_conf_desc_db_name'),
                'check' => array('checkLength', 1),
            ),
            'db_user' => array(
                'name' => _t('_sys_inst_conf_field_db_user'),
                'ex' => 'mydomian_dolphin',
                'desc' => _t('_sys_inst_conf_desc_db_user'),
                'check' => array('checkLength', 1),
            ),
            'db_password' => array(
                'name' => _t('_sys_inst_conf_field_db_pwd'),
                'ex' => 'Super*Secret#Word_1234',
                'desc' => _t('_sys_inst_conf_desc_db_pwd'),
            ),

            'section_db_config_close' => array(
                'func' => 'rowSectionClose',
            ),

            // site config

            'section_site_info_open' => array(
                'name' => _t('_sys_inst_conf_section_site_info'),
                'func' => 'rowSectionOpen',
            ),

            'site_title' => array(
                'name' => _t('_sys_inst_conf_field_site_title'),
                'ex' => 'The Best Community',
                'desc' => _t('_sys_inst_conf_desc_site_title'),
                'check' => array('checkLength', 1),
            ),
            'site_desc' => array(
                'name' => _t('_sys_inst_conf_field_site_desc'),
                'ex' => 'The place to find new friends, communicate and have fun.',
                'desc' => _t('_sys_inst_conf_desc_site_desc'),
                'check' => array('checkLength', 1),
            ),
            'site_email' => array(
                'name' => _t('_sys_inst_conf_field_site_email'),
                'ex' => 'no-reply@youdomain.here',
                'desc' => _t('_sys_inst_conf_desc_site_email'),
                'check' => array('checkEmail', 3),
            ),
            'admin_email' => array(
                'name' => _t('_sys_inst_conf_field_admin_email'),
                'ex' => 'admin@email.here',
                'desc' => _t('_sys_inst_conf_desc_admin_email'),
                'check' => array('checkEmail', 3),
            ),
            'admin_username' => array(
                'name' => _t('_sys_inst_conf_field_admin_username'),
                'ex' => 'admin',
                'desc' => _t('_sys_inst_conf_desc_admin_username'),
                'check' => array('checkLength', 1),
            ),
            'admin_password' => array(
                'name' => _t('_sys_inst_conf_field_admin_pwd'),
                'ex' => 'Super*Secret#Word_1234',
                'desc' => _t('_sys_inst_conf_desc_admin_pwd'),
                'check' => array('checkLength', 1),
            ),

            'section_site_info_close' => array(
                'func' => 'rowSectionClose',
            ),
        );
    }

    public function getFormHtml() 
    {
        $aErrorFields = array();
        if (isset($_POST['site_config'])) {
            $aErrorFields = $this->checkConfig();
            if (empty($aErrorFields)) {
                die('TODO: everything is correct - save data and redirect to the next step');
            }
        }

        $sRows = $this->getFormFields($aErrorFields);
        $sSubmitTitle = _t('_Submit');
        return <<<EOF
            <form method="post">
                <div class="bx-form-advanced-wrapper sys_account_wrapper">

                    {$sRows}

                    <div class="bx-form-element-wrapper bx-def-margin-top">
                        <div class="bx-form-value">
                            <div class="bx-form-input-wrapper bx-form-input-wrapper-submit">
                                <button class="bx-def-font-inputs bx-form-input-submit bx-btn bx-btn-primary" type="submit" name="site_config">
                                    {$sSubmitTitle}
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            <form>
EOF;
    }

    public function checkConfig() 
    {
        $aErrorFields = array();
        foreach ($this->_aConfig as $sKey => $a) {
            if (!$this->check ($sKey, isset($_POST[$sKey]) ? $_POST[$sKey] : '', $a)) {
                $aErrorFields[$sKey] = true;
                unset($_POST[$sKey]);
            }
        }
        return $aErrorFields;
    }

    protected function getFormFields($aErrorFields) 
    {
        $s = '';
        foreach($this->_aConfig as $sKey => $a) {
            $sFunc = isset($a['func']) ? $a['func'] : 'rowInput';
            $s .= $this->$sFunc($sKey, $a, isset($aErrorFields[$sKey]) ? $aErrorFields[$sKey] : false);
        }

        return $s;
    }
    
    protected function rowInput ($sKey, $a, $isError = false) 
    {
        $sAutoMessage = "";
        $sValue = bx_html_attribute($this->def ($sKey, $a, $sAutoMessage));

        $sDesc = _t('_sys_inst_conf_desc', $sAutoMessage, $a['desc'], $a['ex']);

        $sError = '';
        if ($isError)
            $sError = '<div class="bx-form-warn">' . _t('_sys_inst_conf_error') . '</div>';

        $sRequired = '';
        if (isset($a['check']) && $a['check'])
            $sRequired = '<span class="bx-form-required">*</span>';

        return <<<EOF
            <div class="bx-form-element-wrapper bx-def-margin-top-auto">
                <div class="bx-form-caption">
                    {$a['name']}
                    {$sRequired}
                </div>
                <div class="bx-form-value">
                    <div class="bx-form-input-wrapper bx-form-input-wrapper-text">
                        <input type="text" name="{$sKey}" value="{$sValue}" class="bx-def-font-inputs bx-form-input-text" />
                    </div>
                    $sError
                    <div class="bx-form-info bx-def-font-grayed bx-def-font-small">
                        {$sDesc}
                    </div>                    
                </div></div>
EOF;
    }

    protected function rowSectionOpen ($sKey, $a) 
    {
        return <<<EOF
                    <div class="bx-form-section-wrapper bx-def-margin-top">
                        <fieldset class="bx-form-section bx-def-padding-sec-top bx-def-border-top bx-form-section-header">
                            <legend class="bx-def-padding-sec-right bx-def-font-grayed bx-def-font-h3">{$a['name']}</legend>
                            <div class="bx-form-section-content bx-def-padding-top bx-def-padding-bottom">
EOF;
    }

    protected function rowSectionClose ($sKey, $a) 
    {
        return <<<EOF
                            </div>
                        </fieldset>
                    </div>
EOF;
    }

    protected function check ($sKey, $sValue, $a) 
    {
        if (empty($a['check']))
            return true;
        return $this->{$a['check'][0]}($sValue, $a['check'][1]);
    }

    protected function checkLength ($s, $i) 
    {
        return mb_strlen($s) >= $i ? true : false;
    }

    protected function checkEmail ($s, $i) 
    {
        return mb_strlen($s) > $i && false !== strpos($s, '@') ? true : false;
    }

    protected function def ($sKey, $a, &$sAutoMessage) 
    {
        if (isset($_POST[$sKey]))
            return bx_process_pass($_POST[$sKey]);
        if (empty($a['def_exp']))
            return '';
        $s = $this->{$a['def_exp'][0]}($a['def_exp'][1]);
        if ($s) {
            $sAutoMessage = _t('_sys_inst_conf_found') . '<br />';
            return $s;
        } else {
            $sAutoMessage = _t('_sys_inst_conf_not_found') . '<br />';
        }
        return isset($a['def']) ? $a['def'] : '';
    }

    protected function defUrl ($foo) 
    {
        $s = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        return preg_replace("/install\/(index\.php$)/", '', $s);
    }

    protected function defPath ($foo) 
    {
        $s = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $_SERVER['PHP_SELF'];
        return preg_replace("/install\/(index\.php$)/", '', $s);
    }

    protected function defImageMagickBin ($sBin) 
    {
        $a = array(
            '/usr/X11R6/bin/', 
            '/usr/local/bin/',
            '/usr/bin/',
            '/usr/local/X11R6/bin/',
            '/usr/bin/X11/',
            '/opt/local/bin/',
        );
        foreach ($a as $sPath)
            if (file_exists($sPath . $sBin))
                return $sPath . $sBin;
        return '';
    }
}

/** @} */
