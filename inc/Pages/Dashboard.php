<?php  
/**
 * @package OverviewPlugin
 */
namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\ManagerCallbacks;

/**
 * 
 */
class Dashboard extends BaseController 
{
 
	public $settings;

	public $callbacks;
    public $callbacks_mngr;


	public $pages = array();

	//public $subpages = array();

	

	public function register() {

		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();
		$this->callbacks_mngr = new ManagerCallbacks();

		$this->setPages();


		//$this->setSubPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage('Dashboard')->register();
	}
     //addSubPages($this->subpages)->
     
	// Set the pages that comes with the plugin
	
	public function setPages()
	{
		$this->pages = array (
			array(
			'page_title' => 'Overview Plugin', 
			'menu_title' =>'Overview',
		    'capability' =>'manage_options',
		    'menu_slug' => 'overview_plugin',
		    'callback' => array( $this->callbacks, 'adminDashboard'),
		    'icon_url'=> 'dashicons-visibility', 
		    'position' => 110 
			)
		
		);

	}

	// Set the settings that the custom plugin have
	
	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'overview_plugin_settings',
				'option_name' => 'overview_plugin',
				'callback' => array( $this->callbacks_mngr, 'checkboxSanitize' )
			)
		);
		$this->settings->setSettings( $args );
	}

	//Set the section of the custom plugin

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'overview_admin_index',
				'title' => 'Settings Manager',
				'callback' => array( $this->callbacks_mngr, 'adminSectionManager' ),
				'page' => 'overview_plugin'
			)
		);
		$this->settings->setSections( $args );
	}

	//Set the fields to display un my custom area
	//The label should always match the id. 

	 public function setFields()
	{
		$args = array();
		foreach ( $this->managers as $key => $value ) {
			$args[] = array(
				'id' => $key,
				'title' => $value,
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'overview_plugin',
				'section' => 'overview_admin_index',
				'args' => array(
					'option_name' => 'overview_plugin',
					'label_for' => $key,
					'class' => 'ui-toggle'
				)
			);
		}
		$this->settings->setFields( $args );
	}
}