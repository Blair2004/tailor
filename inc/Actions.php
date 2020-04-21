<?php
namespace Tailor\Inc;

use Tendoo_Module;

class Actions extends Tendoo_Module
{
    public function __construct()
    {
        parent::__construct();
    }

    public function enable_module( $namespace )
    {
        if ( $namespace === 'tailor' ) {
            $this->setup    =   new Setup;
            $this->load->model( 'Nexo_Stores' );
            $stores     =   $this->Nexo_Stores->get();

            collect( $stores )->prepend([
                'ID'    =>  0
            ])->each( function( $store ) {
                $store_prefix   =   store_prefix( $store[ 'ID' ] );
                $this->setup->install( $store_prefix );
            });
        }
    }

    public function load_dashboard()
    {
        $config     =   $this->config->item( 'nexo_orders_status' );
        $config[ 'pending' ]    =   __( 'Pending', 'tailor' );
        $config[ 'completed' ]  =   __( 'Ready', 'tailor' );
        $this->config->set_item( 'nexo_orders_status', $config );
    }

    public function nexo_orders_footer()
    {
        $this->load->module_view( 'tailor', 'orders.footer' );
    }

    public function load_pos_footer( $namespace )
    {
        $this->load->module_view( 'tailor', 'register.footer' );
    }
}