<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    HostingAPI Hosting API
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_ELASTICSEARCH_LOG', 1);

/**
 * ElasticSearch API
 */
class BxElsApi extends BxDol
{
    protected $_sError = false;
    protected $_iTimeout = 30;
    protected $_sApiUrl = 'http://localhost:9200';

    public function __construct()
    {
        $this->_sApiUrl = getParam('bx_elasticsearch_api_url');
    }

    public function searchData($sIndex, $sTerm) 
    {
        return $this->api("/$sIndex/_search?q=" . $sTerm);
    }

    public function getData($sIndex, $sType, $iContentId) 
    {
        return $this->api("/$sIndex/$sType/$iContentId");
    }

    public function indexData($sIndex, $sType, $iContentId, $aData) 
    {
        return $this->api("/$sIndex/$sType/$iContentId", $aData, 'put');
    }

    public function updateData($sIndex, $sType, $iContentId, $aData)
    {
        return $this->api("/$sIndex/$sType/$iContentId", $aData, 'post');
    }

    public function deleteData($sIndex, $sType, $iContentId)
    {
        return $this->api("/$sIndex/$sType/$iContentId", array(), 'delete');
    }

    public function getErrorMsg() 
    {
        return $this->_sError;
    }
    
    public function api($sAction, $aData = array(), $sMetod = 'get', $bJsonResponse = true) 
    {
        if (!$this->_sApiUrl) {
            $this->_sError = 'Host isn\'t defined';
            return null;
        }

        if (!$sAction) {
            $this->_sError = 'Action isn\'t defined';
            return null;
        }        

        $sUrl = bx_append_url_params($this->_sApiUrl . $sAction, array(
            'format' => 'json',
            'human' => false,
        ));

        $sHttpCode = 200;
        $s = $this->curl($sUrl, $aData, $sMetod, $sHttpCode);

        $this->log($s);

        if ($sHttpCode >= 300 || $sHttpCode < 200) {
            $this->_sError = 'Action('.$sAction.') returned status - ' . $sHttpCode;
            return null;
        }

        // return JSON response
        if ($s && $bJsonResponse) {
            if (null === ($a = json_decode($s, true))) {
                $this->_sError = 'Can not decode response';
                return null;
            }
            return $a;
        }
    
        return $s;
    }

    protected function curl ($sUrl, $aData = array(), $sMethod = 'get', &$sHttpCode = null, $aBasicAuth = array()) 
    {
        $rConnect = curl_init();

        curl_setopt($rConnect, CURLOPT_USERAGENT, 'UNA ' . BX_DOL_VERSION);
        curl_setopt($rConnect, CURLOPT_URL, $sUrl);
        curl_setopt($rConnect, CURLOPT_HEADER, NULL === $sHttpCode ? false : true);
        curl_setopt($rConnect, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rConnect, CURLOPT_VERBOSE, 0);
        curl_setopt($rConnect, CURLOPT_HEADER, 0);

        curl_setopt($rConnect, CURLOPT_CONNECTTIMEOUT, $this->_iTimeout);
        curl_setopt($rConnect, CURLOPT_TIMEOUT, $this->_iTimeout);

        if (!ini_get('open_basedir'))
            curl_setopt($rConnect, CURLOPT_FOLLOWLOCATION, 1);

        if ($aBasicAuth)
            curl_setopt($rConnect, CURLOPT_USERPWD, $aBasicAuth['user'] . ':' . $aBasicAuth['password']);

        if ('get' != $sMethod)
            curl_setopt($rConnect, CURLOPT_CUSTOMREQUEST, strtoupper($sMethod));

        if ('post' == $sMethod)
            curl_setopt($rConnect, CURLOPT_POST, true);

        if ($aData) {
            curl_setopt($rConnect, CURLOPT_POSTFIELDS, json_encode($aData));
            curl_setopt($rConnect, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        }

        $sResult = curl_exec($rConnect);

        if (NULL !== $sHttpCode)
            $sHttpCode = curl_getinfo($rConnect, CURLINFO_HTTP_CODE);

        curl_close($rConnect);

        return $sResult;
    }

    protected function log ($mixed, $sProvider = '')
    {
        if (!defined('BX_ELASTICSEARCH_LOG') || !constant('BX_ELASTICSEARCH_LOG'))
            return;
        $fn = BX_DIRECTORY_PATH_ROOT . "logs/elasticsearch.log";
        $f = @fopen ($fn, 'a');
        if (!$f)
            return;
        if (is_array($mixed))
            fwrite ($f, date(DATE_RFC822) . "\n" . print_r($mixed, true) . "\n");
        else
            fwrite ($f, date(DATE_RFC822) . "\t" . $mixed . "\n");
        fclose ($f);
    }
}

/** @} */
