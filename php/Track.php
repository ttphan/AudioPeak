<?php
require_once 'AbstractItem.php';
require_once 'MySQL.php';


class Track extends AbstractItem
{
	/**
	 *  name of the track
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $name = null;
	/**
	 *  track artist name
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $artist = null;
	/**
	 *  url of the last.fm page of this track
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $url = null;
	/**
	 *  track is streamable
	 *
	 *  @access protected
	 *  @var string
	 */
	protected $streamable = null;
	/**
	 *  full track is available
	 *
	 *  @access protected
	 *  @var boolean
	 */
	protected $fulltrack = null;
	/**
	 *  number of listeners on last.fm
	 *
	 *  @access protected
	 *  @var int
	 */
	protected $listeners = null;
	/**
	 *  links to the image (small, medium, large)
	 *
	 *  @access protected
	 *  @var array
	 */
	protected $image = null;
	
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
	
	protected function fromArray(array $arr)
	{
		$this->name 		= $arr['name'];
		$this->artist 		= $arr['artist'];
		$this->url 			= $arr['url'];
		$this->streamable 	= (bool) $arr['streamable'];
		$this->fulltrack 	= (bool) $arr['fulltrack'];
		if(array_key_exists('listeners', $arr)) {
			$this->listeners 	= $arr['listeners'];
		}
		if(array_key_exists('image', $arr))
		{
			$this->image     = $arr['image'];
		}
		else
		{
			$this->image    = $arr['album']['image'];
		}		
	}
	
	/**
	 * Get the top tags
	 *
	 * @return TagList
	 */
	public function getTags()
	{	
		$dbase = new Database();
		$result = $dbase->getTags($this);
		if($result === null) {
			return new TagList();
		}
		if(is_array($result)) {
			$res = new TagList();
			$res->FromArray($result);
			return $res;
		}
		else {
			$this->error("error getting the top tags of " . $artist . " - " . $this->name . " from last.fm");
		}
		
		/*$trackClass = $this->apiClass->getPackage($this->auth, 'track', $this->config);
		
		$artist = is_array($this->artist) ? $this->artist['name'] : $this->artist;
		
		$methodVars = array(
				'artist' => $artist,
				'track'  => $this->name
		);
		
		if ($results = $trackClass->getTopTags($methodVars) ) {
			$res = new TagList();
			$res->fromArray($results);
			return $res;
		} else {
			$this->error("error getting the top tags of " . $artist . " - " . $this->name . " from last.fm");
		}*/
		
		
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
	
	/**
	 * @return string
	 */
	public function getImage()
	{
		return $this->image['large'];
	}
}