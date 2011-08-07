
function start_game() {
	update_game();
}

var run = 1;
var total_results = 0;
var mid = '';
var post = 'game=1';
var lastid = 0;
var request_count = 0;

function update_game() {
	console.log('update game: ' + post + ' check ' + request_count + '/60');
	jQuery.getJSON('/json/', post, function(data) {
		console.log('got data');
		for (x in data) {
			entry = data[x];

			if (run) {
				run = 0;
				top.location = entry['url'];
				jQuery('#game_responses').html('');
			}

			if (jQuery('#game_responses').html()) {
				jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />' + entry['string']);
			} else {
				jQuery('#game_responses').html(entry['string']);
			}
			total_results++;
			
			if (entry['mid'] > lastid) {
				post = 'game=1&mid=' + entry['mid'];
			}
		}
	});
	
	if (total_results < 20 && request_count < 5) {
		setTimeout('update_game()', 5000);
	} else {
		if (run) {
			jQuery('#game_responses').html('Stopped looking for Responses<br />We Suggest you Listen to <a href="spotify:track:6JEK0CvvjDjjMUBFoXShNZ">this</a>');
		} else {
			jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />Stopped looking for Responses<br />We Suggest you Listen to <a href="spotify:track:6JEK0CvvjDjjMUBFoXShNZ">this</a>');
		}
		if (run) {
			top.location = 'spotify:track:6JEK0CvvjDjjMUBFoXShNZ';
		}
	}
	request_count++;
}

jQuery(document).ready(function() {
	jQuery('.getplaylist').live('click', function() {
		artist = jQuery(this).attr('artisturi');
		
		jQuery.get('/json/', 'playlist=' + artist, function(data) {
			jQuery('<div class="playlistdialog">' + data + '</div>').dialog({
				draggable: false,
				modal: true,
				resizable: true,
				title: 'Playlist',
				width: '750px',
				close: function(event, ui) {
					jQuery('.playlistdialog').remove();
				}
			});
		});
	});
	
//	jQuery('#playlistbutton').live('mousedown', function() {
//		jQuery('#playlistcontent').select();
//	});
	jQuery('#playlistcontent').live('mouseover click', function() {
		console.log('clicked');
		jQuery('#playlistcontent').select();
	});
});
