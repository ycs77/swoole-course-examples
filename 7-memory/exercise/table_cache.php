<?php

require 'CacheInterface.php';
require 'CacheTtl.php';

$cache = new SwooleCourse\CacheTtl;
$cache->put('foo', 'bar', 1);

var_dump($cache->get('foo'));

sleep(1);

var_dump($cache->get('foo'));





// $cache->put('foo', 'data', 1);
// $cache->put('bar', 'data', 2);

// var_dump($cache->get('foo'));
// var_dump($cache->get('bar'));

// sleep(1);

// var_dump($cache->get('foo'));
// var_dump($cache->get('bar'));

// sleep(1);

// var_dump($cache->get('foo'));
// var_dump($cache->get('bar'));

// $cache->recycle();
