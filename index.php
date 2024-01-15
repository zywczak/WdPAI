<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::get('rams', 'RamController');
Routing::get('cpus', 'CpuController');
Routing::get('coolers', 'CoolerController');
Routing::get('motherboards', 'MotherboardController');
Routing::get('logout', 'SecurityController');
Routing::get('cart', 'ProductController');
Routing::get('cpusEdit', 'CPuController');
Routing::get('ramsEdit', 'RamController');
Routing::post('coolersEdit', 'CoolerController');
Routing::get('addCooler', 'CoolerController');
Routing::get('motherboardsEdit', 'MotherboardController');
Routing::get('deleteCooler', 'CoolerController');
Routing::get('updateCooler', 'CoolerController');

Routing::run($path);