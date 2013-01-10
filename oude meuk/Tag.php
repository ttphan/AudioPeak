<?php
require_once 'AbstractItem.php';

class Tag extends AbstractItem
{
	/**
	 * tag name
	 *
	 * @access protected
	 * @var string
	 */
	protected $name = null;
	/**
	 * the number occurences of this tag on last.fm
	 *
	 * @access protected
	 * @var int
	 */
	protected $count = 0;
	/**
	 * the scaled count to [0 .. 1]
	 *
	 * @access protected
	 * @var decimal
	 */
	protected $scaledCount;
	/**
	 * url to the last.fm tag page
	 *
	 * @access protected
	 * @var string
	 */
	protected $url = '';
	
	protected $trackLimit = 30;
	
	/**
	 *  Class constructor
	 *
	 *  @param array $arr array with last.fm api track data
	 */
	public function __construct(array $arr)
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
		$this->count 		= $arr['count'];
		if(array_key_exists('url', $arr)) {
			$this->url 			= $arr['url'];
		}
	}
	
	/**
	 * Split this tag into multiple tags based on 
	 * individual words
	 * 
	 * @return TagList
	 */
	public function getSplit()
	{
		$chunks = array_merge( explode(' ', $this->name), explode('-', $this->name));
		$res = new TagList();
		if(sizeof($chunks) > 1) {
			foreach($chunks as $chunk) {
				$res->add(new Tag(array(
						'name'  => $chunk, 
						'count' => round($this->count / sizeof($chunks)), 
						'url'   => $this->url)));
			}
		}
		
		return $res;
	}
	
	/**
	 * Get the tag's top tracks
	 * 
	 * @return TrackList
	 */
	public function getTopTracks()
	{
		$trackClass = $this->apiClass->getPackage($this->auth, 'tag', $this->config);
			
		$methodVars = array(
				'tag' => $this->getName(),
				'limit' => $this->trackLimit
		);
		
		if ($results = $trackClass->getTopTracks($methodVars) ) {
			$res = new TrackList();
			$res->fromArray($results);
			return $res;
		} else {
			$this->error("no songs for this tag found");
		}
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getCount()
	{
		return $this->count;
	}
	
	public function setCount($val)
	{
		$this->count = $val;
	}
	
	public function getScaledCount()
	{
		return $this->scaledCount;
	}
	
	public function setScaledCount($c)
	{
		$this->scaledCount = $c;
	}
	
	public function getUrl()
	{
		return $this->url;
	}

}