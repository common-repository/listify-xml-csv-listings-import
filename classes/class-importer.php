<?php
if ( ! class_exists( 'WPAI_Listify_Add_On_Importer' ) ) {
    class WPAI_Listify_Add_On_Importer {

		protected $add_on;
		public $logger = null;
		public $post_type = '';

		public function __construct( RapidAddon $addon_object, $post_type ) {
			$this->add_on = $addon_object;
			$this->post_type = $post_type;
		}

        public function import( $post_id, $data, $import_options, $article ) {
			$this->import_listing_data( $post_id, $data, $import_options, $article );
		}

		public function import_listing_data( $post_id, $data, $import_options, $article ) {
			$importer = new WPAI_Listify_Listing_Importer( $this->add_on );

			$importer->import_listing_details( $post_id, $data, $import_options, $article );
			$importer->import_listing_location( $post_id, $data, $import_options, $article );
		}

		public function can_update_meta( $field, $import_options ) {
            return $this->add_on->can_update_meta( $field, $import_options );
        }

        public function can_update_image( $import_options ) {
            return $this->add_on->can_update_image( $import_options );
        }	
	}	
}