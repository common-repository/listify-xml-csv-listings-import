<?php
if ( ! class_exists( 'WPAI_Listify_Listing_Field_Factory' ) ) {	
    class WPAI_Listify_Listing_Field_Factory {
        
        protected $add_on;
        
        public function __construct( RapidAddon $addon_object ) {
            $this->add_on = $addon_object;
        }
        
        public function add_field( $field_type ) {
            switch ( $field_type ) {
                case 'listing_location':
                    $this->listing_location();
                    break;

                case 'listing_details':
                    $this->listing_details();
                    break;
                
                default:
                    break;
            }
        }

        public function listing_location() {
            $this->add_on->add_field(
                '_job_location',
                'Location',
                'radio',
                array(
                    'search_by_address' => array(
                        'Search by Address',
                        $this->add_on->add_options(
                            $this->add_on->add_field(
                                'job_address',
                                'Job Address',
                                'text'
                            ),
                            'Google Geocode API Settings',
                            array(
                                $this->add_on->add_field(
                                    'address_geocode',
                                    'Request Method',
                                    'radio',
                                    array(
                                        'address_google_developers' => array(
                                            'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                            $this->add_on->add_field(
                                                'address_google_developers_api_key', 
                                                'API Key', 
                                                'text'
                                            ),
                                            'Up to 2,500 requests per day and 5 requests per second.'
                                        ),
                                        'address_google_for_work' => array(
                                            'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                            $this->add_on->add_field(
                                                'address_google_for_work_client_id', 
                                                'Google for Work Client ID', 
                                                'text'
                                            ), 
                                            $this->add_on->add_field(
                                                'address_google_for_work_digital_signature', 
                                                'Google for Work Digital Signature', 
                                                'text'
                                            ),
                                            'Up to 100,000 requests per day and 10 requests per second'
                                        )
                                    ) // end Request Method options array
                                ), // end Request Method nested radio field 

                            ) // end Google Geocode API Settings fields
                        ) // end Google Gecode API Settings options panel
                    ), // end Search by Address radio field
                    'search_by_coordinates' => array(
                        'Enter Coordinates',
                        $this->add_on->add_field(
                            'job_lat', 
                            'Latitude', 
                            'text', 
                            null, 
                            'Example: 34.0194543'
                        ),
                        $this->add_on->add_options( 
                            $this->add_on->add_field(
                                'job_lng', 
                                'Longitude', 
                                'text', 
                                null, 
                                'Example: -118.4911912'
                            ), 
                            'Google Geocode API Settings', 
                            array(
                                $this->add_on->add_field(
                                    'coord_geocode',
                                    'Request Method',
                                    'radio',
                                    array(
                                        'coord_google_developers' => array(
                                            'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                            $this->add_on->add_field(
                                                'coord_google_developers_api_key', 
                                                'API Key', 
                                                'text'
                                            ),
                                            'Up to 2,500 requests per day and 5 requests per second.'
                                        ),
                                        'coord_google_for_work' => array(
                                            'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                            $this->add_on->add_field(
                                                'coord_google_for_work_client_id', 
                                                'Google for Work Client ID', 
                                                'text'
                                            ), 
                                            $this->add_on->add_field(
                                                'coord_google_for_work_digital_signature', 
                                                'Google for Work Digital Signature', 
                                                'text'
                                            ),
                                            'Up to 100,000 requests per day and 10 requests per second'
                                        )
                                    ) // end Geocode API options array
                                ), // end Geocode nested radio field 
                            ) // end Geocode settings
                        ) // end coordinates Option panel
                    ) // end Search by Coordinates radio field
                ),
                'Leave this blank if the location is not important.' // end Job Location radio field
            );
        }


        
        public function listing_details() {
            $this->add_on->disable_default_images();
            $this->add_on->import_images( 'listify_addon_listing_gallery', 'Listing Images' );

            $this->add_on->add_field( '_application', 'Contact Email/URL', 'text', null, 'This field is required for the "application" area to appear beneath the listing.');

            $this->add_on->add_field( '_company_website', 'Company Website', 'text' );

            $this->add_on->add_field( '_company_avatar', 'Company Logo/Avatar', 'image' );

            $social_setting = get_option( 'theme_mods_listify' );

            $social_setting_child = get_option( 'theme_mods_listify-child' );

            if ( ( is_array( $social_setting ) and array_key_exists( 'social-association', $social_setting ) and $social_setting['social-association'] == 'listing' ) or ( is_array( $social_setting_child ) and array_key_exists( 'social-association', $social_setting_child ) and $social_setting_child['social-association'] == 'listing' ) ) {

                // If the user has decided to associate the social profile fields with listings
                // We'll add them as fields in the import here.

                $this->add_on->add_options(
                    null,
                    'Social Profile Fields',
                    array(
                            $this->add_on->add_field( '_company_facebook', 'Facebook URL', 'text' ),

                            $this->add_on->add_field( '_company_twitter', 'Twitter URL', 'text' ),

                            $this->add_on->add_field( '_company_googleplus', 'Google+ URL', 'text' ),

                            $this->add_on->add_field( '_company_linkedin', 'LinkedIn URL', 'text' ),

                            $this->add_on->add_field( '_company_instagram', 'Instagram URL', 'text' ),

                            $this->add_on->add_field( '_company_github', 'GitHub URL', 'text' ),

                            $this->add_on->add_field( '_company_pinterest', 'Pinterest URL', 'text' )
                        )
                    );

            }

            $this->add_on->add_field( 'video_type', 'Company Video', 'radio',
                array(
                    'external' => array(
                        'Externally Hosted',
                        $this->add_on->add_field( '_company_video_url', 'Video URL', 'text')
                    ),
                    'local' => array(
                        'Locally Hosted',
                        $this->add_on->add_field( '_company_video_id', 'Upload Video', 'file')
                    )
                )
            );

            $this->add_on->add_field( '_featured', 'Featured Listing', 'radio', 
                array(
                    '0' => 'No',
                    '1' => 'Yes'
                ),
                'Featured listings will be sticky during searches, and can be styled differently.'
            );

            $this->add_on->add_field( '_claimed', 'Claimed', 'radio', 
                array(
                    '0' => 'No',
                    '1' => 'Yes'
                ),
                'The owner has been verified.'
            );

            $this->add_on->add_field( '_job_expires', 'Listing Expiry Date', 'text', null, 'Use any format supported by the PHP <b>strtotime</b> function. That means pretty much any human-readable date will work.');

            $this->add_on->add_field( '_phone', 'Company phone', 'text' );

            $this->add_on_listify_version = get_option( 'listify_version' );

            $this->add_on_listify_version = str_replace( ".", "", $this->add_on_listify_version );

            if ( $this->add_on_listify_version < "200" ) {
                
                $this->add_on->add_title( 'Hours of Operation', 'Use Closed, 24h, or a time (for example: 8:30 am).' );

            } else {

                $this->add_on->add_title( 'Hours of Operation', 'Use Closed, 24h, or a time (for example: 8:30 am). Seperate multiple open close times with a comma, for example: 8:30 am, 12:00 pm.' );

            }

            $this->add_on->add_text( '<br><b>Monday</b>' );

            $this->add_on->add_field( 'monday_open', 'Open', 'text' );

            $this->add_on->add_field( 'monday_close', 'Close', 'text' );

            $this->add_on->add_text( '<br><br><b>Tuesday</b>' );

            $this->add_on->add_field( 'tuesday_open', 'Open', 'text' );

            $this->add_on->add_field( 'tuesday_close', 'Close', 'text' );

            $this->add_on->add_text( '<br><br><b>Wednesday</b>' );

            $this->add_on->add_field( 'wednesday_open', 'Open', 'text' );

            $this->add_on->add_field( 'wednesday_close', 'Close', 'text' );

            $this->add_on->add_text( '<br><br><b>Thursday</b>' );

            $this->add_on->add_field( 'thursday_open', 'Open', 'text' );

            $this->add_on->add_field( 'thursday_close', 'Close', 'text' );

            $this->add_on->add_text( '<br><br><b>Friday</b>' );

            $this->add_on->add_field( 'friday_open', 'Open', 'text' );

            $this->add_on->add_field( 'friday_close', 'Close', 'text' );

            $this->add_on->add_text( '<br><br><b>Saturday</b>' );

            $this->add_on->add_field( 'saturday_open', 'Open', 'text' );

            $this->add_on->add_field( 'saturday_close', 'Close', 'text' );

            $this->add_on->add_text( '<br><br><b>Sunday</b>' );

            $this->add_on->add_field( 'sunday_open', 'Open', 'text' );

            $this->add_on->add_field( 'sunday_close', 'Close', 'text' );

            if ( $this->add_on_listify_version >= "200" ) {

                $this->add_on->add_field( '_job_hours_timezone', 'Timezone', 'text', null, 'Enter a valid timezone. Example values: "America/Los Angeles", "America/Indiana/Indianapolis", "UTC+5", "UTC+5:30" (without quotes). Leave blank to use UTC+0.' );

            }

            // Check whether the user wants to add the featured image to the gallery as well.
            $this->add_on->add_options(
                null,
                'Advanced Options',
                array(
                    $this->add_on->add_field(
                        'add_featured_img_to_gallery', 
                        'Add featured image to gallery?',
                        'radio',
                        array(
                            '0' => "No",
                            '1' => "Yes"
                            )
                        )
                    )
                );
        }


    }
}
