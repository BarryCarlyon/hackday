<?php

// login interface

if ($login->is_logged_in) {
	echo 'You is logged in like';
} else {
	// not logged in
	echo '
<form action="" method="post" id="login_form">
	<fieldset>
		<input type="hidden" name="go_login" value="twitter" />
		<input type="submit" value="Login with Twitter" />
	</fieldset>
</form>
';
}