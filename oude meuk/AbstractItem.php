<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Auth.php';

abstract class AbstractItem 
{
	/**
	 *  authentication object used for the api
	 *
	 *  @access protected
	 *  @var lastfmApiAuth
	 */
	protected $auth;
	
	/**
	 *  the last.fm api object
	 *
	 *  @access protected
	 *  @var lastfmApi
	 */
	protected $apiClass;
	
	/**
	 *  config array used by the api
	 *
	 *  @access protected
	 *  @var array
	 */
	protected $config;
	
	/**
	 * Compute and display an error
	 *
	 * @todo complete this
	 */
	protected function error($msg)
	{
		die('<b>Error: </b> '.$msg);
	}
	
	/**
	 * construct this from an array
	 * 
	 * @param array $arr
	 */
	protected abstract function fromArray(array $arr);
}