/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Lucid Lucid template
 * @ingroup     UnaModules
 *
 * @{
 */

function BxLucidMenuMoreAuto(oOptions) {
    BxDolMenuMoreAuto.call(this, oOptions);
}

BxLucidMenuMoreAuto.prototype = Object.create(BxDolMenuMoreAuto.prototype);
BxLucidMenuMoreAuto.prototype.constructor = BxLucidMenuMoreAuto;

BxLucidMenuMoreAuto.prototype.init = function(bForceInit)
{
    BxDolMenuMoreAuto.prototype.init.call(this);

    var $this = this;

    $(document).ready(function() {
        $this.update();
    });

    $('#bx-toolbar').find('img').bind('load', function() {
        $this.update();
    });
};