/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

function bx_classes_mark_as_completed (iClassId)
{
    var sActionUrl = sUrlRoot + 'modules/index.php?r=classes/mark_class_as_completed/' + iClassId;
    $.post(sActionUrl, function (sData) {
        if (sData.length)
            bx_alert(sData);
        else
            document.location.reload();
    });
}

function bx_classes_show_module_add_form(iContextProfileId)
{
    var sActionUrl = sUrlRoot + 'modules/index.php?r=classes/add_module/' + iContextProfileId + '/html';
    $(window).dolPopupAjax({url: sActionUrl, closeOnOuterClick: false, removeOnClose: true});
}

function bx_classes_show_module_edit_form(iContextProfileId, iModuleId)
{
    var sActionUrl = sUrlRoot + 'modules/index.php?r=classes/edit_module/' + iContextProfileId + '/' + iModuleId;
    $(window).dolPopupAjax({url: sActionUrl, closeOnOuterClick: false, removeOnClose: true});
}

function bx_classes_delete_module(iContextProfileId, iModuleId)
{
    var sActionUrl = sUrlRoot + 'modules/index.php?r=classes/delete_module/' + iContextProfileId + '/' + iModuleId;
    bx_confirm(_t('_Are_you_sure'), function () {
        $.post(sActionUrl, function (sData) {
            if (sData.length)
                bx_alert(sData);
            else
                loadDynamicBlockAuto('.bx-course-controls');
        });
    });
}

function bx_classes_reorder_classes(iProfileId, bEnable)
{
    var eCourse = jQuery('.bx-course-classes .bx-form-advanced-wrapper');
    var eSortable = jQuery('.bx-course-classes .bx-form-input-wrapper-custom');
    var sHandle = '<i class="handle sys-icon move bx-def-padding-right"></i>';
    if (!eSortable.find('.bx-classes-class').size())
        return;
    if (bEnable) {
        jQuery('.bx-course-classes, .bx-course-controls').addClass('bx-course-reordering-mode');
        if (!eCourse.find('.bx-classes-class-title .handle').size())
            eSortable.find('.bx-classes-class-title').prepend(sHandle);
        if (eSortable.hasClass('ui-sortable-disabled')) {
            eSortable.sortable('enable');
        }
        else if (!eSortable.hasClass('ui-sortable')) {
            eSortable.sortable({
                items:'.bx-classes-class',
                connectWith: ".bx-form-input-wrapper-custom",
                handle: '.handle',
                placeholder: 'bx-classes-placeholder bx-def-font-h3 sys-icon arrow-right col-green1 sys-colored',
                forcePlaceholderSize: true, 
                dropOnEmpty: true,
                stop: function(oEvent, oUi) {
                    var a = eCourse.find('.bx-form-section-wrapper > .bx-form-section'); 
                    var s = '';
                    var i = 0;
                    $.each(a, function () {
                        var aa = $(this).find('.bx-classes-class');
                        var iModuleId = $(this).attr('id').replace('module_', '');
                        s += '&classes_order_' +iModuleId + '=';
                        $.each(aa, function () {
                            s += $(this).attr('id').replace('class_', '') + ',';
                        });
                        s = s.replace(/,$/, '');
                    });
                    var sActionUrl = sUrlRoot + 'modules/index.php?r=classes/reorder_classes/' + iProfileId + s;
                    $.post(sActionUrl);
                }

            });
        }
        glBxClassesSortableClassesInitialized = true;
    }
    else if (!bEnable && 'undefined' !== typeof(glBxClassesSortableClassesInitialized)) {
        eSortable.sortable('destroy');
        eSortable.find('.bx-classes-class .handle').remove();
        jQuery('.bx-course-classes, .bx-course-controls').removeClass('bx-course-reordering-mode');
        delete glBxClassesSortableClassesInitialized;
    }
}

function bx_classes_reorder_modules(iProfileId, bEnable)
{
    var eCourse = jQuery('.bx-course-classes .bx-form-advanced-wrapper');
    var sHandle = '<i class="handle sys-icon move bx-def-padding-leftright"></i>';
    if (!eCourse.find('.bx-form-section-wrapper').size())
        return;
    if (bEnable) {
        jQuery('.bx-course-classes, .bx-course-controls').addClass('bx-course-reordering-mode');
        if (!eCourse.find('.bx-form-section-title .handle').size())
            eCourse.find('.bx-form-section-title').prepend(sHandle);
        if (eCourse.hasClass('ui-sortable-disabled')) {
            eCourse.sortable('enable');
        }
        else if (!eCourse.hasClass('ui-sortable')) {
            eCourse.sortable({
                items:'.bx-form-section-wrapper',
                handle: '.handle',
                stop: function(oEvent, oUi) {
                    var a = eCourse.find('.bx-form-section-wrapper > .bx-form-section'); 
                    var s = '';
                    var i = 0;
                    $.each(a, function () {                        
                        s += '&modules_order[]=' + $(this).attr('id').replace('module_', '');
                    });
                    var sActionUrl = sUrlRoot + 'modules/index.php?r=classes/reorder_modules/' + iProfileId + s;
                    $.post(sActionUrl);
                }

            });
        }
        glBxClassesSortableModulesInitialized = true;
    }
    else if (!bEnable && 'undefined' !== typeof(glBxClassesSortableModulesInitialized)) {
        eCourse.sortable('destroy');
        eCourse.find('.bx-form-section-title .handle').remove();
        jQuery('.bx-course-classes, .bx-course-controls').removeClass('bx-course-reordering-mode');
        delete glBxClassesSortableModulesInitialized;
    }
}

/** @} */
