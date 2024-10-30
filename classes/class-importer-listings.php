<?php
if ( ! class_exists( 'WPAI_Listify_Listing_Importer' ) ) {
    class WPAI_Listify_Listing_Importer extends WPAI_Listify_Add_On_Importer {

		protected $add_on;
		public $helper;

        public function __construct( RapidAddon $addon_object ) {
			$this->add_on = $addon_object;
			$this->helper = new WPAI_Listify_Add_On_Helper();
        }

        public function import_listing_details( $post_id, $data, $import_options, $article ) {
            // Import text fields

            $fields = [
                '_application',
                '_company_website',
                '_phone',
                '_claimed',
                '_featured',
                'add_featured_img_to_gallery'
            ];

            $listify_addon_listify_version = $this->helper->get_theme_version();
            $listify_addon_listify_version = str_replace( ".", "", $listify_addon_listify_version );

            $social_setting = get_option( 'theme_mods_listify' );

            $social_setting_child = get_option( 'theme_mods_listify-child' );

            if ( ( array_key_exists('social-association',$social_setting) and $social_setting['social-association'] == 'listing' ) or is_array($social_setting_child) and array_key_exists('social-association',$social_setting_child) and $social_setting_child['social-association'] == 'listing') {
                // If they're associating social profile fields with the listings.
                // Add the fields.

                array_push( $fields, '_company_facebook', '_company_twitter', '_company_googleplus', '_company_linkedin', '_company_instagram', '_company_github', '_company_pinterest' );

            }

            // update everything in fields arrays
            foreach ( $fields as $field ) {

                if ( empty( $article['ID'] ) or $this->can_update_meta( $field, $import_options ) ) {

                    $this->helper->update_meta( $post_id, $field, $data[$field] );

                }
            }

            // update listing expiration date
            $field = '_job_expires';

            $date = $data[$field];

            $date = strtotime( $date );

            if ( empty( $article['ID'] ) or $this->can_update_meta( $field, $import_options ) ) {

                $job_status = 'publish';

                if ( !empty( $date ) ) {

                    $date = date( 'Y-m-d', $date );

                    $this->helper->update_meta( $post_id, $field, $date );

                    // Set job listing as 'expired' if the date is older than today

                    $today_date = date( 'Y-m-d', current_time( 'timestamp' ) );
                    $job_status = $date < $today_date ? 'expired' : 'publish';

                }

                wp_update_post( [ 'ID' => $post_id, 'post_status' => $job_status ] );

            }

            // clear image fields to override import settings
            $fields = [
                '_gallery_images',
                '_gallery'
            ];

            if ( empty( $article['ID'] ) or $this->can_update_image( $import_options ) ) {

                foreach ($fields as $field) {
                    delete_post_meta($post_id, $field);
                }

            }

            // update company avatar/logo
            if ( empty( $article['ID'] ) or ( $this->can_update_meta( '_company_avatar', $import_options ) ) ) {
                $avatar_url = wp_get_attachment_url( $data['_company_avatar']['attachment_id'] );

                $this->helper->update_meta( $post_id, '_company_avatar', $avatar_url );
                $this->helper->update_meta( $post_id, '_company_avatar_attachment_id', $data['_company_avatar']['attachment_id'] );
            }

            // update video
            if ( empty( $article['ID'] ) or $this->can_update_meta( '_company_video', $import_options ) ) {

                if ( $data['video_type'] == 'external' ) {

                    $this->helper->update_meta( $post_id, '_company_video', $data['_company_video_url'] );

                } elseif ( $data['video_type'] == 'local' ) {

                    $attachment_id = $data['_company_video_id']['attachment_id'];

                    $url = wp_get_attachment_url( $attachment_id );

                    $this->helper->update_meta( $post_id, '_company_video', $url );
                }
            }

            // update hours
            $field = '_job_hours';

            if ( empty( $article['ID'] ) or $this->can_update_meta( $field, $import_options ) ) {

                if ( $listify_addon_listify_version < "200" ) {
                    // Use the old array style for the older versions that didn't allow multiple hours.

                    $hours = array(
                        1 => array(
                            'start' => $data['monday_open'],
                            'end' => $data['monday_close']
                        ),
                        2 => array(
                            'start' => $data['tuesday_open'],
                            'end' => $data['tuesday_close']
                        ),
                        3 => array(
                            'start' => $data['wednesday_open'],
                            'end' => $data['wednesday_close']
                        ),
                        4 => array(
                            'start' => $data['thursday_open'],
                            'end' => $data['thursday_close']
                        ),
                        5 => array(
                            'start' => $data['friday_open'],
                            'end' => $data['friday_close']
                        ),
                        6 => array(
                            'start' => $data['saturday_open'],
                            'end' => $data['saturday_close']
                        ),
                        0 => array(
                            'start' => $data['sunday_open'],
                            'end' => $data['sunday_close']
                    ) );

                    foreach( $hours as $day => $key ) {

                        foreach( $key as $subkey => $value ) {

                            if ( strtotime( $value ) != false ) {
                                
                                $new_value = $value;
                                    
                            } else {

                                $new_value = ucwords( $value );

                            }

                            $hours[$day][$subkey] = $new_value;

                        }
                    }

                    $this->helper->update_meta( $post_id, $field, $hours );
                } else {
                    // New use the new array structure that allows multiple business hours.

                    $hours = array(
                        'mon' => array( $data['monday_open'], $data['monday_close' ] ),
                        'tue' => array( $data['tuesday_open'], $data['tuesday_close'] ),
                        'wed' => array( $data['wednesday_open'], $data['wednesday_close'] ),
                        'thu' => array( $data['thursday_open'], $data['thursday_close'] ),
                        'fri' => array( $data['friday_open'], $data['friday_close'] ),
                        'sat' => array( $data['saturday_open'], $data['saturday_close'] ),
                        'sun' => array( $data['sunday_open'], $data['sunday_close'] )
                    );

                    foreach ( $hours as $day => $times ) {

                        if ( isset( $times[0] ) ) { $open = $times[0]; } // All open times.
                        if ( isset( $times[1] ) ) { $close = $times[1]; } // All close times.
                        unset( $hours[ $day ] ); // Reset array keys.

                        // Implode with comma in case they put multiple times
                        $open = explode( ",", $open ); 
                        $close = explode( ",", $close );


                        if ( count( $open ) > 1 && count( $close ) > 1 ) {
                            // There are multiple open and close times.

                            $x = 0; // The array key we'll use.
                            
                            foreach ( $open as $key => $time ) {
                                
                                if ( $datetime = strtotime( trim( $time ) ) ) {
                                    // They entered a valid date/time
                                    $hours[ $day ][ $x ]['open'] = trim( $time );

                                } else {
                                    // It's probably "24h" or "Closed"
                                    $hours[ $day ][ $x ]['open'] = trim( ucwords( $time ) );                            
                                }

                                if ( isset( $close[ $key ] ) ) {

                                    if ( $datetime = strtotime( trim( $close[ $key ] ) ) ) {
                                        // They entered a valid date/time
                                        $hours[ $day ][ $x ]['close'] = trim( $close[ $key ] );
                                    } else {
                                        // It's probably "24h" or "Closed"
                                        $hours[ $day ][ $x ]['close'] = trim( ucwords( $close[ $key ] ) );

                                    }

                                } else {
                                    // Uh oh, they didn't enter the same amount of close times.
                                    $hours[ $day ][ $x ]['close'] = null;

                                }

                                $x++; // Next inner array key.

                            }

                        } else {
                            // There is only 1 opening / closing time.

                            $x = 0;

                            if ( $datetime = strtotime( $open[0] ) ) {
                                
                                $hours[ $day ][ $x ]['open'] = $open[0];

                            } else {

                                $hours[ $day ][ $x ]['open'] = ucwords( $open[0] );

                            }

                            if ( $datetime = strtotime( $close[0] ) ) {

                                $hours[ $day ][ $x ]['close'] = $close[0];

                            } else {
                            
                                $hours[ $day ][ $x ]['close'] = ucwords( $close[0] );

                            }

                        }

                    }

                    $this->helper->update_meta( $post_id, $field, $hours );

                }

            }

            $field = '_job_hours_timezone';

            if ( empty( $article['ID'] ) or $this->can_update_meta( $field, $import_options ) ) {

                if ( $listify_addon_listify_version >= "200" ) {

                    if ( empty( $data[ $field ] ) ) {
                        // They did not enter a timezone, so we'll default to UTC.
                        $this->helper->update_meta( $post_id, $field, "UTC+0" );
                        $this->helper->update_meta( $post_id, '_job_hours_gmt', '0' );
                        $this->helper->log( "<strong>Timezone</strong>: No timezone entered, using UTC+0." );

                    } else {
                        // Replace spaces with underscores so that we can find the timezone.
                        $timezone = str_replace( " ", "_", $data[ $field ] );

                        $soflyy_check_valid_utc = array( 'UTC-12', 'UTC-11:30', 'UTC-10:30', 'UTC-10', 'UTC-9:30', 'UTC-9', 'UTC-8:30', 'UTC-8', 'UTC-7:30', 'UTC-7', 'UTC-6:30', 'UTC-6', 'UTC-5:30', 'UTC-5', 'UTC-4:30', 'UTC-4', 'UTC-3:30', 'UTC-3', 'UTC-2:30', 'UTC-2', 'UTC-1:30', 'UTC-1', 'UTC-0:30', 'UTC+0', 'UTC+0:30', 'UTC+1', 'UTC+1:30', 'UTC+2', 'UTC+2:30', 'UTC+3', 'UTC+3:30', 'UTC+4', 'UTC+4:30', 'UTC+5', 'UTC+5:30', 'UTC+5:45', 'UTC+6', 'UTC+6:30', 'UTC+7', 'UTC+7:30', 'UTC+8', 'UTC+8:30', 'UTC+8:45', 'UTC+9', 'UTC+9:30', 'UTC+10', 'UTC+10:30', 'UTC+11', 'UTC+11:30', 'UTC+12', 'UTC+12:45', 'UTC+13', 'UTC+13:45', 'UTC+14' );

                        if ( stristr( $timezone, "UTC" ) ) {
                            // They used UTC in the string, so we'll check against valid UTC offsets.
                            $timezone = strtoupper( $timezone );
                            if ( in_array( $timezone, $soflyy_check_valid_utc ) ) {

                                $offset = str_replace( array( "UTC", "+", ":30", ":45" ), array( "", "", ".5", ".75" ), $timezone ); // sanitize the offset for Listify
                                $this->helper->log( "<strong>Timezone</strong>: Setting timezone to " . $timezone . "." );
                                $timezone = str_replace( array( ":30", ":45" ), array( ".5", ".75" ), $timezone ); // Sanitize the UTC value for Listify
                            }

                        } else {
                            // They entered a timezone string instead of a UTC offset.

                            if ( in_array( $timezone, timezone_identifiers_list() ) ) {
                                // It is a valid timezone, let's get the offset.
                                $Date_Time_Zone = new DateTimeZone( $timezone );
                                $date = new DateTime(null, $Date_Time_Zone);
                                $offset = $Date_Time_Zone->getOffset( $date )/60/60;
                                $this->helper->log( "<strong>Timezone</strong>: Valid timezone found '" . $timezone . "'. Offset: UTC" . $offset );

                            } else {
                                // It's not a valid timezone.
                                $this->helper->log( "<strong>Timezone</strong>: This is not a valid timezone: " . $timezone . ". Using UTC+0." );
                                $timezone = "UTC+0";
                                $offset = "0";

                            }
                        }

                        $this->helper->update_meta( $post_id, $field, $timezone );
                        $this->helper->update_meta( $post_id, '_job_hours_gmt', $offset );

                    }

                }
            }
        }

        public function import_listing_location( $post_id, $data, $import_options, $article ) {
            $location_importer = new WPAI_Listify_Listing_Location_Importer( $this->add_on );
            $location_importer->import( $post_id, $data, $import_options, $article );
        }
    }

    if ( ! function_exists( 'listify_addon_listing_gallery' ) ) {
        function listify_addon_listing_gallery( $post_id, $attachment_id, $image_filepath, $import_options ) {

            $helper = new WPAI_Listify_Add_On_Helper();

            $image_fields = [
                "_company_avatar"
            ];

            foreach ( $image_fields as $image_field ) {
                if ( $attachment_id == get_post_meta( $post_id, "{$image_field}_attachment_id", true ) ) {
                    $helper->log("<strong>Listify Add-On:</strong> Image $attachment_id is already attached to this listing.");

                    return;
                }
            }

            $helper->log("<strong>Listify Add-On:</strong> Importing listing image.");

            // build gallery_images
            $new_url = wp_get_attachment_url( $attachment_id );

            $urls = get_post_meta( $post_id, '_gallery_images', true );

            $new_urls = array();

            if ( ! empty( $urls ) ) {
                foreach( $urls as $key => $url ) {

                    $new_urls[] = $url;

                }
            }

            $new_urls[] = $new_url;

            $new_urls = array_unique($new_urls);

            $helper->update_meta( $post_id, '_gallery_images', $new_urls );

            //build gallery
            $new_id = $attachment_id;

            $ids = get_post_meta( $post_id, '_gallery', true );

            $new_ids = array();

            if ( ! empty( $ids ) ) {
                foreach( $ids as $key => $id ) {

                    $new_ids[] = $id;

                }
            }

            $new_ids[] = $new_id;

            $new_ids = array_unique($new_ids);

            $helper->update_meta( $post_id, '_gallery', $new_ids );

        }
    }
}
