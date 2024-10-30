<?php

/*
Plugin Name: WP All Import - Listify Add-On
Plugin URI: https://www.wpallimport.com/
Description: Supporting imports into the Listify theme.
Version: 1.1.2
Author: Soflyy
*/

if ( ! class_exists( 'WPAI_Listify_Add_On' ) ) {

    final class WPAI_Listify_Add_On {

        protected static $instance;

        protected $add_on;

        static public function get_instance() {
            if ( self::$instance == NULL ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        protected function __construct() {
            $this->constants();
            $this->includes();
            $this->hooks();

            // Important: Preserve 'listify_addon' for backwards compatibility
            $this->add_on = new RapidAddon( 'Listify Add-On', 'listify_addon' );

            add_action( 'init', array( $this, 'init' ) );
        }

        public function init() {
            // Helper functions to get post type and other things
            $helper = new WPAI_Listify_Add_On_Helper();
            $this->post_type = $helper->get_post_type();

			// Importing 'Listings'
            $this->listing_fields();

            $this->add_on->set_import_function( array( $this, 'import' ) );

            $this->add_on->run( array(
                'themes'        => array( 'Listify' ),
                'post_types'    => array( 'job_listing' )
            ) );

            $notice_message = 'The Listify Add-On requires WP All Import <a href="https://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wpjm" target="_blank">Pro</a> or <a href="https://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>, and the <a href="https://astoundify.com/go/wp-all-import-buy-listify/">Listify</a> theme.';

            $this->add_on->admin_notice( $notice_message, array( 'themes' => array( 'Listify' ) ) );
        }

        public function get_add_on() {
            return $this->add_on;
        }



        public function listing_fields() {
            $fields = new WPAI_Listify_Listing_Field_Factory( $this->add_on );

            $fields->add_field( 'listing_location' );
            $fields->add_field( 'listing_details' );
        }
        
        public function import( $post_id, $data, $import_options, $article ) {
            $importer = new WPAI_Listify_Add_On_Importer( $this->add_on, $this->post_type );
            $importer->import( $post_id, $data, $import_options, $article );
        }

        public function constants() {
            if ( ! defined( 'WPAI_LISTIFY_PLUGIN_DIR_PATH' ) ) {
                // Dir path
                define( 'WPAI_LISTIFY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
            }

            if ( ! defined( 'WPAI_LISTIFY_ROOT_DIR' ) ) {
                // Root directory for the plugin.
                define( 'WPAI_LISTIFY_ROOT_DIR', str_replace( '\\', '/', dirname( __FILE__ ) ) );
            }

            if ( ! defined( 'WPAI_LISTIFY_PLUGIN_PATH' ) ) {
                // Path to the main plugin file.
                define( 'WPAI_LISTIFY_PLUGIN_PATH', WPAI_LISTIFY_ROOT_DIR . '/' . basename( __FILE__ ) );
            }
        }

        public function includes() {
            include WPAI_LISTIFY_PLUGIN_DIR_PATH . 'rapid-addon.php';
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            include_once( WPAI_LISTIFY_PLUGIN_DIR_PATH . 'classes/class-field-factory-listings.php' );
            include_once( WPAI_LISTIFY_PLUGIN_DIR_PATH . 'classes/class-helper.php' );
            include_once( WPAI_LISTIFY_PLUGIN_DIR_PATH . 'classes/class-importer.php' );
            include_once( WPAI_LISTIFY_PLUGIN_DIR_PATH . 'classes/class-importer-listings.php' );
            include_once( WPAI_LISTIFY_PLUGIN_DIR_PATH . 'classes/class-importer-listings-location.php' );
        }

        public function hooks() {
            $helper = new WPAI_Listify_Add_On_Helper();

            add_action( 'admin_enqueue_scripts', array( $helper, 'admin_scripts' ), 10, 1 );
            add_action( 'pmxi_before_xml_import', array( $helper, 'sync_import_options' ), 10, 1 );

            // Prevent Listify from removing location data after importing
            add_action( 'pmxi_before_post_import', array( $helper, 'wpai_listify_addon_ensure_location_data_is_imported' ), 10, 1 );

            add_action( 'pmxi_saved_post', array( $helper, 'add_featured_img_to_gallery' ), 10, 3 );

            // Ensure gallery fields aren't removed by updating custom fields
            add_filter('pmxi_custom_field_to_update', array( $helper, 'do_not_update_gallery_cf' ), 10, 4);
            add_filter('pmxi_custom_field_to_delete', array( $helper, 'do_not_delete_gallery_cf' ), 10, 5);
        }
    }

    WPAI_Listify_Add_On::get_instance();
}
