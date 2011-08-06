
function start_game() {
	console.log('game start');
	update_game();
}

var run = 1;
var total_results = 0;

function update_game() {
	console.log('update game');
	jQuery.getJSON('/json/', 'game=1', function(data) {
		console.log('woo');
		console.log(data);

		for (x in data) {
			entry = data[x];
			if (run) {
				run = 0;
				top.location = entry['url'];
				jQuery('#game_responses').html('');
			}

			jQuery('#game_responses').html(jQuery('#game_responses').html() + '<br />' + entry['user'] + ' suggested <a href="' + entry['url'] + '">' + entry['artist'] + '</a>');
			total_results++;
		}
	});
	
	if (total_results < 20) {
		setTimeout('update_game()', 5000);
	}
}
