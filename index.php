<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::post('login', 'SecurityController');
Routing::post('register', 'SecurityController');
Routing::get('logout', 'SecurityController');

Routing::get('cart', 'ProductController');
Routing::get('clearCart', 'ProductController');
Routing::get('remove', 'ProductController');
Routing::get('addToCart', 'ProductController');

Routing::get('cpus', 'CpuController');
Routing::post('cpusEdit', 'CpuController');
Routing::get('addCpu', 'CpuController');
Routing::get('deleteCpu', 'CpuController');
Routing::get('updateCpu', 'CpuController');

Routing::get('rams', 'RamController');
Routing::post('ramsEdit', 'RamController');
Routing::get('addRam', 'RamController');
Routing::get('deleteRam', 'RamController');
Routing::get('updateRam', 'RamController');

Routing::get('coolers', 'CoolerController');
Routing::post('coolersEdit', 'CoolerController');
Routing::get('addCooler', 'CoolerController');
Routing::get('deleteCooler', 'CoolerController');
Routing::get('updateCooler', 'CoolerController');

Routing::get('motherboards', 'MotherboardController');
Routing::post('motherboardsEdit', 'MotherboardController');
Routing::get('addMotherboard', 'MotherboardController');
Routing::get('deleteMotherboard', 'MotherboardController');
Routing::get('updateMotherboard', 'MotherboardController');

Routing::run($path);