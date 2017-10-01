<?php

//	Register objects you want to be available globally
$registryInstance = \Core\Registry::getInstance();
$registryInstance->set( new PrettyDate );
$registryInstance->set( new DBMonitor );
$registryInstance->set( new \Core\Database );
$registryInstance->set( new CookieMgr );

// DataStore
Core\DataStore::set(
    [
        'url_paths' => \Core\Requests::processUri()->paths
    ]
);