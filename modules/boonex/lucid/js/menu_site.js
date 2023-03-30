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
    var oImages = $('#bx-toolbar').find('img');

    if(!oImages.length)
        $(document).ready(function() {
            $this.update(true);
        });
    else
        oImages.bind('load', function() {
            setTimeout(function() {
                $this.update(true);
            }, 100);
        });
};