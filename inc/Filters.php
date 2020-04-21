<?php
namespace Tailor\Inc;

use Tendoo_Module;
use Tailor\Inc\TailorUtility;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;

class Filters extends Tendoo_Module
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkout_buttons( $menus )
    {
        $menus[]    =   [
            'class' =>  'default tailor',
            'text'  =>  __( 'Tailor', 'nexo' ),
            'icon'  =>  'home',
        ];

        return $menus;
    }

    public function admin_menus( $menus )
    {
        $menus[ 'tailor' ]  =   [
            [
                'title'     =>      __( 'Tailor Reports', 'tailor' ),
                'href'      =>      '#',
                'icon'      =>      'fa fa-line-chart',
                'disable'   =>      true
            ], [
                'title'     =>  __( 'Orders Ready At Delivery', 'tailor' ),
                'href'      =>      site_url([ 'dashboard', store_slug(), 'tailor', 'reports', 'orders-ready' ])
            ], [
                'title'     =>  __( 'Orders Ready After Delivery', 'tailor' ),
                'href'      =>      site_url([ 'dashboard', store_slug(), 'tailor', 'reports', 'orders-not-ready' ])
            ], [
                'title'     =>  __( 'Not Ready Till Delivery', 'tailor' ),
                'href'      =>      site_url([ 'dashboard', store_slug(), 'tailor', 'reports', 'not-ready-delivery' ])
            ], [
                'title'     =>  __( 'Orders In Progress', 'tailor' ),
                'href'      =>      site_url([ 'dashboard', store_slug(), 'tailor', 'reports', 'orders-in-progress' ])
            ], [
                'title'     =>  __( 'New Orders', 'tailor' ),
                'href'      =>      site_url([ 'dashboard', store_slug(), 'tailor', 'reports', 'new-orders' ])
            ], [
                'title'     =>  __( 'Not Ready Orders', 'tailor' ),
                'href'      =>      site_url([ 'dashboard', store_slug(), 'tailor', 'reports', 'no-ready' ])
            ]
        ];

        $menus[ 'nexo_settings' ][]     =   [
            'title'     =>  __( 'Tailor Settings', 'tailor' ),
            'href'      =>  site_url([ 'dashboard', store_slug(), 'tailor', 'settings' ])
        ];

        return $menus;
    }

    public function nexo_full_order( $details, $order )
    {
        $utility    =   new TailorUtility;
        $measures   =   $utility->getOrderMeasures( $order[ 'ID' ] );
        $details[ 'measures' ]  =   $measures;
        return $details;
    }

    public function afterSubmitOrder( $details )
    {
        extract( $details );

        /**
         * @param array $current_order
         * @param array $order_details
         * @param array $data
         */
        $utility    =   new TailorUtility;
        $utility->registerMeasures( $current_order[0][ 'ID' ], $data[ 'metas' ][ 'tailor' ] );

        return $details;
    }

    public function filter_post( $order_details, $post_data, $order_id = null, $current_order = [] )
    {
        $order_details[ 'ASSIGNED_TAILOR' ]         =   $post_data[ 'metas' ][ 'tailor' ][ 'assigned_tailor' ];
        $order_details[ 'TAILOR_DELIVERY_DATE' ]    =   $post_data[ 'metas' ][ 'tailor' ][ 'delivery' ];
        $order_details[ 'TAILOR_PRIORITY' ]         =   $post_data[ 'metas' ][ 'tailor' ][ 'priority' ];
        $order_details[ 'TAILOR_EXPIRATION_DATE' ]  =   $post_data[ 'metas' ][ 'tailor' ][ 'expiration_date' ];
        $order_details[ 'TAILOR_STATE' ]            =   'pending';
        $order_details[ 'ASSIGNED_IMAGE' ]          =   $this->converToImage( @$post_data[ 'metas' ][ 'tailor' ][ 'assigned_image' ], 'tailor-' . ( @$current_order[0][ 'CODE' ] ?? $order_details[ 'CODE' ] ) );
        
        return $order_details;
    }

    private function converToImage( $base64, $name )
    {
        if( ! empty( $base64 ) ){
            $decoder    =   new Base64ImageDecoder( $base64, $allowedFormats = [ 'jpeg', 'png', 'gif']);
            $relative   =   'public/upload/' . ( intval( get_store_id() ) === 0 ? '' : 'store_' . get_store_id() . '/' ) . $name . '.' . $decoder->getFormat();
            $path       =   FCPATH . $relative; 
            file_put_contents( $path, $decoder->getDecodedContent() );
            return site_url( $relative );
        }
        return '';
    }

    public function update_placed_details( $updated_details, $data, $fresh_order )
    {
        if ( isset( $data[ 'metas' ][ 'tailor' ] ) ) {
            $utility    =   new TailorUtility;
            $utility->registerMeasures( $fresh_order[ 'ID' ], $data[ 'metas' ][ 'tailor' ] );
        }
        return $updated_details;
    }

    public function nexo_commandes_loaded( $crud )
    {
        $crud->add_action(
			__( 'Tailor Options', 'tailor'),
			'',
			'#',
			'toggle-tailor fa fa-cogs'
        );

        return $crud;
    }
}