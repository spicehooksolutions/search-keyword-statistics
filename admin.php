<?php
/*
* Plugin Name: Search Keyword Statistics
* Description: Keep statistics of keywords being searched on your website. Now, you can see who, when and what searched on your site with device, browser and OS report.
* Tags: search keyword statistics, woocommerce product search, keyword search, post search, search records, keywords, search query, incoming terms, user search keyword statistics
* Version: 3.1
* Stable tag: 3.1
* Author: SpiceHook Solutions
* Author URI: https://www.spicehook.biz
* Text Domain: sh-language
* Requires at least: 4.6.0
* Tested up to: 6.2
* Requires PHP: 5.6
* PHP Version Tested up to: 8.1
* License: GPLv2
*/

// protection
if (!defined('ABSPATH')) {
    exit;
}
global $wpdb;

define('SS_TABLE', $wpdb->prefix . 'search_statistics');
define('SS_BG_VERSION', 31);

require 'browserinfo/BrowserDetection.php';
require 'ss-wp-dashboard-widget.php';

/* Handlers for Detect and save search keywords */
add_action('wp_loaded', 'ss_keyword_trace');

function ss_keyword_trace() {

    require 'ss-keyword-trace.php';
    if (strpos($_SERVER['PHP_SELF'], 'wp-admin') == false) {
        if (!empty($_GET['s'])) {
            $keyword = trim($_GET['s']);

            save_keyword($keyword, @$_SERVER['HTTP_REFERER']);
        }
    }
}

/* Admin menu */
add_action('admin_menu', 'ss_menu');

add_action('plugins_loaded', 'ss_plugins_update');

function ss_plugins_update() {
    include plugin_dir_path(__FILE__) . 'update.php';
}

function admin_dashboard() {
    require 'ss-admin-dashboard.php';
}

function ss_export_remove_func()
{
    require 'ss-export-remove.php';
}

function ss_sales_report_call()
{
require 'reports/ss_sales_report.php';
}

function ss_menu() {
    add_menu_page(__( 'Keyword Statistics', 'sh-language' ),__( 'Keyword Statistics', 'sh-language' ), 'manage_options', 'ss-menu', 'admin_dashboard');

    add_submenu_page(
        'ss-menu',
		__( 'Export and Truncate', 'sh-language' ),
		'Export and Truncate',
		'manage_options',
		'ss_export_remove',
		'ss_export_remove_func'
	);
 
    if ( class_exists( 'WooCommerce' ) ) {
        add_submenu_page( 
            'ss-menu',
            __( 'Sales Reports', 'sh-language' ),
            __( 'Sales Reports', 'sh-language' ),
            'manage_options', 
            'ss_sales_report', 
            'ss_sales_report_call'
           );
      }
}

add_action('admin_footer','ss_regsitering_scripts');

function ss_regsitering_scripts()
{

wp_register_script('ss-dashboard-js', plugin_dir_url(__FILE__) . 'js/datatables.min.js');
wp_enqueue_script('ss-dashboard-js');

wp_register_script('ss-dashboard-js-ui', plugin_dir_url(__FILE__) . '/js/jquery-ui.min.js');
wp_enqueue_script('ss-dashboard-js-ui');

wp_register_script('ss-dashboard-js-ui-date', plugin_dir_url(__FILE__) . 'js/jquery.ui.datepicker.min.js');
wp_enqueue_script('ss-dashboard-js-ui-date');

wp_enqueue_style(
    'ss-data-table-style',
    plugin_dir_url(__FILE__) . 'js/datatables.min.css'
);
// wp_enqueue_style(
//     'ss-demo-table-style',
//     plugin_dir_url(__FILE__) . 'css/demo_table.css'
// );

wp_enqueue_style(
    'ss-main-style',
    plugin_dir_url(__FILE__) . 'css/style.css?v='.rand()
);

wp_enqueue_style(
    'ss-jquery-ui-style',
    plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css'
);

wp_enqueue_style(
    'ss-jquery-ui-datepicker-style',
    plugin_dir_url(__FILE__) . 'css/jquery.ui.datepicker.min.css'
);

wp_enqueue_script('ss-boostrap-js',plugin_dir_url(__FILE__) . 'js/popper.min.js', array('jquery'), 101, false);
    wp_enqueue_script('ss-boostrap-js',plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), 101, false);

    wp_enqueue_style( 'ss-boostrap-css', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), false, 'all' );

    wp_enqueue_style( 'ss-additional-css', plugin_dir_url(__FILE__) . 'css/sales_reports.css', array(), false, 'all' ); 
    wp_enqueue_style( 'ss-fontawesome-css', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', array(), false, 'all' );

}


// Function used in the action hook
function add_dashboard_widgets() {
    wp_add_dashboard_widget('dashboard_widget', 'Keyword Statistics', 'ss_dashboard_widget_function');
}

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets');



/* Uninstall and Activation handlers */
register_activation_hook(__FILE__, 'ss_activate');
register_deactivation_hook(__FILE__, 'ss_deactivate');

register_uninstall_hook(__FILE__, 'ss_deactivate_uninstall');

function ss_activate() {
    global $wpdb;
    if ($wpdb->get_var("SHOW TABLES LIKE '" . SS_TABLE . "'") != SS_TABLE) {
        $query = "CREATE TABLE IF NOT EXISTS " . SS_TABLE . "( 
			id INT PRIMARY KEY AUTO_INCREMENT, 
			keywords VARCHAR(255) NOT NULL, 
			query_date varchar(12) NOT NULL, 
            search_count INT(11) NOT NULL DEFAULT  '0', 
			repeat_count INT,
			source VARCHAR(50),
            user varchar(10),
            agent varchar(150)
		)";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $wpdb->query($query);
    }
}

function ss_deactivate_uninstall() {
    global $wpdb;
    $query = "DROP TABLE IF EXISTS " . SS_TABLE;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $wpdb->query($query); 
    delete_option('SS_BG_VERSION_UPDATE_31');
    delete_option('SS_BG_VERSION');
}

function ss_deactivate() {
   
}
require 'review.php';
?>