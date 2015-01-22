<?php

App::error(function (\Dyryme\Exceptions\PermissionDeniedException $e)
{
	return \Redirect::route('user.denied');
});
