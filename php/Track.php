<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Auth.php';

class Track
{
	protected $name;
	protected $artist;
	protected $url;
	protected $streamable;
	protected $fullstrack;
	protected $listeners;
	protected $image = array();
	
	protected $auth;
	protected $apiClass;
	protected $config;
	
	
	public function Track(array $arr)
	{
		$this->name 		= $arr['name'];
		$this->artist 		= $arr['artist'];
		$this->url 			= $arr['url'];
		$this->streamable 	= $arr['streamable'];
		$this->fulltrack 	= $arr['fulltrack'];
		$this->listeners 	= $arr['listeners'];
		$this->image 		= $arr['image'];
		
		$this->auth 		= Auth::getAuth();
		$this->apiClass 	= Auth::getApi();
		$this->config		= Auth::getConfig();
	}
	
	/**
	 * Get the top tags
	 *
	 * @return TagList
	 */
	public function getTopTags()
	{
		$trackClass = $this->apiClass->getPackage($this->auth, 'track', $this->config);
			
		$methodVars = array(
				'artist' => $this->artist,
				'track' => $this->track
		);
			
		if ($results = $trackClass->getTopTags($methodVars) ) {
			$res = new TagList();
			return $res.fromArray($results);
		} else {
			$this->error();
		}
	}
	
	/**
	 * Get all tags
	 *
	 * @return TagList
	 */
	public function getTags()
	{
		$trackClass = $this->apiClass->getPackage($this->auth, 'track', $this->config);

		$methodVars = array(
				'artist' => $this->artist,
				'track' => $this->track
		);

		if ($results = $trackClass->getTags($methodVars) ) {
			$res = new TagList();
			return $res.fromArray($results);
		} else {
			$this->error();
		}
	}
	
	public function getArtist()
	{
		return $this->artist;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getImage()
	{
		return $this->image['large'];
	}
	
	protected function error()
	{
		// TODO: proper error page / message
		die('<b>Error '.$this->trackClass->error['code'].' - </b><i>'.$this->trackClass->error['desc'].'</i>');
	}
}