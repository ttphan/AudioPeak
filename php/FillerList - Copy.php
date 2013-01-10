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
			echo $rand->getName() ."\n\n";
			
			$endTag = $end->getTags()->getRandom();
			echo $endTag->getName()."\n\n";
			
			foreach($rand->getTopTracks() as $testTrack) {
				$artist = $testTrack->getArtist();
				$title = $testTrack->getName();
				echo "testing \"".$artist['name']." - ".$title."\"";
				$temp = $testTrack->getTags();
				if($temp === null) {
					echo " -> nope\n";
				}
				else {
					$list = $temp->strippedList();
					if(in_array($endTag->getName(), $list)) {
						$track = new DetailedTrack($artist['name'], $title);
						$res[] = $track;
						echo " -> yes\n";
					}
					else
						echo " -> nope\n";
				}
			}
			
			if($res == null) {
				echo "no filler found";
				//TODO: and find more fillers
			}
			
		} else {
			echo "\n no filler needed \n \n";
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