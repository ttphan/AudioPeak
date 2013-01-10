<?php
require_once 'lastfmapi/lastfmapi.php';

final class Auth 
{
		
	public function getAuth()
	{
		return new lastfmApiAuth('setsession', array(
				'apiKey' 		=> '47b783c9f6afa0baf7b13d68ed3e8024',
				'secret' 		=> '172b2450c6d5fae45ffd21bf2d3dcf50',
				'username' 		=> 'schneppy',
				'sessionKey' 	=> 'audiopeak',
				'subscriber' 	=> '' )
		);
	}
	
	public function getApi()
	{
		return new lastfmApi();
	}
	
	public function getConfig()
	{
		return array(
			'enabled' 		=> true,
			'path' 			=> 'lastfmapi/',
			'cache_length' 	=> 1800
		);
	}
}