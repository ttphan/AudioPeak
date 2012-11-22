<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Track.php';
require_once 'Auth.php';

class TrackList implements IteratorAggregate
{
	private $tracks = array();
	private $count = 0;
	
	protected $numResults = 5;
	
	protected $auth;
	protected $apiClass;
	protected $config;
	
	public function TrackList()
	{
		$this->auth 	= Auth::getAuth();
		$this->apiClass = Auth::getApi();
		$this->config	= Auth::getConfig();
	}
	
	// Required by IteratorAggregate
	public function getIterator() 
	{
		return new ArrayIterator($this->tracks);
	}
	
	public function add($value) 
	{
		$this->tracks[$this->count++] = $value;
	}
	
	public function reset() 
	{
		$track = array();
		$count = 0;
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
			} else {
				// TODO: proper error page / message
				die('<b>Error '.$this->trackClass->error['code'].' - </b><i>'.$this->trackClass->error['desc'].'</i>');
			}
		} else {
			$this->error();
		}
	}
	
	public function fromArray($arr)
	{
		foreach($arr['results'] as $trackData)
		{
			$this->add(new Track($trackData));
		}
	}
	
	protected function error()
	{
		// TODO: proper error page / message
		die('<b>Error '.$this->trackClass->error['code'].' - </b><i>'.$this->trackClass->error['desc'].'</i>');
	}
}