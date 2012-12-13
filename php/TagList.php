<?php
require_once 'AbstractList.php';

/**
 *  track objects of this class are iterable in a foreach loop
 *  thank IteratorAggregate for this
 */
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
	 * expand this list with splitted tags
	 */
	public function expand()
	{
		foreach($this as $subject) {
			$this->merge($subject->getSplit());
		}
		$this->filter();
	}
	
	/**
	 * 
	 * @return number
	 */
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
	
	/**
	 * 
	 * @return array
	 */
	public function strippedList() 
	{
		$array = array();
		foreach($this as $tagObj) 
		{
			$array[] = $tagObj->getName();
		}
		return $array;
	}
	
	/**
	 * Add all the tags from $that to $this
	 * 
	 * @param TagList $that
	 */
	protected function merge(TagList $that)
	{
		foreach($that as $new) {
			$key = $this->getKey($new->getName());
			
			if($key == -1) // tag doesnt exists yet
				$this->add($new);
			else {
				$double = &$this->items[$key]; //hell yeah pointers!
				$double->setCount($new->getCount() + $double->getCount());
			}
		}
	}
	
	/**
	 * get the key of the tagname if present
	 * else it returns -1
	 * 
	 * @param string $tagName
	 * @return int
	 */
	public function getKey($tagName) 
	{
		foreach($this as $key => $tag)
			if($tag->getName() === $tagName)
				return $key;
		
		return -1;
	}
	
	/**
	 * removes the tag with that tagname
	 * 
	 * @param string $tagName
	 */
	public function remove($tagName)
	{
		$key = $this->getKey($tagName);
		if($key != -1) {
			array_splice($this->items, $key, $key-1);
		}
	}
	
	/**
	 * remove the values according to the filterlist
	 * @param array $filterlist
	 */
	public function applyFilter(array $filterlist)
	{
		foreach($filterlist as $filtertag) {
			$this->remove($filtertag);
		}
	}
	
	/**
	 *  remove the tags from the tagFilter file
	 */
	public function filter()
	{
		$file = 'tagFilter.txt';
		
		if(file_exists($file)) {
			$input = fopen($file, 'r');
			$raw = fread($input, filesize($file));
			$data = explode(',', $raw);
			
			$this->applyFilter($data);
		}
	}
}