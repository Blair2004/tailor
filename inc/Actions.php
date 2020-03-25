<?php
namespace Tailor\Inc;

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
        }
    }

    public function load_pos_footer( $namespace )
    {
        $this->load->module_view( 'tailor', 'register.footer' );
    }
}