<?php

namespace Tailor\Inc;

use Group;
use Tendoo_Module;

class Setup extends Tendoo_Module
{
    public function install( $store_prefix )
    {
        $columns            =   $this->db->list_fields( $store_prefix . 'nexo_commandes' );

        if( ! in_array( 'ASSIGNED_TAILOR', $columns ) ) {
            $this->db->query( 'ALTER TABLE `'. $this->db->dbprefix . $store_prefix .'nexo_commandes` ADD `ASSIGNED_TAILOR` int(11) NOT NULL AFTER `AUTHOR`;');
            $this->db->query( 'ALTER TABLE `'. $this->db->dbprefix . $store_prefix .'nexo_commandes` ADD `TAILOR_PRIORITY` varchar(20) NOT NULL AFTER `AUTHOR`;');
            $this->db->query( 'ALTER TABLE `'. $this->db->dbprefix . $store_prefix .'nexo_commandes` ADD `TAILOR_DELIVERY_DATE` datetime NOT NULL AFTER `AUTHOR`;');
            $this->db->query( 'ALTER TABLE `'. $this->db->dbprefix . $store_prefix .'nexo_commandes` ADD `TAILOR_EXPIRATION_DATE` datetime NOT NULL AFTER `AUTHOR`;');
            $this->db->query( 'ALTER TABLE `'. $this->db->dbprefix . $store_prefix .'nexo_commandes` ADD `TAILOR_STATE` varchar(200) NOT NULL AFTER `AUTHOR`;');
            $this->db->query( 'ALTER TABLE `'. $this->db->dbprefix . $store_prefix .'nexo_commandes` ADD `ASSIGNED_IMAGE` varchar(200) NOT NULL AFTER `AUTHOR`;');
        }

        $this->db->query( 'CREATE TABLE IF NOT EXISTS `' . $this->db->dbprefix . $store_prefix . 'tailor_orders_measures_pants` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `REF_ORDER` varchar( 200 )  NOT NULL,
            `STYLE` varchar(200) NULL,
            `AROUND_WAIST` varchar(200) NULL,
            `AROUND_HIP` varchar(200) NULL,
            `FRONT_RISE` varchar(200) NULL,
            `AROUND_THIGH` varchar(200) NULL,
            `LENGTH` varchar(200) NULL,
            `INSEAM` varchar(200) NULL,
            `AROUND_KNEE` varchar(200) NULL,
            `LEG_OPENING` varchar(200) NULL,
            `DATE_CREATION` datetime not null,
            `DATE_MODIFICATION` datetime not null,
            `AUTHOR` int(11) NOT NULL,
            PRIMARY KEY (`ID`)
        )' );

        $this->db->query( 'CREATE TABLE IF NOT EXISTS `' . $this->db->dbprefix . $store_prefix . 'tailor_orders_measures_shirts` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `REF_ORDER` varchar( 200 )  NOT NULL,
            `STYLE` varchar(200) NULL,
            `AROUND_NECK` varchar(200) NULL,
            `NECK_SHOULDER_LENGTH` varchar(200) NULL,
            `SHOULDER_SHOULDER` varchar(200) NULL,
            `AROUND_CHEST` varchar(200) NULL,
            `SLEEVE_LENGTH` varchar(200) NULL,
            `SLEEVE_CUFF` varchar(200) NULL,
            `AROUND_WAIST` varchar(200) NULL,
            `FRONT_LENGTH` varchar(200) NULL,
            `BACK_LENGTH` varchar(200) NULL,
            `MEASURE_HIP` varchar(200) NULL,
            `MEASURE_SHIRT_HEM` varchar(200) NULL,
            `DATE_CREATION` datetime not null,
            `DATE_MODIFICATION` datetime not null,
            `AUTHOR` int(11) NOT NULL,
            PRIMARY KEY (`ID`)
        )' );

        /**
         * If the tailor role is not defined
         */
        if ( $this->auth->get_group_id( 'tailor.worker' ) === false ) {
            Group::create(
                'tailor.worker',
                __( 'Tailor', 'tailor' ),
                true,
                __( 'is the worker.', 'tailor' )
            );

            $this->auth->allow_group( 'tailor.worker', 'edit_profile' );
        }
    }
}