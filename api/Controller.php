<?php
namespace Tailor\Api;

use User;
use Requests;
use Tendoo_Api;
use Carbon\Carbon;
use Tailor\Inc\TailorUtility;

class Controller extends Tendoo_Api
{
    /**
     * Search items
     * @param void
     * @return json
     */
    public function getOrders( $id )
    {
        $this->load->module_model( 'nexo', 'Nexo_Orders_Model', 'order_model' );
        
        $utility    =   new TailorUtility;
        $order      =   $this->order_model->get( $id );
        $measures   =   $utility->getOrderMeasures( $id );

        return $this->response([
            'order'         =>  $order,
            'assignated'    =>  User::get( $order[ 'ASSIGNED_TAILOR' ] ),
            'measures'      =>  $measures
        ]);
    }

    public function saveStatus( $id )
    {
        $status     =   $this->post( 'status' );
        $this->db->where( 'ID', $id )
            ->update( store_prefix() . 'nexo_commandes', [
                'STATUS'     =>  $status
            ]);
        
        return $this->response([
            'status'    =>  'success',
            'message'   =>  __( 'The order status has been changed.', 'tailor' )
        ]);
    }

    public function getReportOrders()
    {
        $startDate  =   ! empty( $this->post( 'startDate' ) ) ? 
            Carbon::parse( $this->post( 'startDate' ) )->startOfDay()->toDateTimeString() : 
            Carbon::parse( date_now() )->startOfDay()->toDateTimeString();
        
        $endDate  =   ! empty( $this->post( 'endDate' ) ) ? 
            Carbon::parse( $this->post( 'endDate' ) )->endOfDay()->toDateTimeString() : 
            Carbon::parse( date_now() )->endOfDay()->toDateTimeString();


        $filter     =   $this->post( 'filter' );
        $response   =   [];

        switch( $filter ) {
            case 'not-ready' :
                $response   =   $this->filterOrders( [ 'pending', 'processing' ], $startDate, $endDate );
            break;
            case 'ready-orders' :
                $response   =   $this->filterOrders( [ 'completed' ], false, false );
            break;
            case 'in-progress' :
                $response   =   $this->filterOrders( [ 'processing' ], false, false );
            break;
            case 'new-orders' :
                $response   =   $this->filterOrders( [ 'pending' ], false, false, [ 'DATE_CREATION' => 'desc' ]);
            break;
            case 'not-ready-today' :
                $response   =   $this->filterOrders( [ 'pending', 'processing' ], false, false, [ 'DATE_CREATION' => 'desc' ], [
                    'TAILOR_DELIVERY_DATE'  =>  Carbon::parse( date_now() )->startOfDay()->toDateTimeString()
                ]);
            break;
            case 'ready-orders' :
                $response   =   $this->filterOrders( [ 'completed' ], false, false, [ 'DATE_CREATION' => 'desc' ], [
                    'TAILOR_DELIVERY_DATE'  =>  Carbon::parse( date_now() )->startOfDay()->toDateTimeString()
                ]);
            break;
            case 'not-ready-delivery' :
                $response   =   $this->filterOrders( [ 'pending', 'processing' ], false, false, [ 'DATE_CREATION' => 'desc' ], [
                    'TAILOR_DELIVERY_DATE <'  =>  Carbon::parse( date_now() )->startOfDay()->toDateTimeString()
                ]);
            break;
        }

        return $this->response( $response );
    }

    public function filterOrders( $status, $startDate, $endDate, $orderBy = [], $extraWhere = [])
    {
        $this->load->module_model( 'nexo', 'NexoCustomersModel', 'customer_model' );
        $this->db->where_in( 'STATUS', $status );

        if ( $startDate && $endDate ) {
            $this->db
                ->where( 'DATE_CREATION >=', $startDate )
                ->where( 'DATE_CREATION <=', $endDate );
        }

        if ( ! empty( $extraWhere ) ) {
            foreach( $extraWhere as $key => $value ) {
                $this->db->where( $key, $value );
            }
        }

        if ( ! empty( $orderBy ) ) {
            foreach( $orderBy as $key => $value ) {
                $this->db->order_by( $key, $value );
            }
        }
            
        $orders     =   $this->db->get( store_prefix() . 'nexo_commandes' )
            ->result_array();

        return collect( $orders )->map( function( $order ) {
            $order[ 'tailor' ]      =   User::get( $order[ 'ASSIGNED_TAILOR' ] );
            $order[ 'customer' ]    =   ( array ) @$this->customer_model->get( $order[ 'REF_CLIENT' ] )[0];
            return $order;
        })->toArray();
    }

    public function pingTelegram()
    {
        // Requests
    }
}