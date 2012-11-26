<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Track.php';
require_once 'Auth.php';


/**
 *  track objects of this class are iterable in a foreach loop
 *  thank IteratorAggregate for this
 */
class TrackList implements IteratorAggregate
{
	/**
	 *  Store all the track objects
	 *  
	 *  @access private
	 *  @var array
	 */
	private $tracks = array();
	
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
	protected $numResults = 5;
	
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
		return new ArrayIterator($this->tracks);
	}
	
	/**
	 *  Get a specific track by index
	 *
	 *  @param  int $index
	 *  @return Track
	 */
	public function get($index)
	{
		return $tracks[$index];
	}
	
	/**
	 * Add a track to this tracklist
	 *
	 * @param  Track $value
	 */
	public function add($value) 
	{
		$this->tracks[$this->count++] = $value;
	}
	
	/**
	 * Clear this tracklist of any data
	 */
	public function reset() 
	{
		unset($track);
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
	
	/**
	 * Composes this tracklist from an api response array
	 *
	 * @param  array $arr
	 */
	public function fromArray($arr)
	{
		foreach($arr['results'] as $trackData)
		{
			$this->add(new Track($trackData));
		}
	}
	
	/**
	 * Compute and display an error
	 * 
	 * @todo complete this
	 */
	protected function error()
	{
		die('<b>Error '.$this->trackClass->error['code'].' - </b><i>'.$this->trackClass->error['desc'].'</i>');
	}
}