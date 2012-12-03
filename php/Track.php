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
	 *  Class constructor (with a workaround for overloading)
	 *  
	 *  @param array $arr
	 */
	public function __construct($arr = null)
	{
		$this->auth 		= Auth::getAuth();
		$this->apiClass 	= Auth::getApi();
		$this->config		= Auth::getConfig();
		
		if($arr != null)
			$this->fromArray($arr);
	}
	
	protected function fromArray($arr)
	{
		$this->name 		= $arr['name'];
		$this->artist 		= $arr['artist'];
		$this->url 			= $arr['url'];
		$this->streamable 	= (bool) $arr['streamable'];
		$this->fulltrack 	= (bool) $arr['fulltrack'];
		$this->listeners 	= $arr['listeners'];
		$this->image 		= $arr['image'];
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
	
	/**
	 * @return string
	 */
	public function getArtist()
	{
		return $this->artist;
	}
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
}