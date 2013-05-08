<?php
class PPConnectionManager
{
	/**
	 * reference to singleton instance
	 * @var PPConnectionManager
	 */
	private static $instance;

	private function __construct()
	{
	}

	public static function getInstance() {
		if( self::$instance == null ) {
			self::$instance = new PPConnectionManager();
		}
		return self::$instance;
	}

	/**
	 * This function returns a new PPHttpConnection object
	 */
	public function getConnection($httpConfig) {
		$configMgr = PPConfigManager::getInstance();
		if( ($configMgr->get("http.ConnectionTimeOut")) ) {
			$httpConfig->setHttpTimeout( $configMgr->get("http.ConnectionTimeOut") );
		}
		if( $configMgr->get("http.Proxy") ) {
			$httpConfig->setHttpProxy( $configMgr->get("http.Proxy") );
		}
		if( $configMgr->get("http.Retry") ) {
			$retry = $configMgr->get("http.Retry");
			$httpConfig->setHttpRetryCount($retry ) ;
		}
		
		return new PPHttpConnection($httpConfig);
	}

}
