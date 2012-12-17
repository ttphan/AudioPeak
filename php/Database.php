<?php
require_once 'SQLite.php';
require_once 'DetailedTrack.php';

class Database 
{
	protected $dbMeta;
	protected $dbTags;
	
	public function __construct() 
	{
		$this->dbMeta = new SQLite('track_metadata.db');
		$this->dbTags = new SQLite('lastfm_tags.db');
	}
	
	public function getTags(DetailedTrack $track) 
	{
		/*$stm = 'SELECT `track_id` 
				FROM `songs`
				WHERE `title` = '.$track->getName()
				.' AND `artist_name` = '.$track->getArtist();*/
		
		$stm = "SELECT * 
			FROM `songs` LIMIT 10";
		return $this->dbMeta->select_query($stm);
	}
}