<?php
namespace Tailor\Inc;

use User;
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

        $this->db->insert( store_prefix() . 'tailor_orders_measures_pants', $pant );
        $this->db->insert( store_prefix() . 'tailor_orders_measures_shirts', $shirt );
    }
}