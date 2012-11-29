<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Auth.php';

abstract class AbstractItem 
{
	/**
	 * Compute and display an error
	 *
	 * @todo complete this
	 */
	protected function error($msg)
	{
		// TODO: proper error page / message
		die('<b>Error: </b> '.$msg);
	}
}