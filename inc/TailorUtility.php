<?php
namespace Tailor\Inc;

use User;
use Carbon\Carbon;
use Tendoo_Module;

class TailorUtility extends Tendoo_Module
{
    public function registerMeasures( $order_id, $measures )
    {
        $pant   =   collect( $measures[ 'pant' ] )->mapWithKeys( function( $measure, $key ) {
            return [
                \strtoupper( $key ) => $measure
            ];
        })->toArray();

        $shirt  =   collect( $measures[ 'shirt' ] )->mapWithKeys( function( $measure, $key ) {
            return [
                \strtoupper( $key ) => $measure
            ];
        })->toArray();

        $pant[ 'DATE_CREATION' ]    =   $shirt[ 'DATE_CREATION' ]   =   date_now();
        $pant[ 'AUTHOR' ]           =   $shirt[ 'AUTHOR' ]          =   User::id();
        $pant[ 'REF_ORDER' ]        =   $shirt[ 'REF_ORDER' ]       =   $order_id;

        $measure_exists     =   $this->getOrderMeasures( $order_id );

        if ( empty( $measure_exists[ 'pants' ] ) || empty( $measure_exists[ 'pants' ] ) ) {
            $this->db->insert( store_prefix() . 'tailor_orders_measures_pants', $pant );
            $this->db->insert( store_prefix() . 'tailor_orders_measures_shirts', $shirt );
        } else {
            $this->db->where( 'REF_ORDER', $order_id )->update( store_prefix() . 'tailor_orders_measures_pants', $pant );
            $this->db->where( 'REF_ORDER', $order_id )->update( store_prefix() . 'tailor_orders_measures_shirts', $shirt );
        }
    }

    public function getOrderMeasures( $order_id )
    {
        $pant  =   (array) @$this->db->where( 'REF_ORDER', $order_id )
            ->get( store_prefix() . 'tailor_orders_measures_pants' )
            ->result_array()[0];

        $shirt  =   (array) @$this->db->where( 'REF_ORDER', $order_id )
            ->get( store_prefix() . 'tailor_orders_measures_shirts' )
            ->result_array()[0];

        $this->load->module_model( 'nexo', 'Nexo_Orders_Models', 'order_model' );
        $order      =   $this->order_model->get( $order_id );

        $priority           =   $order[ 'TAILOR_PRIORITY' ];
        $assigned_tailor    =   $order[ 'ASSIGNED_TAILOR' ];
        $delivery           =   Carbon::parse( $order[ 'TAILOR_DELIVERY_DATE' ] )->format( 'm/d/Y' );
        $expiration_date    =   Carbon::parse( $order[ 'TAILOR_EXPIRATION_DATE' ] )->format( 'm/d/Y' );
        $state              =   $order[ 'TAILOR_STATE' ];

        return compact( 
            'shirt', 
            'pant', 
            'priority',
            'assigned_tailor',
            'delivery',
            'expiration_date',
            'state' 
        );
    }
}