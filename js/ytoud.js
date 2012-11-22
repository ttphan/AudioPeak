// JavaScript Document
function searchClicked()
{
$("videoResultsDiv").innerHTML =
'Loading YouTube videos ...';
var searchStr = $("inputSearchStrId").value;
//create a JavaScript element that returns the JSON data.
var script = document.createElement('script');
script.setAttribute('id', 'jsonScript');
script.setAttribute('type', 'text/javascript');
script.setAttribute('src', 'http://gdata.youtube.com/feeds/' +
'videos?vq=' + searchStr + '&max-results=8&' +
'alt=json-in-script&callback=showMyVideos&' +
'orderby=relevance&sortorder=descending&format=5&fmt=18');
//after running this script, runs showMyVideos on callback
document.documentElement.firstChild.appendChild(script);
}
function showMyVideos(data)
{
var feed = data.feed;
var entries = feed.entry || [];
var html = ['<ul>'];
for (var i = 0; i < entries.length; i++)
{
var entry = entries[i];
var playCount = entry.yt$statistics.viewCount.valueOf() + ' views';
var title = entry.title.$t;
var link = '<a href = \"' + entry.link[0].href + '\">link</a>';
html.push('<li>', title, ', ', playCount, ', ', link, '</li>');
}
html.push('</ul>');
$('videoResultsDiv').innerHTML = html.join('');
}
