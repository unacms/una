<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Help Tours Help Tours
 * @ingroup     UnaModules
 *
 * @{
 */

class BxHelpToursTemplate extends BxBaseModGeneralTemplate
{
    public function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getHelpTourCode($aTour, $aHelpTourItems) {
        $this->addJs(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'shepherd/js/|shepherd.min.js');
        $this->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'shepherd/css/|shepherd.css');

        $iShowOnlyItem = isAdmin() && bx_get('help_tour_item') ? intval(bx_get('help_tour_item')) : 0;

        $aHelpTourItemsTmpl = [];
        foreach ($aHelpTourItems as $iCounter => $aItem) {
            if ($iShowOnlyItem && $iShowOnlyItem != $aItem['id']) continue;

            $aStep = [
                'id' => 'bx-help-tour-step-'.$aItem['id'],
                'text' => _t($aItem['text']),
            ];

            if ($aItem['title']) $aStep['title'] = _t($aItem['title']);
            if ($aItem['element']) $aStep['attachTo'] = ['element' => $aItem['element'], 'on' => $aItem['arrow']];

            $aHelpTourItemsTmpl[] = $aStep;
        }

        if (!$aHelpTourItemsTmpl) return;

        return $this->parseHtmlByName('tour.html', [
            'tour_overlay' => $aTour['overlay'] ? 'true' : 'false',
            'steps' => json_encode($aHelpTourItemsTmpl),
            'js_obj' => $this->getTourJsObject(),
            'bx_if:not_preview' => [
                'condition' => !(isAdmin() && (bx_get('help_tour_preview') || bx_get('help_tour_item'))),
                'content' => [
                    'finish_callback_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'tour_seen/',
                    'tour_id' => $aTour['id'],
                ],
            ],
        ]);
    }

    public function getTourJsObject() {
        return 'bx_help_tour_obj';
    }
}

/** @} */
