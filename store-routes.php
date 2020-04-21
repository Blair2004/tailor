<?php
global $StoreRoutes;

$StoreRoutes->get( '/tailor/reports/orders-ready', 'Tailor\Controllers\Controller@showOrderReady' );
$StoreRoutes->get( '/tailor/reports/orders-not-ready', 'Tailor\Controllers\Controller@showOrderNotReady' );
$StoreRoutes->get( '/tailor/reports/not-ready-delivery', 'Tailor\Controllers\Controller@showReadyDelivery' );
$StoreRoutes->get( '/tailor/reports/orders-in-progress', 'Tailor\Controllers\Controller@showOrderInProgress' );
$StoreRoutes->get( '/tailor/reports/new-orders', 'Tailor\Controllers\Controller@showNewOrders' );
$StoreRoutes->get( '/tailor/reports/no-ready', 'Tailor\Controllers\Controller@showNotReady' );
$StoreRoutes->get( '/tailor/settings', 'Tailor\Controllers\Controller@settings' );
$StoreRoutes->get( '/tailor/ping-telegram', 'Tailor\Controllers\Controller@pingTelegram' );