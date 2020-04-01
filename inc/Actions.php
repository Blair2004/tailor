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
                var_dump( $store );
                $this->setup->install( $store_prefix );
            });
        }
    }

    public function load_pos_footer( $namespace )
    {
        $this->load->module_view( 'tailor', 'register.footer' );
    }
}