<?php
return array(
    'modules' => array(
		'DoctrineModule',
		'DoctrineORMModule',
		'ZfcTwitterBootstrap',
        'Wiss',
		'AssetManager',
		'ZendDeveloperTools',
        'Application',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
			'Wiss' => '../wiss'
        ),
    ),
);
