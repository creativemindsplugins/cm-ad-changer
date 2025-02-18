<?php

/**
 * CM Ad Changer
 *
 * @author CreativeMinds (https://www.cminds.com/wordpress-plugins-library/adchanger/)
 * @copyright Copyright (c) 2013, CreativeMinds
 */
CMAC_Data::instance();

class CMAC_Data {

	public static $calledClassName;
	protected static $instance		 = NULL;
	protected static $campaignsTable = NULL;
	protected static $imagesTable	 = NULL;
	protected static $historyTable	 = NULL;

	public static function instance() {
		$class = __CLASS__;
		if ( !isset( self::$instance ) && !( self::$instance instanceof $class ) ) {
			self::$instance = new $class;
		}
		return self::$instance;
	}

	public function __construct() {
		global $wpdb;

		if ( empty( self::$calledClassName ) ) {
			self::$calledClassName = __CLASS__;
		}

		self::$campaignsTable	 = $wpdb->prefix . CMAC_CAMPAIGNS_TABLE;
		self::$imagesTable		 = $wpdb->prefix . CMAC_IMAGES_TABLE;
		self::$historyTable		 = $wpdb->prefix . CMAC_HISTORY_TABLE;
	}

	/**
	 * Performs campaign storage
	 * @return Array
	 * @param Array   $data  Array of fields
	 */
	public static function cmac_handle_campaigns_post( $data ) {
		global $wpdb;
		$errors = array();

		/*
		 * VALIDATIONS START
		 */
		if ( empty( $data ) ) {
			return array( 'errors' => array( 'No data entered' ), 'fields_data' => $data );
		}

		if ( !isset( $data[ 'banner_weight' ] ) || !is_array( $data[ 'banner_weight' ] ) ) {
			$data[ 'banner_weight' ] = array();
		}

		if ( empty( $data[ 'title' ] ) ) {
			$errors[] = 'Campaign Name field is empty';
		}

		$data[ 'comment' ] = sanitize_text_field( $data[ 'comment' ] );
		if ( strlen( $data[ 'comment' ] ) > 500 ) {
			$errors[] = 'Note is too long';
		}

		if ( isset( $data[ 'campaign_id' ] ) && !is_numeric( $data[ 'campaign_id' ] ) ) {
			$errors[] = 'Unknown campaign';
		}

		if ( !isset( $data[ 'banner_display_method' ] ) ) {
			$errors[] = 'Please select "Banner display method"';
		}
		if ( isset( $data[ 'banner_display_method' ] ) && $data[ 'banner_display_method' ] == 'selected' && empty( $data[ 'banner_filename' ] ) ) {
			$errors[] = 'Please select a banner';
		}
		if ( isset( $data[ 'banner_display_method' ] ) && $data[ 'banner_display_method' ] == 'selected' && !empty( $data[ 'banner_filename' ] ) && empty( $data[ 'selected_banner' ] ) ) {
			$data[ 'selected_banner' ] = $data[ 'banner_filename' ][ 0 ];
		}

		$banner_weight_sum	 = 0;
		$banners_natural	 = true;
		foreach ( $data[ 'banner_weight' ] as $banner_weight ) {
			if ( !is_numeric( $banner_weight ) || ((int) $banner_weight != (float) $banner_weight) || $banner_weight < 0 ) {
				$errors[]		 = 'Please enter numeric positive Banner Weights';
				$banners_natural = false;
				break;
			}

			$banner_weight_sum+=(int) $banner_weight;
		}

		if ( $banners_natural ) {
			if ( $banner_weight_sum > 1000 ) {
				$errors[] = 'Banner Weight sum is too big';
			}
		}

		if ( !empty( $errors ) ) {
			return array( 'errors' => $errors, 'fields_data' => $data );
		}

		// VALIDATIONS END
		// 1. Inserting the campaign record
		if ( !isset( $data[ 'max_clicks' ] ) ) {
			$data[ 'max_clicks' ] = 0;
		}
		if ( !isset( $data[ 'max_impressions' ] ) ) {
			$data[ 'max_impressions' ] = 0;
		}
		
		$meta = array();
		$meta[ 'rotated_random' ] = !empty( $data[ 'rotated_random' ] ) ? $data[ 'rotated_random' ] : false;
		array_filter( $meta );
		$fmeta = maybe_serialize( $meta );
				
		if ( !isset( $data[ 'campaign_id' ] ) ) {
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . self::$campaignsTable . ' SET `title`=%s, `link`=%s, `banner_display_method`=%s, `max_impressions`=%d, `max_clicks`=%d, `comment`=%s, `status`=%d, `meta`=%s', $data[ 'title' ], $data[ 'link' ], $data[ 'banner_display_method' ], $data[ 'max_impressions' ], $data[ 'max_clicks' ], $data[ 'comment' ], isset( $data[ 'status' ] ) ? 1 : 0, $fmeta ));

