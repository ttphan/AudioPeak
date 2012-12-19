<?php
require_once 'SQLite.php';
require_once 'DetailedTrack.php';

class Database 
{
	/**
	 *	@var SQLite
	 */
	protected $dbMeta;
	
	/**
	 * @var SQLite
	 */
	protected $dbTags;
	
	public function __construct() 
	{
		$this->dbMeta = new SQLite('track_metadata.db');
		$this->dbTags = new SQLite('lastfm_tags.db');
	}
	
	public function getTags(DetailedTrack $track) 
	{
		$title = $track->getName();
		$artist = $track->getArtist();
		
		$stm = "SELECT `track_id` 
				FROM `songs`
				WHERE `title` = '".$title."' 
				AND `artist_name` = '".$artist['name']."'";
		
		// TrackID of the song
		$id = $this->dbMeta->select_query($stm, true);
		
		if($result[0] === false)
		{
			echo $result[1];
			return null;
		}
		else
		{
			// Filters out the artist-tags and low-value tags.
			
			$stm = "SELECT `tags`.`tag`, `tid_tag`.`val`
					FROM `tags`, `tid_tag`, `tids`
					WHERE `tids`.`tid` = '".$id['track_id']."'
					AND `tids`.`rowid` =`tid_tag`.`tid`
					AND `tags`.`rowid` = `tid_tag`.`tag`
					AND `tid_tag`.`val` > 15
					AND `tags`.`tag` != '".$artist['name']."'";
			
			return $this->dbTags->select_query($stm);
		}
	}
}