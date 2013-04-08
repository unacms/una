// TODO: remake according to new design and principles

/***************************************************************************
 *                            Dolphin Web Community Software
 *                              -------------------
 *     begin                : Mon Mar 23 2006
 *     copyright            : (C) 2007 BoonEx Group
 *     website              : http://www.boonex.com
 *
 *
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This is a free software; you can modify it under the terms of BoonEx
 *   Product License Agreement published on BoonEx site at http://www.boonex.com/downloads/license.pdf
 *   You may not however distribute it for free or/and a fee.
 *   This notice may not be removed from the source code. You may not also remove any other visible
 *   reference and links to BoonEx Group as provided in source code.
 *
 ***************************************************************************/

function getTranslations(oSelect) {
    var iLangId = $(oSelect).val();
    var sTemplName = $(oSelect).attr('name').replace('_Language', '');

    $('#adm-email-loading').bx_loading();

    $.post(
        sAdminUrl + 'email_templates.php',
        {
            action: 'get_translations',
            lang_id: iLangId,
            templ_name: sTemplName
        },
        function(oResult) {
            $('#adm-email-loading').bx_loading();

            $("[name='" + sTemplName + "_Subject']").val(oResult.subject);
            $("[name='" + sTemplName + "_Body']").val(oResult.body);
        },
        'json'
    );
}
