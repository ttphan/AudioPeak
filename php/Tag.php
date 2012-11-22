<?php
require_once 'lastfmapi/lastfmapi.php';
require_once 'Auth.php';

class Tag
{
	protected $name;
	protected $count;
	protected $url;
	
	
	public function Tag(array $arr)
	{
		$this->name 		= $arr['name'];
		$this->count 		= $arr['count'];
		$this->url 			= $arr['url'];
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getCount()
	{
		return $this->count;
	}
	
	public function getUrl()
	{
		return $this->url;
	}

}