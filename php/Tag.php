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
	protected $name;
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
	protected $url;
	
	/**
	 *  Class constructor
	 *
	 *  @param array $arr array with last.fm api track data
	 */
	public function __construct(array $arr)
	{
		$this->name 		= $arr['name'];
		$this->count 		= $arr['count'];
		$this->url 			= $arr['url'];
	}
	
	public function equals($other)
	{
		return $other instanceof Track && $other->getName() == $this->getName();
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getCount()
	{
		return $this->count;
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