<?php

// TODO: decide later what to do with twig* classes and module, it looks like they will stay and 'complex' modules will be still based on it, but some refactoring is needed

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

bx_import('BxDolProfileFields');

/**
 * Base send broadcast message class for modules like events/groups/store
 */
class BxDolTwigFormBroadcast extends BxTemplFormView {

    function BxDolTwigFormBroadcast ($sCaptionMsgTitle, $sErrMsgTitle, $sCaptionMsgBody, $sErrMsgBory) {

        $aCustomForm = array(

            'form_attrs' => array(
                'name'     => 'form_broadcast',
                'action'   => '',
                'method'   => 'post',
            ),

            'params' => array (
                'db' => array(
                    'submit_name' => 'submit_form',
                ),
            ),

            'inputs' => array(
                'title' => array(
                    'type' => 'text',
                    'name' => 'title',
                    'caption' => $sCaptionMsgTitle,
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(3,100),
                        'error' => $sErrMsgTitle,
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),

                'message' => array(
                    'type' => 'textarea',
                    'name' => 'message',
                    'caption' => $sCaptionMsgBody,
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(10,64000),
                        'error' => $sErrMsgBory,
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),

                'Submit' => array (
                    'type' => 'submit',
                    'name' => 'submit_form',
                    'value' => _t('_Submit'),
                ),
            ),
        );

        parent::BxTemplFormView ($aCustomForm);
    }
}

?>
