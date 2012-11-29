<?php
class FillerList extends TrackList 
{
	protected $start;
	protected $end;
	
	public function __construct(Track $start, Track $end)
	{
		parent::__construct();
		$this->start = $start;
		$this->end = $end;
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
	 * @param Track $track1
	 * @param Track $track2
	 * @return boolean
	 */
	public static function hasOverlappingTags(Track $track1, Track $track2)
	{
		$startTags = $track1->getTags();
		$endTags = $track2->getTags();
		$union = $startTags->union($endTags);
		return !$union->size() == 0;
	}
}