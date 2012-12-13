// JavaScript Document
//TRACK OBJECT
function track(name,artist,image){
	this.name=name;
	this.artist=artist;
	this.image=image;
	this.id='geenidtoegevoegd';
}

track.prototype.addID = function addID(id){
	this.id=id;
}

track.prototype.MytoString = function trackMyToString() {
	var res = this.name + "','" + this.artist + "','" + this.image;
	return res;
}

track.prototype.toString = function trackToString() {
	var res = "[" + this.name + ", " + this.artist + ", " + this.image + ", " + this.id + "]";
	return res;
}

//NOG NIET GETEST! mytrack.addYTid();
track.prototype.addYTid = function addYTid(){
	//zoek string
	var searchStr = this.artist + ' ' + this.name;

	//gebruik json om te zoeken en geef deze data door aan showButton met i en image.
	$.getJSON('https://gdata.youtube.com/feeds/api/videos?v=2' +
			'&alt=jsonc&q=' +
			searchStr + 
			'&format=5' +
			'&fmt=18' +
			'&max-results=1' +
			'&orderby=relevance' +
			'&key=AI39si7Blv0HpIGbcHtzjaS70mFR-XEcomtJFHQcKC1-4yEthFsx4AhkMFldBeE_5UyD9jEEFCPMt2jzxDLF3hPT1SRoi1La4Q', function(transport) {		
				var entries	= transport.data.items || [];
				var id = entries[0].id;
				filler.addID(id);		
				if(filler.image == ''){
					//zoek op Plasticman Cha Vocal die heeft geen afbeelding
					$('#filler1').attr('src','images/albumgeen.jpg');		
				}
				else {	
					$('#filler1').attr('src',filler.image);
				}		
			});		
}