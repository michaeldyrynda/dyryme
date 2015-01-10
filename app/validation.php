<?php

Validator::extend('no_recursion', function ($attribute, $value, $parameters)
{
	return ! stristr($value, 'dyry.me');
});
