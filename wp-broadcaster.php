<?php
/**
 * Plugin Name: WP Broadcaster
 * Plugin URI: http://usabilitydynamics.com/products/wp-broadcaster/
 * Description: Plugin allows you to broadcast post objects to other wordpress powered sites.
 * Author: Usability Dynamics, Inc.
 * Version: 1.0
 * Author URI: http://usabilitydynamics.com
 *
 * Copyright 2013  Usability Dynamics, Inc.   ( email : info@usabilitydynamics.com )
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

define( 'WPB_VERSION', '1.0' );
define( 'WPB_DIR',  dirname( plugin_basename( __FILE__ ) ) );
define( 'WPB_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPB_URL',  plugin_dir_url( __FILE__ ) );
define( 'WPB_DOMAIN', 'wpb_domain' );

define( 'WPB_OPTION_SLUG', 'wpb_options' );
define( 'WPB_CREDENTIALS', 'credentials' );
define( 'WPB_BROADCASTS',  'broadcasts' );
define( 'WPB_FILTERS',     'filters' );

define( 'FILTER_SPECIFIC_OBJECT', 1 );

require_once WPB_PATH.'core/wpb_core.php';
require_once WPB_PATH.'core/wpb_settings.php';
require_once WPB_PATH.'core/wpb_manage_page.php';
require_once WPB_PATH.'core/wpb_item_credential.php';
require_once WPB_PATH.'core/wpb_item_broadcast.php';
require_once WPB_PATH.'core/wpb_credentials.php';
require_once WPB_PATH.'core/wpb_broadcasts.php';
require_once WPB_PATH.'core/wpb_filters.php';
require_once WPB_PATH.'core/wpb_broadcast_api.php';
require_once WPB_PATH.'core/wpb_helper_functions.php';
require_once WPB_PATH.'core/wpb_ajax.php';
require_once WPB_PATH.'core/wpb_actions.php';
require_once WPB_PATH.'core/wpb_item_filter.php';
require_once WPB_PATH.'core/filters/wpb_specific_object_filter.php';

global $wpb, $wpb_credentials, $wpb_broadcasts, $wpb_filters, $wpb_settings;

$wpb              = new WPB_Core();
$wpb_settings     = new WPB_Settings();
$wpb_credentials  = new WPB_Credentials();
$wpb_broadcasts   = new WPB_Broadcasts();
$wpb_filters      = new WPB_Filters();

//** Make things happen */
add_action( 'init', array( $wpb, 'init' ) );