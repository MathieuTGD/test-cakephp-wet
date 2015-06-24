<?php
use Cake\Routing\Router;

Router::plugin('EAccess', function ($routes) {
    $routes->fallbacks('InflectedRoute');
});
