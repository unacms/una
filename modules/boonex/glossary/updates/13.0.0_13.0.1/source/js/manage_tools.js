/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary
 * @ingroup     UnaModules
 *
 * @{
 */

function BxGlsrManageTools(oOptions)
{
    BxBaseModTextManageTools.call(this, oOptions);

    this._sObjName = oOptions.sObjName == undefined ? 'oBxGlsrManageTools' : oOptions.sObjName;
}

BxGlsrManageTools.prototype = Object.create(BxBaseModTextManageTools.prototype);
BxGlsrManageTools.prototype.constructor = BxGlsrManageTools;

/** @} */
