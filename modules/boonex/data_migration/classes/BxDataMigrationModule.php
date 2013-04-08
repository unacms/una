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

    bx_import('BxDolModuleDb');
    bx_import('BxDolModule');

    require_once 'BxCheckerMigration.php';

    if ( function_exists('ini_set')) {
        ini_set('max_execution_time', 0);
    }

    define('MIGRATION_SUCCESSFUL', 1);
    define('MIGRATION_FAILED', 0);

    class BxDataMigrationModule extends BxDolModule
    {
        // contain some module information ;
        var $aModuleInfo;

        // contain path for current module;
        var $sPathToModule;

        var $aMigrationModules;

        var $sProcessedModule;

        /**
         * Class constructor ;
         *
         * @param   : $aModule (array) - contain some information about this module;
         *                  [ id ]           - (integer) module's  id ;
         *                  [ title ]        - (string)  module's  title ;
         *                  [ vendor ]       - (string)  module's  vendor ;
         *                  [ path ]         - (string)  path to this module ;
         *                  [ uri ]          - (string)  this module's URI ;
         *                  [ class_prefix ] - (string)  this module's php classes file prefix ;
         *                  [ db_prefix ]    - (string)  this module's Db tables prefix ;
         *                  [ date ]         - (string)  this module's date installation ;
         */
        function BxDataMigrationModule(&$aModule)
        {
            parent::BxDolModule($aModule);

            // prepare the location link ;
            $this -> sPathToModule  = BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri();
            $this -> aModuleInfo    = $aModule;

            $this -> aMigrationModules = array(
               'profiles' => array(
                    'is_module'       => false,
                    'module_name'     => 'profiles',
                    'module_class'    => '',
                    'migration_class' => 'BxDataMigrationProfiles',
                    'dependencies' => array(
                    ),
                ),

                'poll' => array(
                    'is_module' => true,
                    'module_name'     => 'poll',
                    'module_class'    => 'BxPollModule',
                    'migration_class' => 'BxDataMigrationPoll',
                    'dependencies' => array(
                         'profiles',
                     ),
                ),

                'events' => array(
                    'is_module' => true,
                    'module_name'     => 'events',
                    'module_class'    => 'BxEventsModule',
                    'migration_class' => 'BxDataMigrationEvents',
                    'dependencies' => array(
                        'profiles',
                        'photos',
                     ),
                 ),

                'blogs' => array(
                    'is_module' => true,
                    'module_name'     => 'blogs',
                    'module_class'    => 'BxBlogsModule',
                    'migration_class' => 'BxDataMigrationBlogs',
                    'dependencies' => array(
                         'profiles',
                    ),
                ),

                'ads' => array(
                    'is_module' => true,
                    'module_name'     => 'ads',
                    'module_class'    => 'BxAdsModule',
                    'migration_class' => 'BxDataMigrationAds',
                    'dependencies' => array(
                         'profiles',
                    ),
                ),

                'news' => array(
                    'is_module' => true,
                    'module_name'     => 'news',
                    'module_class'    => 'BxNewsModule',
                    'migration_class' => 'BxDataMigrationNews',
                    'dependencies' => array(
                         'profiles',
                    ),
                ),

                'articles' => array(
                    'is_module' => true,
                    'module_name'     => 'articles',
                    'module_class'    => 'BxArlModule',
                    'migration_class' => 'BxDataMigrationArticles',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'feedback' => array(
                    'is_module' => true,
                    'module_name'     => 'feedback',
                    'module_class'    => 'BxFdbModule',
                    'migration_class' => 'BxDataMigrationFeedback',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'sounds' => array(
                    'is_module' => true,
                    'module_name'     => 'sounds',
                    'module_class'    => 'BxSoundsModule',
                    'migration_class' => 'BxDataMigrationSounds',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'photos' => array(
                    'is_module' => true,
                    'module_name'     => 'photos',
                    'module_class'    => 'BxPhotosModule',
                    'migration_class' => 'BxDataMigrationPhotos',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'videos' => array(
                    'is_module' => true,
                    'module_name'     => 'videos',
                    'module_class'    => 'BxVideosModule',
                    'migration_class' => 'BxDataMigrationVideos',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'forum' => array(
                    'is_module' => true,
                    'module_name'     => 'forum',
                    'module_class'    => 'Orca Forum',
                    'migration_class' => 'BxDataMigrationForum',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'acl_levels' => array(
                    'is_module' => false,
                    'module_name'     => 'acl_levels',
                    'module_class'    => '',
                    'migration_class' => 'BxDataMigrationMembership',
                    'dependencies' => array(
                        'profiles',
                     ),
                ),

                'custom_profile_fields' => array(
                    'is_module' => false,
                    'module_name'     => 'custom_profile_fields',
                    'module_class'    => '',
                    'migration_class' => 'BxDataMigrationProfilesCustomFields',
                    'dependencies' => array(
                        'profiles',
                    ),
                ),

                'groups' => array(
                    'is_module' => true,
                    'module_name'     => 'groups',
                    'module_class'    => 'BxGroupsModule',
                    'migration_class' => 'BxDataMigrationGroups',
                    'dependencies' => array(
                           'profiles',
                        'photos',
                    ),
                ),
             );
        }

        /**
         * Function will get migration from recived modules;
         * @return unknown_type
         */
        function actionMigration($sModule = '')
        {
            // define the module;
            if( isset($this -> aMigrationModules[$sModule]) && $this -> _oDb -> getExtraParam('is_configured') ) {
                // trasnfer status;
                $sTransferred = $this -> _oDb -> getTransferStatus($sModule);
                if( $sTransferred == 'finished' || $sTransferred == 'not_started' ) {
                    echo MsgBox( _t('_bx_data_migration_already_transfer', $sModule) );
                    exit;
                }
                else {
                    // check module dependencies;
                    if( isset($this -> aMigrationModules[$sModule]['dependencies'])
                        && $this -> aMigrationModules[$sModule]['dependencies']) {

                        foreach($this -> aMigrationModules[$sModule]['dependencies'] as $iKey => $sDependenciesModule)
                        {
                            $sTransferred = $this -> _oDb -> getTransferStatus($sDependenciesModule);
                            if( $sTransferred != 'finished') {
                                echo MsgBox( _t('_bx_data_migration_install_before', $sDependenciesModule) );
                                exit;
                            }
                        }
                    }

                    // create new transfer;
                    $sTransferResult = $this -> _oDb -> checkTransfer($sModule);
                    if( !$sTransferResult || $sTransferResult == 'error' ) {
                        if($sTransferResult == 'error') {
                             $this -> _oDb -> deleteTransfer($sModule);
                        }
                        $this -> _oDb -> createTransfer($sModule);
                        echo MsgBox( _t('_bx_data_migration_start_transfer') );
                    }
                    else {
                        echo MsgBox( _t('_bx_data_migration_install_started', $sModule) );
                    }
                }
            }
            else {
                echo MsgBox( _t('_bx_data_migration_module_not_defined') );
            }
        }

        /**
         * Function will transfer data;
         *
         * @return : void;
         */
        function transferData()
        {
            $sModule = $this -> _oDb -> getFirstTransfer();

             // define the module;
            if( $sModule && isset($this -> aMigrationModules[$sModule])
                    && $this -> _oDb -> getExtraParam('is_configured') ) {

                // create new module's instance;
                require_once($this -> aMigrationModules[$sModule]['migration_class'] . '.php');

                $oDolModule = '';
                // create module instance;
                if($this -> aMigrationModules[$sModule]['module_class']) {
                    $oDolModule = BxDolModule::getInstance($this -> aMigrationModules[$sModule]['name']);
                }

                $this -> sProcessedModule = $sModule;

                // set as started;
                $this -> _oDb -> updateTransferStatus($sModule, 'started');

                 // create new migration instance;
                $oModule = & new $this -> aMigrationModules[$sModule]['migration_class']( $this
                    , $this -> _oDb -> oldDbConnect(), $oDolModule);

                // get migration;
                $iOperation = $oModule -> getMigration();

                if( $iOperation == MIGRATION_SUCCESSFUL) {
                    //set as finished;
                    //$this -> _oDb -> updateTransferStatus($sModule, 'not_started');
                    $this -> _oDb -> updateTransferStatus($sModule, 'finished');
                }
                else {
                    // set as error;
                    $this -> _oDb -> updateTransferStatus($sModule, 'error');
                }
            }
        }

         /**
         * Function will generate the poll's admin page ;
         *
         * @return : (text) - Html presentation data ;
         */
        function actionAdministration($sAction = '')
        {
            $GLOBALS['iAdminPage'] = 1;

            if( !isAdmin() ) {
                header('location: ' . BX_DOL_URL_ROOT);
            }

            // get all needed css files;
            $this -> _oTemplate-> pageCodeAdminStart();
            echo $this -> _oTemplate -> addCss('forms_adv.css', true);

            $aMenu = array(
                'data_migration' => array('title' => _t('_bx_data_migration'), 'href' => $this -> sPathToModule . 'administration'),
                'set_config'     => array('title' => _t('_bx_data_migration_set_config'), 'href' => $this -> sPathToModule . 'administration/settings'),
            );

            $sPageContent = '';
            $sPageTitle   = '';

            switch ($sAction) {
                case 'settings':
                    $aMenu['set_config']['active'] = 1;
                    $sPageTitle   = _t('_bx_data_migration_configure');
                    $sPageContent = $this -> _oTemplate -> getConfigForm($this);
                    break;

                default :
                    if( !$this  -> _oDb -> getExtraParam('is_configured') ) {
                        $aMenu['set_config']['active'] = 1;
                        $sPageTitle   = _t('_bx_data_migration_configure');
                        $sPageContent = $this -> _oTemplate -> getConfigForm($this);
                    }
                    else {
                        $aMenu['data_migration']['active'] = 1;
                         // get modules page;
                        $sPageTitle = _t('_bx_data_migration_choose_data');
                        $sPageContent = $this -> getModulesList();
                    }
                    break;
            }

            echo $this -> _oTemplate -> adminBlock ($sPageContent, $sPageTitle, $aMenu);
            $this -> _oTemplate->pageCodeAdmin( _t('_bx_data_migration_module') );
        }

        /**
         * Function will generate list of allowed modules;
         *
         * @return : (text) - html presentation data;
         */
        function getModulesList()
        {
            $aProccessed  = array();
            $aStatusTexts = array();

            $aLanguageKeys = array(
                'in_procces'      => _t('_bx_data_migration_in_process'),
                'transferred'     => _t('_bx_data_migration_transferred'),
                'error_transfer'  => _t('_bx_data_migration_error_transferred'),
                'queued'          => _t('_bx_data_migration_not_started'),
            );

            // check all modules;
            foreach($this -> aMigrationModules as $sModule => $aItems)
            {
               // define the transfer status;
               $sTransferStatus     = $this -> _oDb -> getTransferStatus($aItems['module_name']);
               $sTransferStatusText = $this -> _oDb -> getTransferStatusText($aItems['module_name']);

               if($sTransferStatusText) {
                   $aStatusTexts[$aItems['module_name']] = $sTransferStatusText;
               }
               else if($sTransferStatus){
                   $aStatusTexts[$aItems['module_name']] = $aLanguageKeys['in_procces'];
               }

               switch($sTransferStatus) {
                   case 'not_started' :
                        $sModuleTransferred = ' (' . $aLanguageKeys['queued'] . ') ';
                        break;

                   case 'finished' :
                        $sModuleTransferred = ' (' . $aLanguageKeys['transferred'] . ') ';
                        break;

                  case 'started' :
                        $sModuleTransferred = ' (' . $aLanguageKeys['in_procces'] . ') ';
                        break;

                   case 'error' :
                        $sModuleTransferred = ' (' . $aLanguageKeys['error_transfer'] . ') ';
                        break;

                   default :
                        $sModuleTransferred = '';
               }

               if($aItems['is_module'] && $aItems['module_name']) {
                   // check allowed module;
                   if( $this -> _oDb -> isModule($aItems['module_name']) ){
                      $aProccessed[ $aItems['module_name'] ] = $aItems['module_name'] . $sModuleTransferred;
                   }
               }

               if( !$aItems['is_module'] ) {
                   $aProccessed[ $sModule ] = $sModule . $sModuleTransferred;;
               }
            }

            return $this -> _oTemplate -> getModulesList($aProccessed, $aStatusTexts, $this);
        }

        /**
         * Function will create new config;
         *
         * @param  : $sPath (string) - path to old dolphin;
         * @return : (string) - call back message;
         */
        function createConfig($sPath)
        {
            $isDbDefined = false;
            $isDirDefined = false;

            $sPath = stripslashes($sPath);

            if ( substr($sPath, strlen($sPath) - 1 ) != DIRECTORY_SEPARATOR ) {
                $sPath .= DIRECTORY_SEPARATOR;
            }

            // get all seeting from file;
            $sDataFile = file_get_contents($sPath . 'inc' . DIRECTORY_SEPARATOR . 'header.inc.php');
            $aConfig   = array();

            // get db values;
            $aDbValues = array();
            preg_match_all("/db\[\'([a-z]+)\'\].+=.+\'(.*)\'/Ui", $sDataFile, $aDbValues);
            if( is_array($aDbValues) ) {
                foreach($aDbValues[1] as $iKey => $sParamName)
                {
                   $aConfig[$sParamName] = $aDbValues[2][$iKey];
                   $isDbDefined = true;
                }
            }

            // get Dir values;
            $aDirValues = array();
            preg_match_all("/dir\[\'([a-z]+)\'\].+=.+\"(.*)\"/Ui", $sDataFile, $aDirValues);

            $sRootDir     = '';
            $sRootDirInc  = '';

            if( is_array($aDirValues) ) {
                foreach($aDirValues[1] as $iKey => $sParamName)
                {
                   $aConfig[$sParamName] = $aDirValues[2][$iKey];
                   if($sParamName == 'root') {
                       $sRootDir = $aDirValues[2][$iKey];
                   }

                   if($sParamName == 'classes') {
                       $sRootDirInc = $aDirValues[2][$iKey];
                   }

                   $isDirDefined = true;
                }
            }

            // replace with value;
            if($isDirDefined && $isDbDefined) {
                foreach($aConfig as $sKey => $sParamName)
                {
                    $aConfig[$sKey] = str_replace('{$dir[\'root\']}', $sRootDir, $aConfig[$sKey]);
                    $aConfig[$sKey] = str_replace('{$dir[\'inc\']}',  $sRootDir . 'inc/', $aConfig[$sKey]);
                    $this -> _oDb -> createConfig( array('config_' . $sKey => $aConfig[$sKey]) );
                }

                $this -> _oDb -> createConfig( array('is_configured' => 'yes') );
            }
            else {
                return MsgBox( _t('_bx_data_migration_was_not_defined') );
            }

            return MsgBox( _t('_bx_data_migration_was_defined') );
        }
    }
