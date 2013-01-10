<?php
require_once 'TrackList.php';
class FillerList extends TrackList 
{
	/**
	 * @var Track
	 */
	protected $start;
	/**
	 * @var Track|FillerList
	 */
	protected $filler;
	/**
	 * @var Track
	 */
	protected $end;
	
	/**
	 * 
	 * @param Track $start
	 * @param Track $end
	 */
	public function __construct(Track $start, Track $end)
	{
		parent::__construct();
		$this->start = $start;
		$this->end = $end;
	}
	
	/**
	 * returns the filler for start <--> end
	 * 
	 * @return Ambigous <Track, FillerList>
	 */
	public function getFiller()
	{
		if(!isset($this->filler))
			return FillerList::computeFiller2($this->start, $this->end);
		else
			return $this->filler;
	}
	
	public static function computeFiller2($startID,$endID)
	{
		$db = new MySQL();
		$start = $db->getSong($startID);
		$end = $db->getSong($endID);
		
		$candidates = $db->getSongs($start['tempo'],$end['tempo']);
		$scores = array();
		foreach($candidates as $candidate) {
			if($candidate['id'] != $startID && $candidate['id'] != $endID) {
				$leftDist = FillerList::getDistance($start, $candidate);
				$rightDist = FillerList::getDistance($end, $candidate);
				//echo $startID." <-".$leftDist."-> ".$candidate['id']." <-".$rightDist."-> ".$endID."\n";
				
				$diff = abs($leftDist-$rightDist);
				$sum = $leftDist + $rightDist;
				
				$score = $diff + $sum;
				$scores[$candidate['id']] = $score;
			}
		}
		
		$minIds = FillerList::minIds($scores);
		
		
		echo sizeof($minIds)."/".sizeof($scores)." possible winrars \n";
		
		$rand = mt_rand(0,sizeof(minIds)-1);
		$winrar = $minIds[$rand];
		//print_r($scores);
		return $winrar;
	}
	
	protected static function minIds($arr)
	{
		$min = min($arr);
		$minIds = array();
		// get which are max
		foreach($arr as $key => $val) {
			if ($val == $min) {
				$minIds[] = $key;
			}
		}
		return $minIds;
	}
	
	protected static function getDistance($track1, $track2)
	{
		$sum = 0;
		$num = 3;
		$sum += abs($track1['song_hotttnesss'] - $track2['song_hotttnesss']);
		//var_dump($sum);
		$sum += abs($track1['danceability'] - $track2['danceability']);
		//var_dump($sum);
		$sum += abs($track1['energy'] - $track2['energy']);
		//var_dump($sum);
		if($track1['year'] != 0 && $track2['year'] != 0) {
			$sum += abs($track1['year'] - $track2['year']) / 100;
			$num++;
		}
		//var_dump($sum);
		if($track1['tempo'] != 0 && $track2['tempo'] != 0) {
			$sum += abs($track1['tempo'] - $track2['tempo']) / 100;
			$num++;
		}
		//var_dump($sum);
		//echo "\n";
		
		if($sum == 0)
			return PHP_INT_MAX;
				
		return $sum / $num;
	}
	
	/**
	 * 
	 * @todo make it compute more than one filler
	 * @todo cache that shit
	 * @param Track $start
	 * @param Track $end
	 * @return Track|FillerList
	 */
	protected static function computeFiller($start, $end)
	{	
		$res = null;
		if(!FillerList::hasOverlappingTags($start->getTags(), $end->getTags())) {
			$rand = $start->getTags()->getRandom();
			
			
			$endTag = $end->getTags()->getRandom();
		
			
			foreach($rand->getTopTracks() as $testTrack) {
				$artist = $testTrack->getArtist();
				$title = $testTrack->getName();
				
				$temp = $testTrack->getTags();
				if($temp === null) {
				
				}
				else {
					$list = $temp->strippedList();
					if(in_array($endTag->getName(), $list)) {
						$track = new DetailedTrack($artist['name'], $title);
						$res[] = $track;
					
					}

				}
			}
			
			if($res == null) {
				//TODO: and find more fillers
			}
			
		} else {
			//TODO: and find more fillers
		}
		
		return $res;
	}
	
	/**
	 * If tags of the songs collide, no filler is needed
	 * 
	 * @param Track $start
	 * @param Track $end
	 * @return boolean
	 */
	public function fillerNeeded()
	{
		return !$this->hasOverlappingTags($this->start, $this->end);
	}
	
	/**
	 * Check if track1 and 2 have overlapping tags
	 * 
	 * @param TagList $track1
	 * @param TagList $track2
	 * @return boolean
	 */
	public static function hasOverlappingTags(TagList $startTags, TagList $endTags)
	{
		$return = false;
		foreach($startTags as $startTag) {
			foreach($endTags as $endTag) {
				if($startTag->getName() == $endTag->getName()) {
					$return = true;
				}
			}
		}
		return $return;
	}
}