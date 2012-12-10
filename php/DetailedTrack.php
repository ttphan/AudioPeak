<?php
require_once 'Track.php';

class DetailedTrack extends Track
{
	/**
	 * @var int
	 */
	protected $id;
	/**
	 * @var int
	 */
	protected $duration;
	/**
	 * @var int
	 */
	protected $playcount;
	/**
	 * @var array
	 */
	protected $artist;
	/**
	 * @var array
	 */
	protected $album;
	/**
	 * @var array
	 */
	protected $toptags;
	/**
	 * @var array
	 */
	protected $wiki;
	
	
	/**
	 *  Class constructor (with a workaround for overloading)
	 *  
	 *  @param unknown $unknown array with last.fm api track data
	 *  @param string $title (optional)
	 */
	public function __construct($artist, $title)
	{
		parent::__construct();
		
		$trackClass = $this->apiClass->getPackage($this->auth, 'track', $this->config);
			
		$methodVars = array(
				'artist' => $artist,
				'track'  => $title
		);
		
		if ($results = $trackClass->getInfo($methodVars) ) {
			$this->fromArray($results);
		} else {
			$this->error("error getting track info");
		}
	}
	
	protected function fromArray(array $arr)
	{
		parent::fromArray($arr);
		
		$this->id = $arr['id'];
		$this->duration = $arr['duration'];
		$this->playcount = $arr['playcount'];
		$this->artist = $arr['artist'];
		$this->album = $arr['album'];
		$this->toptags = $arr['toptags'];
		$this->wiki = $arr['wiki'];
		
	}
	
	/**
	* @return string
	* 
	*/
	public function getWiki()
	{
		return $this->wiki;
	}
	
	/**
	 * @return string
	 *
	 */
	 public function getTopTags()
	 {
	 	return $this->toptags;
	 }
	 
	/**
	* @return array
	* 
	*/
	public function getAlbum()
	{
	return $this->album;
	}
	
}