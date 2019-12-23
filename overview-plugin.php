<?php  
/**
 * @package OverviewPlugin
 */
/*
Plugin Name: Overview Plugin
Plugin URI: https://freetouraachen.com/plugin
Description: This is my overview plugin
version: 1.0.0
Author: Andrew Ebare Ogbeide
Author URI: https://freetouraachen.com
License: GPLv2 or later
Text Domain: overview-plugin
 */

//if this file is called firectly, ABORT!!
defined('ABSPATH') or die('Hey.Stop there attempt failed');

//Require once the composer autoload
if (file_exists(dirname(__FILE__).'/vendor/autoload.php')) {
	require_once dirname(__FILE__). '/vendor/autoload.php';
}





/**
 * This code runs during the plugin activation
 */

function activate_overview_plugin(){
	Inc\Base\Activate::activate();
}

register_activation_hook(__FILE__,'activate_overview_plugin');

/**
 * This code runs during the plugin deactivation
 */

function deactivate_overview_plugin(){
	Inc\Base\Deactivate::Deactivate();
}

register_deactivation_hook(__FILE__,'deactivate_overview_plugin');

/**
 * Initialize all the core classes of the plugin
 */

if (class_exists('Inc\\Init')) {
	Inc\Init::register_services
	();
}


