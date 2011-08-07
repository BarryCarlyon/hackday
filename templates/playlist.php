<?php

// generate a playlist for an artist
if (@$json == 1) {
	// genny
	// Playlist CAN get misc
	$pltracks = array();
	$spotify = new Spotify();
	
	// get some albums
	$albums = $spotify->lookup_artist($artist, 'album');
	$albums = $albums->artist->albums;
	$artistname = $albums[0]->album->name;
	
	shuffle($albums);
	$full = $albums;
	
	for ($x=0;$x<10;$x++) {
		$album = array_pop($albums);
		
		$albumuri = $album->album->href;
		$albumname = $album->album->name;
		
		// get album
		$tracks = $spotify->lookup_album($albumuri, 'track');
		$tracks = $tracks->album->tracks;
		shuffle($tracks);
		$track = array_pop($tracks);

		$trackuri = $track->href;
		
		$pltracks[$trackuri] = array(
			'name'		=> $track->name,
			'album'		=> $albumname
		);
		
		if (!count($albums)) {
			// reset
			$albums = $full;
		}
		shuffle($albums);
	}
	
	$uris = '';
	
	echo '<p>Click a Single Track to Play</p>';
	echo '<ul>';
	foreach ($pltracks as $uri => $track) {
		echo '<li>';
//		echo '<a href="' . $uri . '" class="playartist" artist="' . $artistname . '" suggest="' . $from . '">' . $track->name . ' from ' . $track->album . '</a>';
		echo '<a href="' . $uri . '">' . $track['name'] . ' from ' . $track['album'] . '</a>';
		echo '</li>';
		$uris .= $uri . ' ';
	}
	echo '</ul>';
	
	echo '<p class="center">Drag the block below to your Play Queue or a Playlist in Spotify to Play this Playlist, or Copy and Paste</p>';
	echo '<textarea id="playlistcontent" readonly="readonly">';
	$uris = trim($uris);
	echo $uris;
	echo '</textarea>';
}
