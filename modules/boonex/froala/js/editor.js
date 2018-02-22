/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 * 
 * @{
 */

function bx_editor_insert_img (sEditorId, sImgId, sImgUrl, sClasses) 
{

}

function bx_editor_remove_img (aEditorIds, aMarkers) 
{

}

function bx_editor_on_space_enter (sEditorId)
{
    if (typeof glOnSpaceEnterInEditor !== 'undefined' && glOnSpaceEnterInEditor instanceof Array) {
        for (var i = 0; i < glOnSpaceEnterInEditor.length; i++)
            if (typeof glOnSpaceEnterInEditor[i] === "function")
                glOnSpaceEnterInEditor[i]($(sEditorId).froalaEditor('html.get'));
    }
}

/** @} */
