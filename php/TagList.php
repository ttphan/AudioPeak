<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Track.php';
require_once 'Tag.php';
require_once 'Auth.php';

/**
 *  track objects of this class are iterable in a foreach loop
 *  thank IteratorAggregate for this
 */
class TagList implements IteratorAggregate
{
	/**
	 *  Store all the tag objects
	 *
	 *  @access private
	 *  @var array
	 */
	private $tags = array();
	/**
	 *  the number of tracks
	 *  
	 *  @access private
	 *  @var int
	 */
	private $count = 0;
	
	/**
	 *  the number of results to be returned
	 *  from the last.fm api
	 *
	 *  @access protected
	 *  @var int
	 */
	protected $numResults = 50;
	
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
	 */
	public function __construct()
	{
		$this->auth 	= Auth::getAuth();
		$this->apiClass = Auth::getApi();
		$this->config	= Auth::getConfig();
	}
	
	/**
	 *  Required by IteratorAggregate
	 *  
	 *  @return ArrayIterator
	 */
	public function getIterator() 
	{
		return new ArrayIterator($this->tags);
	}
	
	/**
	 *  get the number of tags in the list
	 *  
	 *  @return int
	 */
	public function size()
	{
		return $this->count;
	}
	
	/**
	 *  Add a tag to this list and rescale
	 *  
	 *  @param Tag $value
	 */
	public function add($value) 
	{
		$this->tags[$this->count++] = $value;
		$this->scaleCounts();
	}
	
	/**
	 *  Scale all the tag counts to [0 .. 1]
	 *  similar to tag popularity / relevance
	 */
	protected function scaleCounts()
	{
		$maxCount = 0;
		
		if($this->size() > 0)
		{
			// get max value
			foreach ($this->tags as $num => $tag) 
				if($tag->getCount() > $maxCount) 
					$maxCount = $tag->getCount();
			
			// scale everything relative to the max tag-count in [0 ... 1]
			foreach ($this->tags as $tags)
			{
				$scaled = $tag->getCount() / $maxCount;
				$tag->setScaledCount($scaled);
			}
		}
	}
	
	/**
	 * Clear this taglist of any data
	 */
	public function reset() 
	{
		$track = array();
		$count = 0;
	}
	
	/**
	 * Search for a tag
	 *
	 * @param  string $query
	 */
	public function search($query = '')
	{
		if($query != '')
		{
			$tagClass = $this->apiClass->getPackage($this->auth, 'tag', $this->config);
			
			$methodVars = array(
					'tag' => $query,
					'limit' => $this->numResults
			);
	
			if ($results = $tagClass->search($methodVars) ) {
				$this->fromArray($results);
			} else {
				// TODO: proper error page / message
				die('<b>Error '.$this->tagClass->error['code'].' - </b><i>'.$this->tagClass->error['desc'].'</i>');
			}
		} else {
			$this->error();
		}
	}
	
	/**
	 * Composes this tracklist from an api response array
	 *
	 * @param array $arr
	 */
	public function fromArray($arr)
	{
		foreach($arr['results'] as $tagData) 
		{
			$addTag = new Tag($tagData);
			$this->add($addTag);
		}
	}
	
	/**
	 * Compute and display an error
	 *
	 * @todo complete this
	 */
	protected function error()
	{
		// TODO: proper error page / message
		die('<b>Error '.$this->trackClass->error['code'].' - </b><i>'.$this->trackClass->error['desc'].'</i>');
	}
}