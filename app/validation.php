<?php

Validator::extend('no_recursion', function ($attribute, $value, $parameters)
{
	return ! starts_with($value, 'http://dyry.me')  && ! starts_with($value, 'https://dyry.me');
});
