<?php
/**
 * Woo Customer Insight plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
}

class WooCustomerInsightSchema {

	public function generate_tables() {
		global $wpdb;
		$woo_customer_insight_info =  "CREATE TABLE IF NOT EXISTS wci_activity (
			id int(20) NOT NULL AUTO_INCREMENT,
			session_id int(20) NOT NULL,
			session_key varchar(200) NOT NULL,
			user_id  varchar(200) DEFAULT NULL,
			user_email varchar(200) DEFAULT NULL,
			is_user int(10) DEFAULT NULL,
			user_ip varchar(30) DEFAULT NULL,
			country varchar(200) DEFAULT NULL,
			date date DEFAULT NULL,
			information LONGBLOB DEFAULT NULL,
			visited_url LONGBLOB DEFAULT NULL,
			page_id int(20) DEFAULT NULL,
			page_title varchar(200) DEFAULT NULL,
			spent_time int(20) DEFAULT NULL,
			clicked_button varchar(100) DEFAULT NULL,
			date_time datetime DEFAULT NULL,
			PRIMARY KEY (id)
			   ) ENGINE=InnoDB;";
		$wpdb->query($woo_customer_insight_info);

		$create_btn_click_table =  "CREATE TABLE IF NOT EXISTS wci_events (
			id int(20) NOT NULL AUTO_INCREMENT,
			session_id int(20) NOT NULL,
			session_key varchar(200) NOT NULL,
			user_id varchar(200) DEFAULT NULL,
			user_email varchar(200) DEFAULT NULL,
			user_ip varchar(40) DEFAULT NULL,
			country varchar(100) DEFAULT NULL,
			prod_id int(20) DEFAULT NULL,
			product LONGBLOB DEFAULT NULL,
			button_name varchar(100) DEFAULT NULL,
			page_url LONGBLOB DEFAULT NULL,
			date datetime DEFAULT '0000-00-00 00-00-00',
			count int(30) DEFAULT NULL,
			date_without_time date DEFAULT NULL,
			PRIMARY KEY (id)
			   ) ENGINE=InnoDB;";
		$wpdb->query($create_btn_click_table);

		$create_usr_profile =  "CREATE TABLE IF NOT EXISTS wci_history (
			id int(20) NOT NULL AUTO_INCREMENT,
			user_id int(30) DEFAULT NULL,
			user_name varchar(200) DEFAULT NULL,
			email varchar(200) DEFAULT NULL,
			date datetime DEFAULT '0000-00-00 00-00-00',
			role varchar(100) DEFAULT NULL,
			login_time datetime DEFAULT '0000-00-00 00-00-00',
			logout_time datetime DEFAULT '0000-00-00 00-00-00',
			status varchar(100) DEFAULT NULL,
			PRIMARY KEY (id)
			   ) ENGINE=InnoDB;";
		$wpdb->query($create_usr_profile);

		$wci_user_purchased_history =  "CREATE TABLE IF NOT EXISTS wci_user_purchased_history (
			id int(20) NOT NULL AUTO_INCREMENT,
			user_ip varchar(30) DEFAULT NULL,
			user_id varchar(20) DEFAULT NULL,
			user_email varchar(100) DEFAULT NULL,
			product_id int(50) DEFAULT NULL,
			product_name varchar(200) DEFAULT NULL,
			PRIMARY KEY (id)
			   ) ENGINE=InnoDB;";
		$wpdb->query($wci_user_purchased_history);

		$wci_create_usr_profile_updated =  "CREATE TABLE IF NOT EXISTS wci_user_profile_updated (
                        id int(20) NOT NULL AUTO_INCREMENT,
                           user_id int(20) UNIQUE NOT NULL,
                           user_name varchar(200) DEFAULT NULL,
                           email varchar(200) DEFAULT NULL,
                           date datetime DEFAULT '0000-00-00 00-00-00',
                           role varchar(30) DEFAULT NULL,
                           login_time datetime DEFAULT '0000-00-00 00-00-00',
                           logout_time datetime DEFAULT '0000-00-00 00-00-00',
                           PRIMARY KEY (id)
                                   ) ENGINE=InnoDB;";
                $wpdb->query( $wci_create_usr_profile_updated );


		$wci_abandon_cart =  "CREATE TABLE IF NOT EXISTS wci_abandon_cart (
			id int(20) NOT NULL AUTO_INCREMENT,
			user_email varchar(100) NOT NULL,
			order_id int(30) UNIQUE NOT NULL,
			date datetime DEFAULT NULL,
			time_difference int(30) DEFAULT NULL,
			PRIMARY KEY (id)
			   ) ENGINE=InnoDB;";
		$wpdb->query($wci_abandon_cart);

		$wci_successful_purchases =  "CREATE TABLE IF NOT EXISTS wci_successful_purchases (
			id int(20) NOT NULL AUTO_INCREMENT,
			user_name varchar(200) NOT NULL,
			user_email varchar(200) NOT NULL,
			order_id int(30) UNIQUE NOT NULL,
			order_status varchar(30) NOT NULL,
			date datetime DEFAULT NULL,
			products LONGBLOB DEFAULT NULL,
			coupon_code varchar(100) DEFAULT NULL,
			coupon_amount varchar(100) DEFAULT NULL,
			total_price int(30) DEFAULT NULL,
			discount_type varchar(100) DEFAULT NULL,
			PRIMARY KEY (id)
			   ) ENGINE=InnoDB;";
		$wpdb->query($wci_successful_purchases);
	
		$wci_user_session =  "CREATE TABLE IF NOT EXISTS wci_user_session (
                        id int(40) NOT NULL AUTO_INCREMENT,
                        session_id int(20) NOT NULL,
			user_id varchar(200) NOT NULL,
			user_name varchar(200) NOT NULL,
			country varchar(100) NOT NULL,
			is_cart int(10) NOT NULL,
                        product_key varchar(200) NOT NULL,
			product_data LONGBLOB DEFAULT NULL,
                        is_checkout int(10) NOT NULL,
                        is_payment int(10) NOT NULL,
			order_id int(30) NOT NULL,
                        payment_success int(20) NOT NULL,
                        payment_failure int(20) NOT NULL,
                        session_value LONGBLOB DEFAULT NULL,
			date datetime DEFAULT '0000-00-00 00-00-00',
                        PRIMARY KEY (id)
                           ) ENGINE=InnoDB;";
                $wpdb->query($wci_user_session);
			
	}

}
