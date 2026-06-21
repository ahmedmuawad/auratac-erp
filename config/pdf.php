<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => 'Barq System',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('../temp/'),
	'font_path' => base_path('resources/fonts/'),
	'font_data' => [
		'tajawal' => [
			'R'  => 'Tajawal-Regular.ttf',    // regular
			'B'  => 'Tajawal-Bold.ttf',       // bold
			'useOTL' => 0xFF,
			'useKashida' => 75,
		]
	],
	'auto_language_detection'  => true,
	'insider_font_subset'   => true,
];
