
function start_game() {
	console.log('game start');
	update_game();
}

var run = 1;
function update_game() {
	console.log('update game');
	jQuery.getJSON('/json/', 'game=1', function(data) {
		console.log('woo');
		console.log(data);
		top.location = data['url'];
		jQuery('#game_responses').html(data['user'] + ' suggested ' + data['artist']);
		
		run = 0;
	});
	
	if (run) {
		setTimeout('update_game()', 5000);
	}
}
