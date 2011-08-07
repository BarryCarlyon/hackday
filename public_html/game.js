
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
				jQuery.get('/json/', 'playing=' + escape(entry['artist']) + '&refer=' + entry['user']);
			}

			if (jQuery('#game_responses').html()) {
//				jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />' + entry['string']);
			} else {
//				jQuery('#game_responses').html(entry['string']);
			}
			jQuery('<p>' + entry['string'] + '</p>').hide().appendTo('#game_responses').slideDown();
			total_results++;
			
			if (entry['mid'] > lastid) {
				post = 'game=1&mid=' + entry['mid'];
			}
		}
	});
	
	if (total_results < 20 && request_count < 60) {
		setTimeout('update_game()', 5000);
	} else {
		if (run) {
			jQuery('#game_responses').html('Stopped looking for Responses<br />We Suggest you Listen to <a href="spotify:track:6JEK0CvvjDjjMUBFoXShNZ">this</a>');
		} else {
			jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />Stopped looking for Responses<br />We Suggest you Listen to <a href="spotify:track:6JEK0CvvjDjjMUBFoXShNZ">this</a>');
		}
		if (run) {
			top.location = 'spotify:track:6JEK0CvvjDjjMUBFoXShNZ';
			jQuery.get('/json/', 'playing=Rick');
		}
	}
	request_count++;
}

jQuery(document).ready(function() {
	jQuery('.getplaylist').live('click', function() {
		artist = jQuery(this).attr('artisturi');
		
		html = jQuery(this).html();
		jQuery(this).html('Loading...');
		target = this;
		
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
			jQuery(target).html(html);
		});
	});
	
	jQuery('#playlistcontent').live('mouseover click', function() {
		jQuery('#playlistcontent').select();
	});
	jQuery('.playartist').live('click', function() {
		extra = 'playing=' + escape(jQuery(this).attr('artist')) + '&refer=' + jQuery(this).attr('suggest');
		jQuery.get('/json/', extra);
	});
	
	jQuery('#custom_tweet').click(function() {
		jQuery('<div><textarea id="the_custom_tweet" style="width: 300px; height: 200px;">I am playing #SpotifyRoulette, suggest me an artist via reply and I will listen to them!</textarea><br /><input type="submit" onclick="custom_tweet()" value="Submit" />').dialog({
			draggable: false,
			modal: true,
			resizable: true,
			title: 'Custom Tweet',
			width: '350px'
		});
	});
});

function custom_tweet() {
	tweet = jQuery('#the_custom_tweet').val();
	jQuery('#custom_tweet_text').val(tweet);
	jQuery('#spin_form').submit();
}
