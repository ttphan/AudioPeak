<?php
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
			return FillerList::computeFiller($this->start, $this->end);
		else
			return $this->filler;
	}
	
	/**
	 * 
	 * @todo make it compute more than one filler
	 * @param Track $start
	 * @param Track $end
	 * @return Track|FillerList
	 */
	protected static function computeFiller($start, $end)
	{
		if(!FillerList::hasOverlappingTags($start->getTags(), $end->getTags())) {
			$rand = $start->getTags()->getRandom();
			
			foreach($rand->getTopTracks() as $testTrack)
				if(FillerList::hasOverlappingTags($testTrack->getTags(), $end->getTags()))
					$res = $testTrack;
			
		} else {
			echo "no filler needed";
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
		$union = $startTags->union($endTags);
		return $union->size() > 0;
	}
}