<?php
require_once 'AbstractItem.php';

class Track extends AbstractItem
{
	/**
	 *  name of the track
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $name;
	/**
	 *  track artist name
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $artist;
	/**
	 *  url of the last.fm page of this track
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $url;
	/**
	 *  track is streamable
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $streamable;
	/**
	 *  full track is available
	 *
	 *  @access protected
	 *  @var boolean
	 */
	protected $fulltrack;
	/**
	 *  number of listeners on last.fm
	 *
	 *  @access protected
	 *  @var int
	 */
	protected $listeners;
	/**
	 *  links to the image (small, medium, large)
	 *
	 *  @access protected
	 *  @var array
	 */
	protected $image = array();
	
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
	 *  Class constructor
	 *  
	 *  @param array $arr array with last.fm api track data
	 */
	public function __construct(array $arr)
	{
		$this->name 		= $arr['name'];
		$this->artist 		= $arr['artist'];
		$this->url 			= $arr['url'];
		$this->streamable 	= (bool) $arr['streamable'];
		$this->fulltrack 	= (bool) $arr['fulltrack'];
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
	public function getTags()
	{
		$trackClass = $this->apiClass->getPackage($this->auth, 'track', $this->config);
			
		$methodVars = array(
				'artist' => $this->artist,
				'track'  => $this->name
		);
			
		if ($results = $trackClass->getTopTags($methodVars) ) {
			$res = new TagList();
			$res->fromArray($results);
			return $res;
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
}