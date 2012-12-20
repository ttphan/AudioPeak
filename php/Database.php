<?php
require_once 'SQLite.php';

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
	
	public function getTags(Track $track) 
	{
		$title = $track->getName();
		$artist = $track->getArtist();
		
		$stm = "SELECT `track_id` 
				FROM `songs`
				WHERE `title` = ".$this->dbMeta->quote($title)."
				AND `artist_name` = ".$this->dbMeta->quote($artist['name']);
		// TrackID of the song
		$id = $this->dbMeta->select_query($stm, true);
		if($id[0] === false)
		{
			echo $result[1];
			return null;
		}
		else if($id === false)
		{
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
					AND `tags`.`tag` != ".$this->dbTags->quote($artist['name'])."
					AND `tags`.`tag` != ".$this->dbTags->quote($title);
			
			$tags = $this->dbTags->select_query($stm);
			
			$arr = array();
			
			foreach($tags as $tag) {
					$arr['results'][] = array(
										'name' => $tag['tag'],
										'count' => $tag['val']
										);				
			}
			return $arr;
		}
	}
}