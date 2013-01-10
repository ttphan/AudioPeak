<?php
require_once 'AbstractList.php';

/**
 *  track objects of this class are iterable in a foreach loop
 *  thank IteratorAggregate for this
 */
class TrackList extends AbstractList
{	
	/**
	 *  Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Search for a track
	 *
	 * @param  string $query
	 */
	public function search($query = '')
	{
		if($query != '')
		{
			$trackClass = $this->apiClass->getPackage($this->auth, 'track', $this->config);
			
			$methodVars = array(
					'track' => $query,
					'limit' => $this->numResults
			);
	
			if ($results = $trackClass->search($methodVars) ) {
				$this->fromArray($results);
			} //else no results
		} else {
			$this->error("provide a search query, you dickhead");
		}
	}
	
	/**
	 * Composes this tracklist from an api response array
	 *
	 * @param  array $arr
	 */
	public function fromArray($arr)
	{
		$arr = array_key_exists('results', $arr) ? $arr['results'] : $arr;
		foreach($arr as $trackData)	{
			$this->add(new Track($trackData));
		}
	}
}