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
		$startTags = $this->start->getTags();
		$endTags = $this->end->getTags();
		$union = $startTags->union($endTags);
		return $union->size() == 0;
	}
}