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
		$db = new MySQL();
		
		parent::__construct();
		$this->start = $db->getTid($start);
		$this->end = $db->getTid($end);
	}
	
	/**
	 * returns the filler for start <--> end
	 * 
	 * @return Ambigous <Track, FillerList>
	 */
	public function getFiller()
	{
		if(!isset($this->filler))
			return FillerList::computeFillers($this->start, $this->end);
		else
			return $this->filler;
	}
	
	public static function computeFillers($startID,$endID)
	{
		$db = new MySQL();
		$start = $db->getSong($startID);
		$end = $db->getSong($endID);
		$start['tags'] = $db->getTags($startID);
		$end['tags'] = $db->getTags($endID);
		
		$res = array();
		
		//print_r($start);
		//print_r($end);
		
		/*$numFillers = round(abs($start['tempo'] - $end['tempo']) / 20, 0, PHP_ROUND_HALF_UP);
		if($start['tempo'] + $end['tempo'] == 0) {
			$numFillers = 1;
		}
		echo "NUMBER OF FILLERS NEEDED: " . $numFillers."\n";
		
		if($numFillers > 0) {*/
		$filler = FillerList::getOneFiller($start, $end);
		$res[] = $filler;
			/*if($numFillers > 1) { // need more fillers
				if($numFillers % 2 == 0) {
					array_combine($res, FillerList::computeFillers($filler['id'], $endID));
					array_combine(FillerList::computeFillers($startID, $filler['id']), $res);
				} else {
					$leftDist = FillerList::getDistance($start, $filler);
					$rightDist = FillerList::getDistance($filler, $end);
					if($leftDist > $rightDist) {
						array_combine($res, FillerList::computeFillers($filler['id'], $endID));
					} else {
						array_combine(FillerList::computeFillers($startID, $filler['id']), $res);
					}*/
		return $res;
	}
	
	protected static function trimSimilars($sims) {
		$db = new MySQL();
		$res = array();
		foreach($sims as $key => $val) {
			if($db->exists($key)) {
				if($val > 0.1 && $val < 0.7) {
					$res[] = $key;
				}
			}
		}
		return $res;
	}
	
	public static function getOneFiller($start, $end) 
	{
		$db = new MySQL();
		//$candidates = $db->getSongs($start['tempo'],$end['tempo']);
		$start['similar'] = $db->getSimilar($start['tid']);
		$end['similar'] = $db->getSimilar($end['tid']);
		
		$candidates = array_merge($start['similar'], $end['similar']);
		$candidates = FillerList::trimSimilars($candidates);
		
		if (sizeof($candidates) < 10) {
			$candidates = $db->getSongs($start['tempo'], $end['tempo']);
		}
		else {
			$res= array();
			foreach($candidates as $tid) {
				$res[] = $db->getSong($tid);
			}
			$candidates = $res;
		}
		$scores = array();
		foreach($candidates as $candidate) {
			if($candidate['tid'] != $start['tid'] && $candidate['tid'] != $end['tid']) {
				$candidate['similar'] = $db->getSimilar($candidate['tid']);
				
				$candidate['tags'] = $db->getTags($candidate['tid']);
				$leftDist = FillerList::getDistance($start, $candidate);
				$rightDist = FillerList::getDistance($candidate, $end);
				//echo $startID." <-".$leftDist."-> ".$candidate['tid']." <-".$rightDist."-> ".$endID."\n";
				
				$score = min($leftDist,$rightDist)/max($leftDist,$rightDist);

				$scores[$candidate['tid']] = $score;
			}
		}

		$minIds = FillerList::minIds($scores);
		//echo sizeof($minIds)."/".sizeof($scores)." possible winrars \n";
		
		$rand = mt_rand(0,sizeof($minIds)-1);
		$winrar = $minIds[$rand];

		return $winrar;
	}
	
	protected static function jaccard($start, $end) 
	{
		if(is_array($start) && is_array($end))
			return count(array_intersect_key($start, $end)) / count(array_merge($start, $end));
		else
			return 1;
	}
	
	protected static function minIds($arr)
	{
		$min = min($arr);
		asort($arr);
		$minIds = array();
		$index = array_keys($arr);
		
		for($i = 0; $i < 3; $i++) {
			$minIds[] = $index[$i];
		}
		//print_r($minIds);
		//print_r($arr);
		return $minIds;
	}
	
	protected static function getDistance($track1, $track2)
	{
		//echo (is_array($track1['tags']) && is_array($track2['tags'])) ? 'YES' : 'nope';
		$properties = array();
		$properties['hotttnesss']	= abs($track1['song_hotttnesss'] - $track2['song_hotttnesss']);
		$properties['danceability']	= abs($track1['danceability'] - $track2['danceability']);
		$properties['energy']		= abs($track1['energy'] - $track2['energy']);
		$properties['jaccard']		= 1 - FillerList::jaccard($track1['similar'], $track2['similar']);
		if(is_array($track1['tags']) && is_array($track2['tags'])) {
			$properties['tags']			= 1 - (2 * count(array_intersect($track1['tags'], $track2['tags'])) ) / (count($track1) + count($track2));
		}
		
		if($track1['year'] != 0 && $track2['year'] != 0)
			$properties['year'] = abs($track1['year'] - $track2['year']) / 100;
		
		if($track1['tempo'] != 0 && $track2['tempo'] != 0)
			$properties['tempo'] = abs($track1['tempo'] - $track2['tempo']) / 100;
		
		return array_sum($properties) / sizeof($properties);
	}
}