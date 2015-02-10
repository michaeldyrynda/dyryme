<?php

return [

	/*
	|--------------------------------------------------------------------------
	| API key
	|--------------------------------------------------------------------------
	|
	| Set your Google Custom Search Engine API key here
	|
	*/

	'api_key'          => getenv('GOOGLE_CSE_API_KEY'),

	/*
	|--------------------------------------------------------------------------
	| Search engine ID
	|--------------------------------------------------------------------------
	|
	| Set your Google Custom Search Engine engine identifier here
	|
	*/

	'search_engine_id' => getenv('GOOGLE_CSE_ENGINE_ID'),

];
