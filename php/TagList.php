<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Track.php';
require_once 'Tag.php';
require_once 'Auth.php';

class TagList implements IteratorAggregate
{
	private $tags = array();
	private $count = 0;
	
	protected $numResults = 50;
	
	protected $auth;
	protected $apiClass;
	protected $config;
	
	public function TagList()
	{
		$this->auth 	= Auth::getAuth();
		$this->apiClass = Auth::getApi();
		$this->config	= Auth::getConfig();
	}
	
	// Required by IteratorAggregate
	public function getIterator() 
	{
		return new ArrayIterator($this->tags);
	}
	
	public function size()
	{
		$i;
		foreach($this as $tag)
			$i++;
		
		return $i;
	}
	
	public function add($value) 
	{
		$this->tags[$this->count++] = $value;
		$this->scaleCounts();
	}
	
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
	
	public function fromArray($arr)
	{
		foreach($arr['results'] as $tagData) 
		{
			$addTag = new Tag($tagData);
			$this->add($addTag);
		}
	}
	
	protected function error()
	{
		// TODO: proper error page / message
		die('<b>Error '.$this->trackClass->error['code'].' - </b><i>'.$this->trackClass->error['desc'].'</i>');
	}
}