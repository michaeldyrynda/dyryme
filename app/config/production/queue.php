<?php

return array(
	'default'     => 'iron',
	'connections' => array(
		'iron' => array(
			'driver'  => 'iron',
			'host'    => getenv('IRON_HOST'),
			'token'   => getenv('IRON_TOKEN'),
			'project' => getenv('IRON_PROJECT'),
			'queue'   => getenv('IRON_ENCRYPT'),
			'encrypt' => true,
		),
	),
);
