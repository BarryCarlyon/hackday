
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
	jQuery.getJSON('/json/', post, function(data) {
		for (x in data) {
			entry = data[x];

			if (run) {
				run = 0;
				top.location = entry['url'];
				jQuery('#game_responses').html('');
			}

			jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />' + entry['user'] + ' suggested <a href="' + entry['url'] + '">' + entry['artist'] + '</a>');
			total_results++;
			
			if (entry['mid'] > lastid) {
				post = 'game=1&mid=' + entry['mid'];
			}
		}
	});
	
	if (total_results < 20 && request_count < 10) {
		setTimeout('update_game()', 5000);
	} else {
		jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />Stopped looking for Responses');
	}
	request_count++;
}
