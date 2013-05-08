<?php
interface IPPHandler {
	/**
	 * 
	 * @param PPHttpConfig $httpConfig
	 * @param PPRequest $request 
	 */
	public function handle($httpConfig, $request);
}