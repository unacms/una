<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxVideosDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function updateEntries($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql);
    }

    public function getEmbedProviders() {
        return $this->getAll("SELECT * FROM `bx_videos_embeds_providers`");
    }

    public function updateOEmbedProviders() {
        $CNF = &$this->_oConfig->CNF;

        $aProviders = @json_decode(bx_file_get_contents($CNF['OEMBED_PROVIDERS_IMPORT_URL']), true);
        if (!$aProviders) return;

        $this->query('TRUNCATE TABLE `bx_videos_oembed_endpoints`');
        $this->query('TRUNCATE TABLE `bx_videos_oembed_schemes`');

        foreach ($aProviders as $aProvider) {
            foreach ($aProvider['endpoints'] as $aEndpoint) {
                if (!isset($aEndpoint['url']) || empty($aEndpoint['url']) || !isset($aEndpoint['schemes']) || empty($aEndpoint['schemes'])) continue;

                $aEndpoint['url'] = str_replace('{format}', 'json', $aEndpoint['url']);
                $this->query('INSERT INTO `bx_videos_oembed_endpoints` SET `url` = :url', ['url' => $aEndpoint['url']]);
                $iEndpointId = $this->lastId();

                $aRows = [];
                foreach ($aEndpoint['schemes'] as $sScheme) {
                    $aRows[] = "({$iEndpointId}, ".$this->escape(str_replace('*', '%', $sScheme)).")";
                }
                if ($aRows)
                    $this->query('INSERT INTO `bx_videos_oembed_schemes` (`endpoint_id`, `url`) VALUES '.implode(', ', $aRows));
            }
        }
    }

    public function getOEmbedEndpoint($sLink) {
        if (!$this->getOne("SELECT COUNT(*) FROM `bx_videos_oembed_endpoints`"))
            $this->updateOEmbedProviders();

        return $this->getOne("
            SELECT `bx_videos_oembed_endpoints`.`url` 
            FROM `bx_videos_oembed_endpoints`
            JOIN `bx_videos_oembed_schemes` ON `bx_videos_oembed_endpoints`.`id` = `bx_videos_oembed_schemes`.`endpoint_id`
            WHERE :link LIKE `bx_videos_oembed_schemes`.`url`
            LIMIT 1
        ", ['link' => $sLink]);
    }
}

/** @} */
