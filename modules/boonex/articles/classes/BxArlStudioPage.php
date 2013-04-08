<?
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

bx_import('BxTemplStudioModule');

class BxArlStudioPage extends BxTemplStudioModule {
    protected $oModule;

    function BxArlStudioPage($sModule = "", $sPage = "") {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance('bx_articles');

        $sName = $oModule->_oConfig->getName();
        $sDirectory = $oModule->_oConfig->getDirectory();
        $this->aMenuItems = array(
            array('name' => 'general', 'icon' => $sName. '@modules/' . $sDirectory . '|std-mi-general.png', 'title' => '_articles_adm_lmi_cpt_general'),
            array('name' => 'editor', 'icon' => $sName. '@modules/' . $sDirectory . '|std-mi-editor.png', 'title' => '_articles_adm_lmi_cpt_editor'),
            array('name' => 'links', 'icon' => $sName. '@modules/' . $sDirectory . '|std-mi-links.png', 'title' => '_articles_adm_lmi_cpt_links')
        );

        parent::BxTemplStudioModule($sModule, $sPage);
    }

    protected function getEditor() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
        	'bx_repeat:blocks' => "Custom page 'Editor' can be here.",
        );
        return $oTemplate->parseHtmlByName('module.html', $aTmplVars);
    }

    protected function getLinks() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
        	'bx_repeat:blocks' => "Custom page 'Useful Links' can be here.",
        );
        return $oTemplate->parseHtmlByName('module.html', $aTmplVars);
    }
}