<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Lucid',
    'version_from' => '11.0.6',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/lucid/updates/update_11.0.6_12.0.0/',
    'home_uri' => 'lucid_update_1106_1200',

    'module_dir' => 'boonex/lucid/',
    'module_uri' => 'lucid',

    'db_prefix' => 'bx_lucid_',
    'class_prefix' => 'BxLucid',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Boonex Lucid Template',

    /**
     * Files Section
     */
    'delete_files' => array(
        'data/template/bx_timeline/type_repost.html',
        'data/template/studio/scripts/BxTemplStudioAudit.php',
        'data/template/studio/scripts/BxTemplStudioBadges.php',
        'data/template/studio/scripts/BxTemplStudioBadgesGrid.php',
        'data/template/studio/scripts/BxTemplStudioBuilderPage.php',
        'data/template/studio/scripts/BxTemplStudioBuilderPageUploaderHTML5.php',
        'data/template/studio/scripts/BxTemplStudioBuilderPageUploaderSimple.php',
        'data/template/studio/scripts/BxTemplStudioConfig.php',
        'data/template/studio/scripts/BxTemplStudioDashboard.php',
        'data/template/studio/scripts/BxTemplStudioDesign.php',
        'data/template/studio/scripts/BxTemplStudioDesigner.php',
        'data/template/studio/scripts/BxTemplStudioDesigns.php',
        'data/template/studio/scripts/BxTemplStudioDesignServices.php',
        'data/template/studio/scripts/BxTemplStudioForms.php',
        'data/template/studio/scripts/BxTemplStudioFormsCategories.php',
        'data/template/studio/scripts/BxTemplStudioFormsDisplays.php',
        'data/template/studio/scripts/BxTemplStudioFormsField.php',
        'data/template/studio/scripts/BxTemplStudioFormsFields.php',
        'data/template/studio/scripts/BxTemplStudioFormsForms.php',
        'data/template/studio/scripts/BxTemplStudioFormsLabels.php',
        'data/template/studio/scripts/BxTemplStudioFormsPreLists.php',
        'data/template/studio/scripts/BxTemplStudioFormsPreValues.php',
        'data/template/studio/scripts/BxTemplStudioFormsSearchFields.php',
        'data/template/studio/scripts/BxTemplStudioFormsSearchForms.php',
        'data/template/studio/scripts/BxTemplStudioFormView.php',
        'data/template/studio/scripts/BxTemplStudioFunctions.php',
        'data/template/studio/scripts/BxTemplStudioGrid.php',
        'data/template/studio/scripts/BxTemplStudioGridNavigation.php',
        'data/template/studio/scripts/BxTemplStudioGridStorages.php',
        'data/template/studio/scripts/BxTemplStudioLanguage.php',
        'data/template/studio/scripts/BxTemplStudioLanguages.php',
        'data/template/studio/scripts/BxTemplStudioLauncher.php',
        'data/template/studio/scripts/BxTemplStudioMenu.php',
        'data/template/studio/scripts/BxTemplStudioMenuTop.php',
        'data/template/studio/scripts/BxTemplStudioModule.php',
        'data/template/studio/scripts/BxTemplStudioModules.php',
        'data/template/studio/scripts/BxTemplStudioNavigation.php',
        'data/template/studio/scripts/BxTemplStudioNavigationImport.php',
        'data/template/studio/scripts/BxTemplStudioNavigationItems.php',
        'data/template/studio/scripts/BxTemplStudioNavigationMenus.php',
        'data/template/studio/scripts/BxTemplStudioNavigationSets.php',
        'data/template/studio/scripts/BxTemplStudioPage.php',
        'data/template/studio/scripts/BxTemplStudioPermissions.php',
        'data/template/studio/scripts/BxTemplStudioPermissionsActions.php',
        'data/template/studio/scripts/BxTemplStudioPermissionsLevels.php',
        'data/template/studio/scripts/BxTemplStudioPolyglot.php',
        'data/template/studio/scripts/BxTemplStudioPolyglotEtemplates.php',
        'data/template/studio/scripts/BxTemplStudioPolyglotKeys.php',
        'data/template/studio/scripts/BxTemplStudioSettings.php',
        'data/template/studio/scripts/BxTemplStudioSettingsServices.php',
        'data/template/studio/scripts/BxTemplStudioSettingsUploaderHTML5.php',
        'data/template/studio/scripts/BxTemplStudioStorages.php',
        'data/template/studio/scripts/BxTemplStudioStoragesFiles.php',
        'data/template/studio/scripts/BxTemplStudioStoragesImages.php',
        'data/template/studio/scripts/BxTemplStudioStore.php',
        'data/template/studio/scripts/BxTemplStudioUploaderCropCover.php',
        'data/template/studio/scripts/BxTemplStudioWidgets.php',
        'data/template/studio/scripts/',
        'data/template/studio/'
    ),
);
