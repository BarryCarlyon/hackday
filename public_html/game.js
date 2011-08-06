
function start_game() {
	console.log('game start');
	update_game();
}

function update_game() {
	console.log('update game');
	jQuery.getJSON('/json/', 'game=1', function(data) {
		console.log('woo');
		console.log(data);
	});
}
