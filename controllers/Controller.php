<?php
namespace Tailor\Controllers;

use Tendoo_Module;

class Controller extends Tendoo_Module
{
    public function showOrderReady()
    {
        $this->Gui->set_title( store_title( __( 'Ready Orders', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'reports/ready-orders/gui' );
    }

    public function showOrderNotReady()
    {
        $this->Gui->set_title( store_title( __( 'Not Ready Orders', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'reports/not-ready-orders/gui' );
    }

    public function showReadyDelivery()
    {
        $this->Gui->set_title( store_title( __( 'Not Ready Before Delivery', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'reports/not-ready-delivery/gui' );
    }

    public function showOrderInProgress()
    {
        $this->Gui->set_title( store_title( __( 'Order In Progress', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'reports/in-progress/gui' );
    }

    public function showNewOrders()
    {
        $this->Gui->set_title( store_title( __( 'New Orders', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'reports/new-orders/gui' );
    }

    public function showNotReady()
    {
        $this->Gui->set_title( store_title( __( 'Not Ready Orders Today', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'reports/not-ready-today/gui' );
    }

    public function settings()
    {
        $this->Gui->set_title( store_title( __( 'Tailor Settings', 'tailor' ) ) );
        $this->load->module_view( 'tailor', 'settings/gui' );
    }
}