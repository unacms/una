/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

function BxArtificerMenuMoreAuto(oOptions) {
    BxDolMenuMoreAuto.call(this, oOptions);
}

BxArtificerMenuMoreAuto.prototype = Object.create(BxDolMenuMoreAuto.prototype);
BxArtificerMenuMoreAuto.prototype.constructor = BxArtificerMenuMoreAuto;

BxArtificerMenuMoreAuto.prototype.init = function(bForceInit)
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