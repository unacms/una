<?php

    class BxSimpleMessengerResponse extends BxDolAlertsResponse
    {
        function response(&$o)
        {
            if ( $o -> sUnit == 'profile' ) {
                switch ( $o -> sAction ) {
                    case 'delete' :
                       $oModule = BxDolModule::getInstance('bx_simple_messenger');
                       $oModule -> _oDb -> deleteAllMessagesHistory($o -> iObject);
                    break;
                }
            }
        }
    }