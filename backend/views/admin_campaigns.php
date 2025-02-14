<?php
/**
 * CM Ad Changer
 *
 * @author CreativeMinds (https://www.cminds.com/wordpress-plugins-library/adchanger/)
 * @copyright Copyright (c) 2013, CreativeMinds
 */
?>
<div class="cm-ad-changer">
	<script type="text/javascript">
		var base_url = '<?php echo get_bloginfo( 'wpurl' ) ?>';
		var plugin_url = '<?php echo CMAC_PLUGIN_URL ?>';
		var upload_tmp_path = '<?php echo cmac_get_upload_url() . CMAC_TMP_UPLOAD_PATH; ?>';
		var banners_limit = <?php echo CMAC_BANNERS_PER_CAMPAIGN_LIMIT; ?>;
		var next_banner_index = 0;
		var label_descriptions = new Object();
		label_descriptions.banner_title = '<?php echo CMAdChangerShared::$labels[ 'banner_title' ]; ?>';
		label_descriptions.banner_title_tag = '<?php echo CMAdChangerShared::$labels[ 'banner_title_tag' ]; ?>';
		label_descriptions.banner_alt_tag = '<?php echo CMAdChangerShared::$labels[ 'banner_alt_tag' ]; ?>';
		label_descriptions.banner_link = '<?php echo CMAdChangerShared::$labels[ 'banner_link' ]; ?>';
		label_descriptions.banner_weight = '<?php echo CMAdChangerShared::$labels[ 'banner_weight' ]; ?>';
	</script>
	<input type="submit" value="Create new Campaign" class="right clear button" id="new_campaign_button" />
	<div class="clear"></div>
	<?php if ( !empty( $campaigns ) ) : ?>
		<div class="campaigns_list_table_head">
			<div style="text-align: left !important;">Campaign Name</div>
			<div>Campaign ID</div>
			<div>Images</div>
			<div>Clicks</div>
			<div>Impressions</div>
			<div>Status</div>
			<div>Actions</div>
		</div>
		<div class="campaigns_list_scroll clear">
			<table id="campaigns_list" class="ads_list" cellspacing=0 cellpadding=0 border=0>
				<tbody>
					<?php foreach ( $campaigns as $campaign ) : ?>
						<tr campaign_id="<?php echo $campaign->campaign_id ?>"<?php echo isset( $fields_data[ 'campaign_id' ] ) && $fields_data[ 'campaign_id' ] == $campaign->campaign_id ? ' class="selected_campaign"' : '' ?>>
							<td>
								<a href="<?php echo get_bloginfo( 'wpurl' ) ?>/wp-admin/admin.php?page=<?php echo $pageName ?>&action=edit&campaign_id=<?php echo $campaign->campaign_id ?>" class="field_tip" title="<?php echo $campaign->comment ?>"><?php echo $campaign->title; ?></a>
							</td>
							<td><?php echo $campaign->campaign_id; ?></td>
							<td><?php echo $campaign->banners_cnt; ?></td>
							<td><?php echo!is_null( $campaign->clicks_cnt ) ? $campaign->clicks_cnt : '-'; ?></td>
							<td><?php echo!is_null( $campaign->impressions_cnt ) ? $campaign->impressions_cnt : '-'; ?></td>
							<td><?php echo (($campaign->status == '1') ? 'Active' : 'Inactive') ?></td>
							<td class="actions">
								<a href="<?php echo get_bloginfo( 'wpurl' ) ?>/wp-admin/admin.php?page=<?php echo $pageName ?>&action=edit&campaign_id=<?php echo $campaign->campaign_id ?>"><img src="<?php echo self::$cssPath . 'images/edit.png' ?>" /></a>
								<a href="<?php echo get_bloginfo( 'wpurl' ) ?>/wp-admin/admin.php?page=<?php echo $pageName ?>&action=delete&campaign_id=<?php echo $campaign->campaign_id ?>" class="delete_campaign_link"><img src="<?php echo self::$cssPath . 'images/trash.png' ?>" /></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<div class="ac-edit-form">
		<form id="campaign_form" class="clear ac-form" <?php echo (isset( $fields_data[ 'title' ] ) || (isset( $_GET[ 'acs_admin_action' ] ) && $_GET[ 'acs_admin_action' ] == 'new_campaign' && empty( $_POST )) ? 'style="display:block !important"' : '') ?> method="post">
			<?php
			wp_nonce_field( 'cmac-update-campaign-data' );
			?>
			<div class="right" style="margin-bottom: 5px;">
				<input type="submit" value="<?php echo (isset( $fields_data[ 'campaign_id' ] ) ? 'Save' : 'Add') ?>" name="submit" id="submit_button" class="right button">
			</div>
			<div id="ac-fields" class="clear">
				<ul>
					<li><a href="#campaign_fields">Campaign Settings</a></li>
					<li><a href="#banners_fields">Campaign Banners</a></li>
					<li><a href="#activity_fields">Campaign Activity Settings</a></li>
				</ul>
				<table cellspacing=0 cellpadding=0 border=0 class="clear" id="campaign_fields">
					<tr>
						<td>
							<label class="ac-form-label" for="title" >Campaign Name <span class="required" style="color:red">*</span> </label><div class="field_help" title="<?php echo CMAdChangerShared::$labels[ 'title' ] ?>"></div><br/>
							<?php
							if ( isset( $fields_data ) && isset( $fields_data[ 'campaign_id' ] ) ) {
								echo '<input type="hidden" name="campaign_id" value="' . $fields_data[ 'campaign_id' ] . '" />';
								echo '<br><strong>Campaign ID <div class="field_help" title="' . CMAdChangerShared::$labels[ 'campaign_id' ] . '"></div> :' . $fields_data[ 'campaign_id' ] . '</strong>';
							}
							?>
						</td>
						<td>
							<input type="text" aria-required="true" value="<?php echo (isset( $fields_data[ 'title' ] ) ? esc_attr( $fields_data[ 'title' ] ) : '') ?>" name="title" id="title" /></br>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Campaign Group:</label>
							<div class="field_help" title="Select the group of the Campaign."></div>
						</td>
						<td>
							<select disabled>
								<option>-None-<option>
							</select>
							<label class="ac-form-label" for="comment" class="clear" > Campaign Weight:</label>
							<div class="field_help" title="Choose the priority of the Campaign within the group. Campaigns with high weight will have the precedense."></div>
							<input type="text" disabled value="0" />
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Campaign Notes</label>
							<div class="field_help" title="<?php echo CMAdChangerShared::$labels[ 'comment' ] ?>"></div>
						</td>
						<td>
							<textarea value="<?php echo (isset( $fields_data[ 'comment' ] ) ? esc_html( stripslashes( $fields_data[ 'comment' ] ) ) : '') ?>" name="comment" id="comment"><?php echo (isset( $fields_data[ 'comment' ] ) ? esc_html( stripslashes( $fields_data[ 'comment' ] ) ) : '') ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label class="ac-form-label" for="link" >Target URL</label>
							<div class="field_help" title="<?php echo CMAdChangerShared::$labels[ 'link' ] ?>"></div>
						</td>
						<td>
							<input type="text" aria-required="false" value="<?php echo (isset( $fields_data[ 'link' ] ) ? esc_url( $fields_data[ 'link' ] ) : '') ?>" name="link" id="link" />
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Open target URL in new window</label>
							<div class="field_help" title="Should clicking on banner open new window"></div>
						</td>
						<td>
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="ac-form-label" for="status">Campaign Status</label>
							<div class="field_help" title="<?php echo CMAdChangerShared::$labels[ 'status' ] ?>"></div>
						</td>
						<td>
							<input type="checkbox" aria-required="true" name="status" id="status" <?php echo (((isset( $fields_data[ 'status' ] ) && $fields_data[ 'status' ] == 1) || !isset( $fields_data[ 'campaign_id' ] )) ? 'checked=checked' : '') ?> />&nbsp;Active
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Campaign Manager Email:</label>
							<div class="field_help" title="Email of campaign manager. Notifications will be send to this email"></div>
						</td>
						<td>
							<input type="text" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Send Notifications</label>
							<div class="field_help" title="Send Notifications when campaign stops to the email set for the campaign manager"></div>
						</td>
						<td>
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Max Impressions</label>
							<div class="field_help" title="Leave it 0 to remove limit or set to max number allowed"></div>
						</td>
						<td>
							<input type="number" value="0" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Max Clicks</label>
							<div class="field_help" title="Leave it 0 to remove limit or set to max number allowed"></div>
						</td>
						<td>
							<input type="number" value="0" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Approved Domains</label>
							<div class="field_help" title="Approved domains - List of URLs of approved clients. If not specified all clients will be served."></div>
						</td>
						<td>
							<input type="text" disabled /><br>
							<p style="margin-bottom:0;">There are no domain limitations set</p>
							<img src="<?php echo self::$cssPath; ?>../img/plus.png" style="opacity:0.2;">
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Advertisers</label>
							<div class="field_help" title="Advertiser Name"></div>
						</td>
						<td>
							<select disabled style="min-width:400px;">
								<option>Select Advertiser<option>
							</select>
							<br>
							<input type="text" disabled style="min-width:554px;" /><span style="text-decoration:underline;">Add</span>
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Custom JS</label>
							<div class="field_help" title="Custom JS will be injected into body before banner is shown and only on post or pages where campaign is active and if client explicitly allows to inject JS. Example: alert('test');"></div>
						</td>
						<td>
							<textarea disabled rows="5" cols="60"></textarea>
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					
					<?php if ( isset( $fields_data[ 'campaign_id' ] ) ) { ?>
						<tr>
							<td colspan="2">
								<div class="clear"></div>
								<div class="inlineMessageInfo"><strong>To show this campaign please put the following shortcode [cm_ad_changer campaign_id="<?php echo $fields_data[ 'campaign_id' ]; ?>"] on any page or post or use the Ad Changer sidebar widget found in your WordPress widgets area with the campaign number "<?php echo $fields_data[ 'campaign_id' ]; ?>".</strong></div>
								<div class="clear"></div>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<td colspan="2">
							<div class="clear"></div>
							<div class="inlineMessageError"><strong>Please note that free version of the AdChanger plugin does not track impressions and clicks. You can find this feature in AdChanger PRO.</strong></div>
							<div class="clear"></div>
						</td>
					</tr>
				</table>
				<table cellspacing=0 cellpadding=0 border=0 id="banners_fields">
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Campaign Type:</label>
							<div class="field_help" title="Pick the type of the advertisements in the current campaign from the list currently supported types."></div>
						</td>
						<td>
							<select disabled>
								<option>Image Banners<option>
							</select>
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Cloud Storage URL:</label>
							<div class="field_help" title="Cloud Storage URL is where the campaign banners are stored. Make sure to specify the correct url of your cloud storage bucket. All Campaign images will be served from this location if Use Cloud Storage is set. All local campaign images are stored under WordPress upload directory in a sub-directory called ac_uploads. Make sure to upload them to cloud storage"></div>
						</td>
						<td>
							<input type="text" disabled />
							<input type="checkbox" disabled />
							Use Cloud Storage
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="ac-form-label" for="use_random_banner">Display Method</label>
							<div class="field_help" title="<?php echo CMAdChangerShared::$labels[ 'use_selected_banner' ] ?>"></div>
						</td>
						<td>
							<input type="radio" aria-required="true" name="banner_display_method" id="use_random_banner" <?php echo (isset( $fields_data[ 'banner_display_method' ] ) && $fields_data[ 'banner_display_method' ] == 'random' ? 'checked=checked' : (!isset( $fields_data[ 'banner_display_method' ] ) ? 'checked=checked' : '')) ?> value="random" />&nbsp;<label for="use_random_banner">Random Banner</label><br/>
							<input type="radio" aria-required="true" name="banner_display_method" id="use_selected_banner" <?php echo (isset( $fields_data[ 'banner_display_method' ] ) && $fields_data[ 'banner_display_method' ] == 'selected' ? 'checked=checked' : '') ?> value="selected" />&nbsp;<label for="use_selected_banner">Selected Banner</label><br/>
							<input type="radio" aria-required="true" name="banner_display_method" id="use_all_banners" <?php echo (isset( $fields_data[ 'banner_display_method' ] ) && $fields_data[ 'banner_display_method' ] == 'all' ? 'checked=checked' : '') ?> value="all" />&nbsp;<label for="use_all_banners">Rotated Banner</label>
							<input type="checkbox" name="rotated_random" id="rotated_random" class='rotated_random' <?php
							if ( !empty( $fields_data[ 'meta' ] ) ) {
								$meta = unserialize($fields_data[ 'meta' ]);
								if (isset($meta[ 'rotated_random' ]) && $meta[ 'rotated_random' ] == 'on') {
									echo 'checked';
								}
							}
							?> />&nbsp;Randomize Rotated Banners
						</td>
					</tr>
					<tr>
						<td>
							<label class="ac-form-label" for="campaign_images">Campaign Images</label>
							<div class="field_help" title="<?php echo CMAdChangerShared::$labels[ 'campaign_images' ] ?>"></div>
						</td>
						<td>
							<div id="container">
								<input type="button" value="Select files" id="pickfiles" class="pickfiles clear">
								<div id="filelist" class="clear">
									<?php
									if ( isset( $fields_data[ 'banners' ] ) ) {
										foreach ( $fields_data[ 'banners' ] as $banner_index => $banner ) {

											$clicks_rate	 = 0;
											$banner_filename = $banner[ 'filename' ];
											if ( isset( $banner[ 'banner_clicks_cnt' ] ) && (int) $banner[ 'banner_clicks_cnt' ] > 0 ) {
												$clicks_rate = round( ((int) $banner[ 'banner_clicks_cnt' ] / (int) $banner[ 'banner_impressions_cnt' ]) * 100 );
											}

											//if(@file_get_contents(get_bloginfo('wpurl') . '/wp-content/uploads/'.CMAC_UPLOAD_PATH.''.$banner_filename)){
											if ( file_exists( cmac_get_upload_dir() . $banner_filename ) ) {
												$filename	 = cmac_get_upload_url() . $banner_filename;
												$filename1	 = cmac_get_upload_dir() . $banner_filename;
											} elseif ( file_exists( cmac_get_upload_url() . CMAC_TMP_UPLOAD_PATH . '' . $banner_filename ) ) {
												$filename	 = cmac_get_upload_url() . CMAC_TMP_UPLOAD_PATH . '' . $banner_filename;
												$filename1	 = cmac_get_upload_dir() . CMAC_TMP_UPLOAD_PATH . $banner_filename;
											} else {
												continue;
											}

											// image info
											$image_size		 = getimagesize( $filename1 );
											$filesize		 = round( filesize( $filename1 ) / 1024 );
											$image_width	 = $image_size[ 0 ];
											$image_height	 = $image_size[ 1 ];
											$mime_splitted	 = explode( '/', $image_size[ 'mime' ] );
											$ext			 = $mime_splitted[ 1 ];
											$image_info		 = '<b>Dimensions:</b> ' . $image_width . 'x' . $image_height . "<br/>";
											$image_info .= '<b>Size:</b> ' . $filesize . ' kb' . "<br/>";
											$image_info .= '<b>Type:</b> ' . $ext;
											echo '<div class="plupload_image">
														<img src="' . $filename . '" class="banner_image" title="' . $image_info . '" />
														<input type="hidden" name="banner_filename[]" value="' . $banner_filename . '" />
														<table class="banner_info" border=0>
																<tr><td><label for="banner_title' . $banner_index . '">Name</label><div class="field_help" title="' . CMAdChangerShared::$labels[ 'banner_title' ] . '"></div></td><td><input type="text" name="banner_title[]" id="banner_title' . $banner_index . '" maxlength="150" value="' . (isset( $banner[ 'title' ] ) ? $banner[ 'title' ] : '') . '" /></td></tr>
																<tr><td><label for="banner_title_tag' . $banner_index . '">Banner Title</label><div class="field_help" title="' . CMAdChangerShared::$labels[ 'banner_title_tag' ] . '"></div></td><td><input type="text" name="banner_title_tag[]" id="banner_title_tag' . $banner_index . '" maxlength="50" value="' . (isset( $banner[ 'title_tag' ] ) ? $banner[ 'title_tag' ] : '') . '" /></td></tr>
																<tr><td><label for="banner_alt_tag' . $banner_index . '">Banner Alt</label><div class="field_help" title="' . CMAdChangerShared::$labels[ 'banner_alt_tag' ] . '"></div></td><td><input type="text" name="banner_alt_tag[]" id="banner_alt_tag' . $banner_index . '" maxlength="150" value="' . (isset( $banner[ 'alt_tag' ] ) ? $banner[ 'alt_tag' ] : '') . '" /></td></tr>
																<tr><td><label for="banner_link' . $banner_index . '">Target URL</label><div class="field_help" title="' . CMAdChangerShared::$labels[ 'banner_link' ] . '"></div></td><td><input type="text" name="banner_link[]" id="banner_link' . $banner_index . '" maxlength="150" value="' . (isset( $banner[ 'link' ] ) ? $banner[ 'link' ] : '') . '" /></td></tr>
																<tr><td><label for="banner_weight' . $banner_index . '">Weight</label><div class="field_help" title="' . CMAdChangerShared::$labels[ 'banner_weight' ] . '"></div></td><td><input type="text" name="banner_weight[]" id="banner_weight' . $banner_index . '" maxlength="4" value="' . (isset( $banner[ 'weight' ] ) && is_numeric( $banner[ 'weight' ] ) ? $banner[ 'weight' ] : '0') . '" class="num_field" /></td></tr>
														</table>
														<div class="ac_explanation clear">Click on image to select the banner</div>
														<div class="clicks_and_impressions">
																<div class="impressions">' . ($banner[ 'banner_impressions_cnt' ] ? $banner[ 'banner_impressions_cnt' ] : 0) . '</div>
																<div class="clicks">' . ($banner[ 'banner_clicks_cnt' ] ? $banner[ 'banner_clicks_cnt' ] : 0) . '</div>
																<div class="percent">' . $clicks_rate . '</div>
														</div>
														<img src="' . self::$cssPath . 'images/close.png' . '" class="delete_button" />
												</div>';
										}

										if ( isset( $fields_data[ 'selected_banner_file' ] ) && !empty( $fields_data[ 'selected_banner_file' ] ) ) {
											echo '<script type="text/javascript">
												jQuery(document).ready(function(){
													CM_AdsChanger.check_banner(jQuery(\'#filelist input[type="hidden"][value="' . $fields_data[ 'selected_banner_file' ] . '"]\').parent());
												})
											  </script>';
										}
									}
									?>
								</div>
							</div>
							<div class="selected_banner_details">
								<label class="ac-form-label">Selected Image URL:</label>
								<div id="selected_banner_url"></div>
								<label class="ac-form-label" for="selected_image">Selected Image Name:</label>
								<div id="selected_banner"></div>
								<input type="hidden" name="selected_banner" value="" />
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="clear"></div>
							<div class="inlineMessageError"><strong>Please note that free version of the AdChanger plugin does not track impressions and clicks.<br>You can find this feature in AdChanger PRO.</strong></div>
							<div class="clear"></div>
						</td>
					</tr>
				</table>
				<table cellspacing=0 cellpadding=0 border=0 id="activity_fields">
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Activity Dates</label>
							<div class="field_help" title="Activity Dates - List of dates when campaign is active. If not set than campaign is active on all dates."></div>
						</td>
						<td>
							<p style="margin-bottom:0;">There are no domain limitations set</p>
							<img src="<?php echo self::$cssPath; ?>../img/plus.png" style="opacity:0.2;">
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
					<tr class="onlyinpro">
						<td>
							<label class="ac-form-label" for="comment" class="clear" >Activity Days</label>
							<div class="field_help" title="Activity Days - List of days in the week when campaign is active. If not set than campaign is active on all days."></div>
						</td>
						<td>
							<input type="checkbox" disabled /> <label>Sunday</label><br>
							<input type="checkbox" disabled /> <label>Monday</label><br>
							<input type="checkbox" disabled /> <label>Tuesday</label><br>
							<input type="checkbox" disabled /> <label>Wednesday</label><br>
							<input type="checkbox" disabled /> <label>Thursday</label><br>
							<input type="checkbox" disabled /> <label>Friday</label><br>
							<input type="checkbox" disabled /> <label>Saturday</label><br>
							<p><span class="cm_field_help_pro">(Only in Pro)</span></p>
						</td>
					</tr>
				</table>
			</div>
			<div class="right">
				<input type="submit" value="<?php echo (isset( $fields_data[ 'campaign_id' ] ) ? 'Save' : 'Add') ?>" name="submit" id="submit_button" class="button">
			</div>
		</form>
	</div>
</div>
<style>
.cm_field_help_pro { color:green !important; opacity:0.8; }
.show_hide_pro_options { display:none; }
</style>