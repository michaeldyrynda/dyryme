<?php

Validator::extend('no_recursion', function ($attribute, $value, $parameters)
{
	return ! starts_with($value, 'http://dyry.me')  && ! starts_with($value, 'https://dyry.me');
});

Validator::extend('no_hitler', function ($attribute, $value, $parameters)
{
	return ! preg_match('/o\/$/', $value);
});
