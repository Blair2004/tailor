<?php
namespace Tailor\Inc;

use Tendoo_Module;
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

    public function filter_post( $order_details, $post_data )
    {
        $order_details[ 'ASSIGNED_TAILOR' ]         =   $post_data[ 'metas' ][ 'tailor' ][ 'assigned_tailor' ];
        $order_details[ 'TAILOR_DELIVERY_DATE' ]    =   $post_data[ 'metas' ][ 'tailor' ][ 'delivery' ];
        $order_details[ 'TAILOR_EXPIRATION_DATE' ]  =   $post_data[ 'metas' ][ 'tailor' ][ 'expiration_date' ];
        $order_details[ 'TAILOR_STATE' ]            =   'pending';
        $order_details[ 'ASSIGNED_IMAGE' ]          =   $this->converToImage( @$post_data[ 'metas' ][ 'tailor' ][ 'assigned_image' ], 'tailor-' . $order_details[ 'CODE' ] );
        
        return $order_details;
    }

    private function converToImage( $base64, $name )
    {
        if( ! empty( $base64 ) ){
            $decoder    =   new Base64ImageDecoder( $base64, $allowedFormats = [ 'jpeg', 'png', 'gif']);
            $relative   =   'public/upload/' . ( intval( get_store_id() ) === 0 ? '' : 'store-' . get_store_id() . '/' ) . $name . '.' . $decoder->getFormat();
            $path       =   FCPATH . $relative; 
            file_put_contents( $path, $decoder->getDecodedContent() );
            return site_url( $relative );
        }
        return '';
    }
}