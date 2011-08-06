
function start_game() {
	console.log('game start');
	update_game();
}

var run = 1;
var total_results = 0;
var mid = '';
var post = 'game=1';
var lastid = 0;

function update_game() {
	console.log('update game');
	
	jQuery.getJSON('/json/', post, function(data) {
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
			
			if (entry['mid'] > lastid) {
				post = 'game=1&mid=' + entry['mid'];
			}
		}
	});
	
	if (total_results < 20) {
		setTimeout('update_game()', 5000);
	}
}
