<?php
/*
Plugin Name: MyPay payment gateway for WooCommerce
Description: Provides payment gateway service for WooCommerce
Version: 1.1.1
Author: MyPay
Author URI: http://www.mypay.co.uk/
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/*  Copyright 2020  mypay  (support@mypay.co.uk)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    /**
	 * Initialise mypay Gateway
	**/
    add_action('plugins_loaded', 'init_mypay', 0);
}


function init_mypay() {

	if (!class_exists('WC_Payment_Gateway')) {
		return;
	}

	add_filter('plugin_action_links', 'mypay_add_action_plugin', 10, 5);

	include( 'classes/mypay.php' );

	add_filter('woocommerce_payment_gateways', 'add_mypay_hosted' );

}

function mypay_add_action_plugin($actions, $plugin_file)
{
	static $plugin;

	if (!isset($plugin))
	{
		$plugin = plugin_basename(__FILE__);
	}

	if ($plugin == $plugin_file)
	{
		$actions = array_merge(array('settings' => '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=wc_mypay_hosted') . '">' . __('Settings', 'General') . '</a>'), $actions);
	}

	return $actions;
}

function add_mypay_hosted($methods) {
	$methods[] = 'WC_mypay_Hosted';
	return $methods;
}
