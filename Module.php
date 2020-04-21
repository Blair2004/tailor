<?php
namespace Tailor;

use Tendoo_Module;
use Tailor\Inc\Actions;
use Tailor\Inc\Filters;

include_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

class Module extends Tendoo_Module
{
    public function __construct()
    {
        parent::__construct();
        
        $this->actions  =  new Actions;
        $this->filters  =  new Filters;

        $this->events->add_action( 'do_enable_module', [ $this->actions, 'enable_module' ]);
        $this->events->add_filter( 'checkout_header_menus_1', [ $this->filters, 'checkout_buttons' ]);
        $this->events->add_action( 'load_pos_footer', [ $this->actions, 'load_pos_footer' ]);
        $this->events->add_filter( 'after_submit_order', [ $this->filters, 'afterSubmitOrder' ], 10 );
        $this->events->add_filter( 'post_order_details', [ $this->filters, 'filter_post' ], 10, 3 );
        $this->events->add_filter( 'put_order_details', [ $this->filters, 'filter_post' ], 10, 4 );
        $this->events->add_filter( 'nexo_full_order', [ $this->filters, 'nexo_full_order' ], 10, 2 );
        $this->events->add_filter( 'nexo_commandes_loaded', [ $this->filters, 'nexo_commandes_loaded' ]);
        $this->events->add_action( 'nexo_orders_footer', [ $this->actions, 'nexo_orders_footer' ]);
        $this->events->add_action( 'load_dashboard', [ $this->actions, 'load_dashboard' ], 20 );
        $this->events->add_filter( 'admin_menus', [ $this->filters, 'admin_menus' ], 30 );
        // $this->events->add_filter( 'after_order_placed_details', [ $this->filters, 'update_placed_details' ], 10, 3 );
    }
}

new Module;