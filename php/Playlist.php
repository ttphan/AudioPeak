<?php
require_once TagList.php;

class Playlist extends TagList
{
	/**
	 *  Current song playing
	 *  
	 *  @access private
	 *  @var int
	 */
	private $current = 0;
	/**
	 *  repeat a track yes/no
	 *
	 *  @access private
	 *  @var boolean
	 */
	private $repeatTrack = false;
	/**
	 *  repeat the playlist yes/no
	 *
	 *  @access private
	 *  @var boolean
	 */
	private $repeatList  = false;
	
	/**
	 *  Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *  shuffle the tracks
	 */
	public function shuffle()
	{
		shuffle($tracks);
	}
	
	/**
	 *  get the current Track
	 *  
	 *  @return Track
	 */
	public function get()
	{
		return parent::get($current);
	}
	
	/**
	 *  get the next Track and update
	 *  
	 *  @return Track|null
	 */
	public function next()
	{
		$next = $this->repeatTrack ? ++$current : $current;
		if($this->repeatList && parent::size() == $this->current)
			$next = 0;
		else
			return null;
		
		return parent::get($next);
	}
}