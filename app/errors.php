<?php

App::error(function (\Dyryme\Exceptions\PermissionDeniedException $e)
{
	return \Redirect::route('user.denied');
});

App::error(function (\Dyryme\Exceptions\LooperException $e)
{
	return \Redirect::route('loop_detected');
});
