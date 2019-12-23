<?php  
/**
 * @package OverviewPlugin
 */

namespace Inc\Base;

class Activate
{
   public static function activate(){
   	flush_rewrite_rules( );

   	$default = array();

   if( ! get_option('overview_plugin')){
   	update_option('overview_plugin',$default);
   }
     if( ! get_option('overview_plugin_cpt')){
   	update_option('overview_plugin_cpt',$default);
   }
   if ( ! get_option( 'overview_plugin_tax' ) ) {
      update_option( 'overview_plugin_tax', $default );
    }
  }
}