			$new_campaign_id = $wpdb->insert_id;
		} else {
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . self::$campaignsTable . ' SET `title`=%s, `link`=%s, `banner_display_method`=%s, `max_impressions`=%d, `max_clicks`=%d, `comment`=%s, `status`=%d, `meta`=%s WHERE `campaign_id`="' . $data[ 'campaign_id' ] . '"', $data[ 'title' ], $data[ 'link' ], $data[ 'banner_display_method' ], $data[ 'max_impressions' ], $data[ 'max_clicks' ], $data[ 'comment' ], isset( $data[ 'status' ] ) ? 1 : 0, $fmeta ));
			$new_campaign_id = $data[ 'campaign_id' ];
		}

		// 2. Inserting banner images

		$new_filenames = array();
		if ( isset( $new_campaign_id ) ) {
			if ( !isset( $data[ 'banner_filename' ] ) || !is_array( $data[ 'banner_filename' ] ) ) {
				$data[ 'banner_filename' ] = array();
			}
			$existing_filenames = $wpdb->get_col( 'SELECT filename FROM ' . self::$imagesTable . ' WHERE `campaign_id`="' . $new_campaign_id . '"' );

			$deleted_filenames = array();

			foreach ( $existing_filenames as $existing_filename ) {
				if ( !in_array( $existing_filename, $data[ 'banner_filename' ] ) ) {
					$deleted_filenames[] = $existing_filename;
				}
			}

			foreach ( $data[ 'banner_filename' ] as $data_filename ) {
				if ( !in_array( $data_filename, $existing_filenames ) ) {
					$new_filenames[] = $data_filename;
				}
			}

			// cleaning images folder
			if ( !empty( $deleted_filenames ) ) {
				foreach ( $deleted_filenames as $deleted_filename ) {
					if ( !in_array( $deleted_filename, $data[ 'banner_filename' ] ) ) {
						if ( file_exists( cmac_get_upload_dir() . $deleted_filename ) )
							unlink( cmac_get_upload_dir() . $deleted_filename );
					}

					$wpdb->query( 'DELETE FROM ' . self::$imagesTable . ' WHERE `campaign_id`       ="' . $new_campaign_id . '" AND `filename`="' . $deleted_filename . '"' );
				}
			}
		}

		$selected_banner_id = '0';

		if ( isset( $data[ 'banner_title' ] ) && !empty( $data[ 'banner_title' ] ) ) {
			$data[ 'banner_weight' ] = self::cmac_normalize_weights( $data[ 'banner_weight' ] );
			foreach ( $data[ 'banner_title' ] as $banner_index => $banner_title ) {

				$banner_title = sanitize_text_field( $banner_title );
				$data[ 'banner_filename' ][ $banner_index ]	 = sanitize_text_field( sanitize_file_name( $data[ 'banner_filename' ][ $banner_index ] ) );
				$data[ 'banner_title' ][ $banner_index ]	 = sanitize_text_field( $data[ 'banner_title' ][ $banner_index ] );
				$data[ 'banner_title_tag' ][ $banner_index ] = sanitize_text_field( $data[ 'banner_title_tag' ][ $banner_index ] );
				$data[ 'banner_alt_tag' ][ $banner_index ]	 = sanitize_text_field( $data[ 'banner_alt_tag' ][ $banner_index ] );
				$data[ 'banner_link' ][ $banner_index ]		 = sanitize_text_field( $data[ 'banner_link' ][ $banner_index ] );

				if ( in_array( $data[ 'banner_filename' ][ $banner_index ], $new_filenames ) ) {
					@$image_file_content = file_get_contents( cmac_get_upload_dir() . CMAC_TMP_UPLOAD_PATH . $data[ 'banner_filename' ][ $banner_index ] );

					if ( $image_file_content ) {
						$f = fopen( cmac_get_upload_dir() . $data[ 'banner_filename' ][ $banner_index ], 'w+' );
						fwrite( $f, $image_file_content );
						fclose( $f );
					}

					$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . self::$imagesTable . ' SET `campaign_id`=%d, `title`=%s, `title_tag`=%s, `alt_tag`=%s, `link`=%s, `weight`=%d, `filename`=%s', $new_campaign_id, $banner_title, $data[ 'banner_title_tag' ][ $banner_index ], $data[ 'banner_alt_tag' ][ $banner_index ], $data[ 'banner_link' ][ $banner_index ], $data[ 'banner_weight' ][ $banner_index ], $data[ 'banner_filename' ][ $banner_index ] ) );

					if ( $data[ 'banner_filename' ][ $banner_index ] == $data[ 'selected_banner' ] ) {
						$selected_banner_id = $wpdb->insert_id;
					}
				} else {
					$wpdb->query( $wpdb->prepare( 'UPDATE ' . self::$imagesTable . ' SET `title`=%s, `title_tag`=%s, `alt_tag`=%s, `link`=%s, `weight`=%d WHERE `filename`=%s', $banner_title, $data[ 'banner_title_tag' ][ $banner_index ], $data[ 'banner_alt_tag' ][ $banner_index ], $data[ 'banner_link' ][ $banner_index ], $data[ 'banner_weight' ][ $banner_index ], $data[ 'banner_filename' ][ $banner_index ] ) );
					if ( $data[ 'banner_filename' ][ $banner_index ] == $data[ 'selected_banner' ] ) {
						$selected_banner_id = $wpdb->get_var( 'SELECT `image_id` FROM ' . self::$imagesTable . ' WHERE `filename`="' . $data[ 'banner_filename' ][ $banner_index ] . '"' );
					}
				}
			}
		}
		/*
		 * cleaning tmp folder
		 */
		if ( $handle = opendir( cmac_get_upload_dir() . CMAC_TMP_UPLOAD_PATH ) ) {
			while ( false !== ($entry = readdir( $handle )) ) {
				if ( file_exists( cmac_get_upload_dir() . CMAC_TMP_UPLOAD_PATH . $entry ) && $entry != '.' && $entry != '..' ) {
					unlink( cmac_get_upload_dir() . CMAC_TMP_UPLOAD_PATH . $entry );
				}
			}
		}

		// updating campaigns : setting selected banner
		$wpdb->query( 'UPDATE ' . self::$campaignsTable . ' SET `selected_banner`="' . ($selected_banner_id ? $selected_banner_id : '0') . '" WHERE campaign_id="' . $new_campaign_id . '"' );

		if ( !empty( $wpdb->last_error ) ) {
			return array( 'errors' => array( 'Database error' ), 'fields_data' => $data );
		}

		if ( empty( $errors ) ) {
			//wp_redirect(admin_url('admin.php?page=cmac_campaigns&action=edit&saved=1&campaign_id='.$new_campaign_id));
			//return array();
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . admin_url( 'admin.php?page=cmac_campaigns&action=edit&saved=1&campaign_id=' . $new_campaign_id ) . '">';
			exit;
		}
	}

	/**
	 * Performs settings storage
	 * @return Array
	 * @param Array   $data  Array of fields
	 */
	public static function cmac_handle_settings_post( $data ) {
		$errors = array();

		if ( !empty( $errors ) ) {
			return array( 'errors' => $errors, 'data' => $data );
		}

		update_option( 'acs_active', 1 );
		
		if ( isset( $_POST[ 'acs_slideshow_effect' ] ) )
            update_option( 'acs_slideshow_effect', $_POST[ 'acs_slideshow_effect' ] );

        if ( isset( $_POST[ 'acs_slideshow_interval' ] ) )
            update_option( 'acs_slideshow_interval', $_POST[ 'acs_slideshow_interval' ] );

        if ( isset( $_POST[ 'acs_slideshow_transition_time' ] ) )
            update_option( 'acs_slideshow_transition_time', $_POST[ 'acs_slideshow_transition_time' ] );
		
		return array();
	}

	/**
	 * Gets list of all campaigns
	 * @return Array
	 */
	public static function cmac_get_campaigns() {
		global $wpdb;

		$sql = 'SELECT c.*, count(ci.image_id) as banners_cnt '
		. 'FROM ' . self::$campaignsTable . ' as c '
		. 'LEFT JOIN ' . self::$imagesTable . ' as ci ON ci.campaign_id=c.campaign_id
                    GROUP BY c.campaign_id';

		$campaigns = $wpdb->get_results( $sql );

		foreach ( $campaigns as $campaign_index => $campaign ) {
			$campaigns[ $campaign_index ]->impressions_cnt	 = self::cmac_get_impressions_cnt( $campaign->campaign_id );
			$campaigns[ $campaign_index ]->clicks_cnt		 = self::cmac_get_clicks_cnt( $campaign->campaign_id );
		}
		return $campaigns;
	}

	/**
	 * Gets single campaign
	 * @return Array
	 * @param Int   $campaign_id  Campaign ID
	 */
	public static function cmac_get_campaign( $campaign_id ) {
		global $wpdb;

		$campaign	 = $wpdb->get_row( 'SELECT c.* FROM ' . self::$campaignsTable . ' c WHERE c.campaign_id="' . $campaign_id . '"', ARRAY_A );
		$images		 = $wpdb->get_results( 'SELECT ci.* FROM ' . self::$imagesTable . ' ci WHERE ci.campaign_id="' . $campaign_id . '"', ARRAY_A );

		if ( $images ) {
			foreach ( $images as $image ) {
				$image[ 'banner_clicks_cnt' ]		 = self::cmac_get_banner_clicks_cnt( $image[ 'image_id' ] );
				$image[ 'banner_impressions_cnt' ]	 = self::cmac_get_banner_impressions_cnt( $image[ 'image_id' ] );
				$campaign[ 'banners' ][]			 = $image;

				if ( $image[ 'image_id' ] == $campaign[ 'selected_banner' ] ) {
					$campaign[ 'selected_banner_file' ]		 = $image[ 'filename' ];
					$campaign[ 'selected_banner_title_tag' ] = $image[ 'title_tag' ];
					$campaign[ 'selected_banner_alt_tag' ]	 = $image[ 'alt_tag' ];
					$campaign[ 'selected_banner_link' ]		 = $image[ 'link' ];
					$campaign[ 'selected_banner_id' ]		 = $image[ 'image_id' ];
				}
			}
		}

		return $campaign;
	}

	/**
	 * Gets impressions count for a campaign
	 * @return Int
	 * @param Int   $campaign_id  Campaign ID
	 */
	public static function cmac_get_impressions_cnt( $campaign_id ) {
		global $wpdb;
		//$impressions_cnt = $wpdb->get_var('SELECT count(*) FROM ' . self::$historyTable . ' WHERE `event_type`="impression" AND `campaign_id`="' . $campaign_id . '"');
		$impressions_cnt = 0;
		return $impressions_cnt;
	}

	/**
	 * Gets clicks count for a campaign
	 * @return Int
	 * @param Int   $campaign_id  Campaign ID
	 */
	public static function cmac_get_banner_clicks_cnt( $banner_id ) {
		global $wpdb;
//        $clicks_cnt = $wpdb->get_var('SELECT count(*) FROM ' . self::$historyTable . ' WHERE `event_type`="click" AND `banner_id`="' . $banner_id . '"');
		$clicks_cnt = 0;
		return $clicks_cnt;
	}

	/**
	 * Gets impressions count for a banner
	 * @return Int
	 * @param Int   $banner_id  Banner ID
	 */
	public static function cmac_get_banner_impressions_cnt( $banner_id ) {
		global $wpdb;
//        $impressions_cnt = $wpdb->get_var('SELECT count(*) FROM ' . self::$historyTable . ' WHERE `event_type`="impression" AND `banner_id`="' . $banner_id . '"');
		$impressions_cnt = 0;
		return $impressions_cnt;
	}

	/**
	 * Gets clicks count for a banner
	 * @return Int
	 * @param Int   $banner_id  Banner ID
	 */
	public static function cmac_get_clicks_cnt( $campaign_id ) {
		global $wpdb;
//        $clicks_cnt = $wpdb->get_var('SELECT count(*) FROM ' . self::$historyTable . ' WHERE `event_type`="click" AND `campaign_id`="' . $campaign_id . '"');
		$clicks_cnt = 0;
		return $clicks_cnt;
	}

	/**
	 * Removes campaign and all related data
	 * @param Int   $campaign_id  Campaign ID
	 */
	public static function cmac_remove_campaign( $campaign_id ) {
		global $wpdb;

		$images = $wpdb->get_col( 'SELECT filename FROM ' . self::$imagesTable . ' WHERE `campaign_id`="' . $campaign_id . '"' );

		foreach ( $images as $image ) {
			if ( file_exists( cmac_get_upload_dir() . $image ) ) {
				unlink( cmac_get_upload_dir() . $image );
			}
		}

		$wpdb->query( 'DELETE FROM ' . self::$campaignsTable . ' WHERE `campaign_id`="' . $campaign_id . '"' );
		$wpdb->query( 'DELETE FROM ' . self::$imagesTable . ' WHERE `campaign_id`="' . $campaign_id . '"' );
	}

	/**
	 * Gets category
	 * @return Array
	 * @param Int   $category_id  Category ID
	 */
	public static function cmac_get_category( $category_id ) {
		global $wpdb;
		return $wpdb->get_row( 'SELECT * FROM ' . CATEGORIES_TABLE . ' WHERE `category_id`="' . $category_id . '"' );
	}

	/**
	 * Gets banner
	 * @return Array
	 */
	public static function cmac_get_banner( $params = array() ) {
		CMAdChangerShared::cmac_log( self::$calledClassName . '::cmac_get_banner()' );

		if ( empty( $params[ 'campaign_id' ] ) || !is_numeric( $params[ 'campaign_id' ] ) ) {
			return array( 'error' => CMAC_API_ERROR_2 );
		}

		$campaign = self::cmac_get_campaign( $params[ 'campaign_id' ] );
		if ( empty( $campaign ) ) {
			return array( 'error' => CMAC_API_ERROR_3 );
		}

		if ( (int) $campaign[ 'status' ] == 0 ) {
			return array( 'error' => CMAC_API_ERROR_5 );
		}

		if ( isset( $campaign[ 'use_selected_banner' ] ) && empty( $campaign[ 'selected_banner_id' ] ) ) {
			return array( 'error' => CMAC_API_ERROR_6 );
		}

		if ( CMAC_Data::cmac_get_impressions_cnt( $campaign[ 'campaign_id' ] ) >= $campaign[ 'max_impressions' ] && (int) $campaign[ 'max_impressions' ] > 0 ) {
			return array( 'error' => CMAC_API_ERROR_10 );
		}

		if ( CMAC_Data::cmac_get_clicks_cnt( $campaign[ 'campaign_id' ] ) >= $campaign[ 'max_clicks' ] && (int) $campaign[ 'max_clicks' ] > 0 ) {
			return array( 'error' => CMAC_API_ERROR_11 );
		}

		$selectedBannerInfo = self::cmac_get_selected_banner_info( $campaign );
		
		if ( !empty( $selectedBannerInfo ) && !is_array( $selectedBannerInfo ) ) {
			return $selectedBannerInfo;
		}
		
		if ( empty( $selectedBannerInfo ) || !is_array( $selectedBannerInfo ) ) {
			return array( 'error' => CMAC_API_ERROR_14 );
		}

		$ret_array = $selectedBannerInfo;
		if ( !empty( $selectedBannerInfo[ 'filename' ] ) ) {
			$ret_array[ 'image' ] = cmac_get_upload_url() . $selectedBannerInfo[ 'filename' ];
		} else {
			return array( 'error' => CMAC_API_ERROR_15 );
		}

		if ( !empty( $selectedBannerInfo[ 'link' ] ) ) {
			$ret_array[ 'banner_link' ] = $selectedBannerInfo[ 'link' ];
		} elseif ( !empty( $campaign[ 'link' ] ) ) {
			$ret_array[ 'banner_link' ] = $campaign[ 'link' ];
		}

		CMAdChangerShared::cmac_log( 'Returning response from ' . self::$calledClassName . ':cmac_get_banner()' );

		return $ret_array;
	}

	/**
	 * Gets the information array about the banner (according to display method)
	 * @param type $campaign
	 * @return type
	 */
	public static function cmac_get_selected_banner_info( $campaign ) {
		
		//echo "<pre>"; print_r($campaign); echo "</pre>";
		
		if ( $campaign[ 'banner_display_method' ] == 'selected' ) {
			$bannerInfo = self::cmac_get_banner_info( $campaign, $campaign[ 'selected_banner_id' ] );
			return $bannerInfo;
		}

		if ( $campaign[ 'banner_display_method' ] == 'random' ) {
			$random_banner_index = self::cmac_get_random_banner_index( $campaign );
			$bannerInfo			 = self::cmac_get_banner_info( $campaign, $random_banner_index );
			return $bannerInfo;
		}
		
		if ( $campaign[ 'banner_display_method' ] == 'all' ) {
			
			$tcycle_fx      = get_option( 'acs_slideshow_effect', 'fade' ) == "fade" ? "fade" : "scroll";
			$tcycle_speed   = get_option( 'acs_slideshow_transition_time', '400' );
			$tcycle_timeout = get_option( 'acs_slideshow_interval', '5000' );

			$bannerInfo = self::cmac_get_banners_info( $campaign );
			$meta = unserialize($campaign[ 'meta' ]);
			if ( !empty( $meta[ 'rotated_random' ] ) ) {
				$bannerInfo = self::shuffle_assoc($bannerInfo);
			}
			//echo "<pre>"; print_r($campaign); echo "</pre>";
			//echo "<pre>"; print_r($bannerInfo); echo "</pre>";
			$out = '';
			if(count($bannerInfo) > 0) {
				$mt_rand = mt_rand(0,999999);
				$out .= '<div id="cmac_ads_r_'.$mt_rand.'" data-fx="' . $tcycle_fx . '" data-speed="' . $tcycle_speed . '" data-timeout="' . $tcycle_timeout . '">';
				foreach ($bannerInfo as $banner) {
					if($campaign['link'] != '') {
						$href = $campaign['link'];
					} else if($banner['link'] != '') {
						$href = $banner['link'];
					} else {
						$href = '';
					}
					$title = '';
					if($banner['title_tag'] != '') {
						$title = $banner['title_tag'];
					}
					$alt = '';
					if($banner['alt_tag'] != '') {
						$alt = $banner['alt_tag'];
					}
					$out .= '<a href="'.$href.'"><img src="'.cmac_get_upload_url().$banner['filename'].'" title="'.$title.'" alt="'.$alt.'" /></a>';
				}
				$out .= '</div>';
				$out .= '<script>jQuery("#cmac_ads_r_'.$mt_rand.'").tcycle();</script>';
			}
			return $out;
			//return $bannerInfo;
		}

		$bannerInfo = apply_filters( 'cmac_additional_display_method', $campaign );
		return $bannerInfo;
	}
	
	private static function shuffle_assoc( $list ) {
        if ( !is_array( $list ) ) {
            return $list;
        }

        $keys   = array_keys( $list );
        shuffle( $keys );
        $random = array();
        foreach ( $keys as $key ) {
            $random[ $key ] = $list[ $key ];
        }
        return $random;
    }
	
	/**
	 * Gets the information about specific banner
	 * @param type $campaign
	 * @param type $banner_id
	 * @return type
	 */
	public static function cmac_get_banner_info( $campaign, $banner_id ) {
		if ( isset( $campaign[ 'banners' ] ) && is_array( $campaign[ 'banners' ] ) ) {
			foreach ( $campaign[ 'banners' ] as $banner ) {
				if ( $banner[ 'image_id' ] == $banner_id || !$banner_id ) {
					return $banner;
				}
			}
		}
		return array();
	}
	
	public static function cmac_get_banners_info( $campaign ) {
		if ( isset( $campaign[ 'banners' ] ) && is_array( $campaign[ 'banners' ] ) ) {
			return $campaign[ 'banners' ];
		}
		return array();
	}

	/**
	 * Normalizing weights, till sum = 100
	 * @return Array
	 * @param Array   $weights  Array of positive integers
	 */
	public static function cmac_normalize_weights( $weights ) {
		$sum = array_sum( $weights );
		if ( $sum == 0 ) {
			return $weights;
		}

		foreach ( $weights as $index => $weight ) {
			$weights[ $index ] = round( $weight / $sum * 100 );
		}

		$new_sum	 = array_sum( $weights );
		$rand_key	 = array_rand( $weights, 1 );

		if ( $new_sum != 100 ) {
			$weights[ $rand_key ] += 100 - $new_sum;
		}
		return $weights;
	}

	/**
	 * Random weighted key finder
	 * @return Int
	 * @param Array   $weights  Array of positive integers
	 */
	public static function cmac_get_random_banner_index( $campaign ) {
		$weights = array();
		if ( !empty( $campaign ) && !empty( $campaign[ 'banners' ] ) ) {
			foreach ( $campaign[ 'banners' ] as $banner ) {
				$weights[ $banner[ 'image_id' ] ] = $banner[ 'weight' ];
			}
		}

		if ( empty( $weights ) ) {
			return null;
		}

		asort( $weights );

		if ( array_sum( $weights ) == 0 ) {
			return array_rand( $weights, 1 );
		}

		$rand_num = rand( 1, array_sum( $weights ) );

		$diapasons			 = array();
		$weights_sum		 = 0;
		$prev_weights_sum	 = 0;
		$res				 = array();
		foreach ( $weights as $cur_key => $weight ) {
			$weights_sum += $weight;
			$diapasons[ $cur_key ]	 = array( $prev_weights_sum + 1, $weights_sum );
			$prev_weights_sum		 = $weights_sum;
			if ( $rand_num <= $diapasons[ $cur_key ][ 1 ] && $rand_num >= $diapasons[ $cur_key ][ 0 ] ) {
				$res[] = $cur_key;
			}
		}

		$res_rand_key = array_rand( $res, 1 );
		return $res[ $res_rand_key ];
	}

	/**
	 * Save the event information
	 * @global type $wpdb
	 * @return boolean
	 */
	public static function cmac_event_save( $args ) {
		global $wpdb;

		$event_name = isset( $args[ 'event' ] ) ? $args[ 'event' ] : 'click';

		CMAdChangerShared::cmac_log( 'Triggering ' . $event_name . ' event' );

		if ( !isset( $args[ 'campaign_id' ] ) || !is_numeric( $args[ 'campaign_id' ] ) ) {
			return array( 'error' => 'Missing "campaign_id"' );
		}

		if ( !isset( $args[ 'banner_id' ] ) || !is_numeric( $args[ 'banner_id' ] ) ) {
			return array( 'error' => 'Missing "banner_id"' );
		}

		if ( !isset( $args[ 'http_referer' ] ) ) {
			return array( 'error' => 'Missing "http_referer' );
		}

		$country_name = '';

		switch ( $event_name ) {
			default:
			case 'click':
				//$wpdb->query($wpdb->prepare('INSERT INTO ' . self::$historyTable . ' SET event_type="click", campaign_id=%d, banner_id=%d, referer_url=%s, webpage_url=%s, remote_ip=%s, remote_country=%s, campaign_type=%s', $args['campaign_id'], $args['banner_id'], $args['http_referer'], $args['webpage_url'], $args['remote_ip'], $country_name, $args['campaign_type']));
				return true;
			case 'impression':
				//$wpdb->query($wpdb->prepare('INSERT INTO ' . self::$historyTable . ' SET event_type="impression", campaign_id=%d, banner_id=%d, referer_url=%s, webpage_url=%s, remote_ip=%s, remote_country=%s, campaign_type=%s', $args['campaign_id'], $args['banner_id'], $args['http_referer'], $args['webpage_url'], $args['remote_ip'], $country_name, $args['campaign_type']));
				return true;
		}
		return false;
	}

}
