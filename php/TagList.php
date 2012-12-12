<?php
require_once 'AbstractList.php';

class TagList extends AbstractList
{
	/**
	 * minimum tag count; tags with counts
	 * below this value will be stripped away
	 * 
	 * @var int
	 * @access protected
	 */
	protected $minCount = 15;
	
	/**
	 *  Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *  Makes a new taglist containing all overlapping
	 *  tags of this and other
	 *
	 *  @return TagList
	 */
	public function union($other)
	{
		$res = new TagList();
		if($other instanceof TagList)
			foreach($this as $thisTag)
				foreach($other as $thatTag)
					if($thisTag->equals($thatTag))
						$res->add($thisTag);
						
		return $res;
	}
	
	/**
	 *  Scale all the tag counts to [0 .. 1]
	 *  similar to tag popularity / relevance
	 *  for roulette wheel selection
	 */
	protected function scaleCounts()
	{
		$maxCount = 0;
		
		if($this->size() > 0)
		{
			$sum = $this->getSumCount();
			
			foreach ($this as $tag)
			{
				$scaled = $tag->getCount() / $sum;
				$tag->setScaledCount($scaled);
			}
		}
	}
	
	/**
	 *  expand this list with splitted tags
	 */
	public function expand() 
	{
		foreach($this as $tag) {
			if($chunks = $tag->split()) {
				foreach($chunks as $chunk) {
					$this->add($chunk);
				}
			}
		}
	}
	
	private function getSumCount()
	{
		$sum = 0;
		foreach($this as $tag)
			$sum += $tag->getCount();
		
		return $sum;
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
	
			if ($results = $tagClass->search($methodVars) )
				$this->fromArray($results);
			else
				$this->error("error using search");
		} else {
			$this->error("error using search");
		}
	}
	
	/**
	 * Composes this tracklist from an api response array
	 *
	 * @param array $arr
	 */
	public function fromArray($arr)
	{
		if(array_key_exists('results', $arr))
			$key = 'results';
		elseif(array_key_exists('tags', $arr))
			$key = 'tags';
		else
			error('no such key in array');
		
		foreach($arr[$key] as $tagData) {
			if($tagData['count'] > $this->minCount) {
				$addTag = new Tag($tagData);
				$this->add($addTag);
			}
		}
		
		$this->scaleCounts();
	}
	
	/**
	 * Get a random tag based on roulette wheel
	 * selection
	 * 
	 * @return Tag
	 */
	public function getRandom()
	{
		$pin = mt_rand(0,100) / 100;
		$sum = 0;
		foreach($this as $tag) {
			$sum += $tag->getScaledCount();
			if($pin <= $sum)
				return $tag;
		}
		
		$this->error("error whilst finding a random tag");
	}
	
	public function contains(Tag $tag) 
	{
		foreach($this as $test) {
			//echo $tag.getName()."\n";
			if($test->equals($tag))
				return true;
		}
		return false;
	}
}