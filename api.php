<?php
$Routes->get( 'tailor/orders/{id}', 'Tailor\Api\Controller@getOrders' );
$Routes->post( 'tailor/orders/{id}', 'Tailor\Api\Controller@saveStatus' );
$Routes->post( 'tailor/orders', 'Tailor\Api\Controller@getReportOrders' );