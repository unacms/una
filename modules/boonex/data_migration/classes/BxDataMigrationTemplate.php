<?php

    /***************************************************************************
    *                            Dolphin Smart Community Builder
    *                              -------------------
    *     begin                : Mon Mar 23 2006
    *     copyright            : (C) 2007 BoonEx Group
    *     website              : http://www.boonex.com
    * This file is part of Dolphin - Smart Community Builder
    *
    * Dolphin is free software; you can redistribute it and/or modify it under
    * the terms of the GNU General Public License as published by the
    * Free Software Foundation; either version 2 of the
    * License, or  any later version.
    *
    * Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
    * without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    * See the GNU General Public License for more details.
    * You should have received a copy of the GNU General Public License along with Dolphin,
    * see license.txt file; if not, write to marketing@boonex.com
    ***************************************************************************/

    bx_import('BxDolModuleTemplate');

    class BxDataMigrationTemplate extends BxDolModuleTemplate
    {
        /**
         * Class constructor
         */
        function BxDataMigrationTemplate(&$oConfig, &$oDb)
        {
            parent::BxDolModuleTemplate($oConfig, $oDb);
        }

        function pageCodeAdminStart()
        {
            ob_start();
        }

        function adminBlock ($sContent, $sTitle, $aMenu = array())
        {
            return DesignBoxAdmin($sTitle, $sContent, $aMenu);
        }

        function pageCodeAdmin ($sTitle)
        {
            global $_page;
            global $_page_cont;

            $_page['name_index'] = 9;

            $_page['header'] = $sTitle ? $sTitle : $GLOBALS['site']['title'];
            $_page['header_text'] = $sTitle;

            $_page_cont[$_page['name_index']]['page_main_code'] = ob_get_clean();

            PageCodeAdmin();
        }

        /**
         * Function will include the js file ;
         *
         * @param  : $sName (string) - name of needed file ;
         * @return : (text) ;
         */
        function addJs($sName)
        {
            return '<script type="text/javascript" src="' . $this -> _oConfig -> getHomeUrl() . 'js/' . $sName . '" language="javascript"/></script>';
        }

        /**
         * Function will get spy's css file;
         *
         * @param : $sFileName (string) - css file name;
         * @return : (string);
         */
        function getCssFile($sFileName)
        {
            return '<link href="' . $this -> getCssUrl($sFileName) . '" rel="stylesheet" type="text/css" />';
        }

        /**
         * Function will get config form;
         *
         * @param  : $oModule (object) - current module;
         * @return : (text) - html presentation data;
         */
        function getConfigForm(&$oModule)
        {
            $aForm = array (
                'form_attrs' => array (
                    'action' =>  $oModule -> sPathToModule . 'administration/settings',
                    'method' => 'post',
                    'name' => 'form',
                ),

                'params' => array (
                    'checker_helper' => 'BxCheckerMigration',
                    'db' => array(
                        'submit_name' => 'do_submit', // some filed name with non empty value to determine if the for was submitted,
                    ),
                ),

                'inputs' => array(
                    'location' => array (
                        'type'     => 'text',
                        'name'     => 'location',
                        'caption'  => _t('_bx_data_migration_q_where_located'),
                        'required' => true,
                        'value'       => $oModule  -> _oDb -> getExtraParam('root'),
                        'info'     => _t('_bx_data_migration_q_d_where_located', ' ' . BX_DIRECTORY_PATH_ROOT),

                        // checker params
                        'checker' => array (
                            'func'   => 'DolpinDirectory',
                            'params' => array(),
                            'error'  => _t('_bx_data_migration_q_e_wrong_path'),
                        ),
                    ),
                ),
            );

           // add submit button;
           $aForm['inputs'][] = array (
                'type' => 'submit',
                'name' => 'do_submit',
                'value' => _t('_Submit'),
           );

           $oForm = & new BxTemplFormView($aForm);
           $oForm -> initChecker();

            if ( $oForm -> isSubmittedAndValid() ) {
                $sOutputCode = $oModule -> createConfig( $oForm -> getCleanValue('location') );
            }
            else {
                $sOutputCode = $oForm -> getCode();
            }

           return $sOutputCode;
        }

        /**
         * Function will generate list of modules;
         *
         * @param  : $aModules (array) - list of all allowed modules;
         * @param  : $aStatusTexts (array) - list of modules status texts;
         * @param  : $oModule (object) - current module's instance;
         * @return : (text) html presentation data;
         */
        function getModulesList($aModules, &$aStatusTexts, &$oModule)
        {
            $sLoadingImg  = $this -> getIconUrl('loading.gif');
            $sOutputCode  = $this -> addJs('data_migration.js');

            $sOutputCode .= "<script type=\"text/javascript\">
                oDataMigration.sPathToModule = '{$oModule -> sPathToModule}migration/';
                oDataMigration.sLoadingImg = '{$sLoadingImg}';
               </script>";

            $aForm = array (
                'form_attrs' => array (
                    'action' =>  $oModule -> sPathToModule . 'administration',
                    'method' => 'post',
                    'name' => 'form',
                ),

                'params' => array (
                    'db' => array(
                        'submit_name' => 'do_submit', // some filed name with non empty value to determine if the for was submitted,
                    ),
                ),

                'inputs' => array(
                    'modules' => array (
                        'type'     => 'select',
                        'name'     => 'module',
                        'caption'  => _t('_bx_data_migration_select'),
                        'required' => true,
                        'attrs'    => array(
                            'id' => 'modules',
                        ),
                    ),
               ),
            );

           // add submit button;
           $aForm['inputs'][] = array (
                'type'  => 'button',
                'value' => _t('_bx_data_migration_move'),
                'attrs'    => array(
                    'onclick' => 'oDataMigration.moveData( $(\'#modules\').val() )',
                ),
           );

            $aForm['inputs'][] = array (
                     'type' => 'custom',
                    'content' => '<div id="callback">&nbsp;</div>',
                    'colspan' => true,
            );

           $aForm['inputs']['modules']['values'] = $aModules;

           // list of status text processed modules;
           if( $aStatusTexts && is_array($aStatusTexts) ) {
               foreach($aStatusTexts as $sModule => $sStatusText)
               {
                   $aForm['inputs'][] = array (
                             'type' => 'custom',
                            'content' => $sStatusText,
                            'caption' => $sModule,
                    );
               }
           }

           $oForm = & new BxTemplFormView($aForm);
           $sOutputCode .= $oForm -> getCode();

           return $this -> addCss('data_migration.css', true) . $sOutputCode;
        }
    }