<?php
use Cake\Routing\Router;

Router::plugin('Oracle', function ($routes) {
    $routes->fallbacks('InflectedRoute');
});
