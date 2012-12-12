<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Track.php';
require_once 'Tag.php';
require_once 'Auth.php';

abstract class AbstractList implements IteratorAggregate
{
	/**
	 *  Store all the abstract item objects
	 *
	 *  @access private
	 *  @var array
	 */
	private $items = array();
	/**
	 *  the number of items
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
	protected $numResults = 30;
	
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
		return new ArrayIterator($this->items);
	}
	
	/**
	 *  Get a specific ite bmy index
	 *
	 *  @param int $index
	 *  @return Item
	 */
	public function get($index)
	{
		return $this->items[$index];
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
	 *  Add an item to this list
	 *
	 *  @param Tag $value
	 */
	public function add($value)
	{
		$this->items[$this->count++] = $value;
	}
	
	/**
	 * Clear this taglist of any data
	 */
	public function reset()
	{
		unset($items);
		$items = array();
		$count = 0;
	}
	
	/**
	 * @todo finish this
	 * @param Item $item
	 * @return boolean
	 */
	public function exists(Item $item)
	{
		foreach($this as $temp) {
			
		}
		return false;
	}
	
	/**
	 * Compute and display an error
	 *
	 * @todo complete this
	 */
	protected function error($msg = 'unknown')
	{
		die('<b>Error msg: </b> '.$msg);
	}
	
	/**
	 * Composes this list from a search
	 *
	 * @param  string $query
	 */
	public abstract function search($query);
	
	/**
	 * Composes this list from an api response array
	 *
	 * @param array $arr
	 */
	public abstract function fromArray($arr);
}