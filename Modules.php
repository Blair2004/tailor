<?php
namespace Tailor;

use Tendoo_Module;
use Tailor\Inc\Actions;
use Tailor\Inc\Filters;

class Module extends Tendoo_Module
{
    public function __construct()
    {
        parent::__construct();
        
        $this->actions  =  new Actions;
        $this->actions  =  new Filters;

        $this->events->add_action( 'do_enable_module', [ $this->actions, 'enable_module' ]);
        $this->events->add_action( 'load_pos_footer', [ $this->actions, 'load_pos_footer' ]);
    }
}