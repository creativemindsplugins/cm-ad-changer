<?php
/**
 * CM Ad Changer
 *
 * @author CreativeMinds (https://www.cminds.com/wordpress-plugins-library/adchanger/)
 * @copyright Copyright (c) 2013, CreativeMinds
 */
$acs_slideshow_effect = get_option( 'acs_slideshow_effect', 'fade' );
$acs_slideshow_interval = get_option( 'acs_slideshow_interval', '5000' );
$acs_slideshow_transition_time = get_option( 'acs_slideshow_transition_time', '400' );
?>
<script type="text/javascript">
	plugin_url = '<?php echo CMAC_PLUGIN_URL ?>';
</script>
<div class="clear"></div>
<div class="ac-edit-form clear">
	<form id="acs_settings_form" method="post">
		<?php
		wp_nonce_field( 'cmac-update-settings-data' );
		?>
        <input type="hidden" name="action" value="acs_settings" />
        <div id="settings_fields" class="clear">
            
			<ul>
				<li><a href="#tab-installation">Installation Guide</a></li>
				<li><a href="#tab-general">General Settings</a></li>
				<li><a href="#tab-geolocation">Geolocation</a></li>
				<li><a href="#tab-rotated">Rotated Banners</a></li>
				<li><a href="#tab-custom">Custom CSS</a></li>
				<li><a href="#tab-responsive">Responsive Settings</a></li>
				<li><a href="#tab-trash">Trash</a></li>
				<li><a href="#tab-shortcode">Shortcode</a></li>
				<li><a href="#tab-upgrade">Upgrade</a></li>
			</ul>
			
            <div id="tab-installation">
                <div class="block">
					<table>
						<tbody>
							<tr>
								<td><?php echo do_shortcode( '[cminds_free_guide id="cmac"]' ); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<div id="tab-general">
				<div class="block">
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Server Active</label></div>
						<div class="setting_content">
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Server status, if set than server will accept connections from CM Ad Changer - Pro Clients</p>
						</div>
					</div>
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Disable history functionality</label></div>
						<div class="setting_content">
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Select this option to turn off AdChanger functionality of tracking banner clicks and impressions. If this option is selected functionalities: statistics, banner max impressions and banner max clicks will not work. Selecting this option may speed up the site since AdChanger will not be using the history table.</p>
						</div>
					</div>
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Notification Email Template</label></div>
						<div class="setting_content">
							<textarea disabled style="width:100%; height:150px;"></textarea>
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Email notification is sent when campaign stops working. Email notification can include the following fields: %campaign_name%, %campaign_id%, %reason%.</p>
						</div>
					</div>
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Inject JS libraries on ALL pages</label></div>
						<div class="setting_content">
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Injecting scripts into all pages is needed in rare cases when campaign function or shortcode is called from an external plugin. This means that every page will enqueue the CSS and JS code of CM Ad Changer.</p>
						</div>
					</div>
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Inject JS files in footer</label></div>
						<div class="setting_content">
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Inject scripts in footer. Warning: theme must use wp_footer() function for this to work!</p>
						</div>
					</div>
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Auto-deactivate old campaigns</label></div>
						<div class="setting_content">
							<input type="checkbox" disabled />
							<p><span class="cm_field_help_pro">(Only in Pro)</span> If you check this option the campaings which have had the activity dates set, will be automatically deactivated after the last period has passed.</p>
						</div>
					</div>
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Use Cloud Storage</label></div>
						<div class="setting_content">
							<input type="checkbox" disabled /> For cloud storage, You should need to install and configure WP Offload Media plugin.
							<p><span class="cm_field_help_pro">(Only in Pro)</span> If you check this option then uploaded images will store on cloud server.</p>
						</div>
					</div>
				</div>
			</div>
			
			<div id="tab-geolocation">
				<div class="block">
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Geolocation API Key</label></div>
						<div class="setting_content">
							<input type="text" disabled style="width:300px; height:30.2px;" />
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Geolocation API Key. To receive API register at http://ipinfodb.com/register.php</p>
						</div>
					</div>
				</div>
			</div>
			
			<div id="tab-rotated">
				<div class="block">
					<div class="setting_row">
						<div class="setting_label"><label>Rotated Banner switch effect</label></div>
						<div class="setting_content">
							<select style="width:100px; height:30.2px;" name="acs_slideshow_effect">
								<option value="fade" <?php echo !isset( $acs_slideshow_effect ) || $acs_slideshow_effect == 'fade' ? 'selected=selected' : ''; ?>>Fade</option>
                                <option value="slide" <?php echo isset( $acs_slideshow_effect ) && $acs_slideshow_effect == 'slide' ? 'selected=selected' : ''; ?>>Slide</option>
							</select>
							<p>Rotating Banner effect</p>
						</div>
					</div>
					<div class="setting_row">
						<div class="setting_label"><label>Rotated Banner switch interval</label></div>
						<div class="setting_content">
							<input type="text" value="<?php echo $acs_slideshow_interval; ?>" style="width:100px; height:30.2px;" name="acs_slideshow_interval" />
							<p>Amount of time before one banner replaces the other (milliseconds)</p>
						</div>
					</div>
					<div class="setting_row">
						<div class="setting_label"><label>Rotated Banner Transition time</label></div>
						<div class="setting_content">
							<input type="text" value="<?php echo $acs_slideshow_transition_time; ?>" style="width:100px; height:30.2px;" name="acs_slideshow_transition_time" />
							<p>The amount of time each transition takes (milliseconds)</p>
						</div>
					</div>
					<div class="setting_row" style="text-align:right;">
						<input type="submit" value="Store Settings" id="submit_button" class="button" />
					</div>
				</div>
			</div>
			
			<div id="tab-custom">
				<div class="block">
					<div class="setting_row onlyinpro">
						<div class="setting_label"><label>Custom CSS</label></div>
						<div class="setting_content">
							<textarea disabled style="width:100%; height:150px;"></textarea>
							<p><span class="cm_field_help_pro">(Only in Pro)</span> Custom CSS will be injected into body before banner is shown and only on post or pages where campaign is active. Example: #featured.has-badge {margin-bottom: 85px;}</p>
						</div>
					</div>
				</div>
			</div>
			
			<div id="tab-responsive">
				<div class="block">
					<p class="onlyinpro">Using banner variations means it will take more time to show ad on client side since it is called only after calculating possible size</p>
					<div class="setting_row onlyinpro">
						<div class="onlyinpro" style="margin:1em 0;">
							Available in Pro version and above.
							<a href="http://localhost/cmindsfree/wp-admin/admin.php?page=cmac_pro">UPGRADE NOW&nbsp;➤</a>
						</div>
					</div>
				</div>
			</div>
			
			<div id="tab-trash">
				<div class="block">
					<p class="onlyinpro">Here you able to remove statistics using following options</p>
					<div class="setting_row onlyinpro">
						<div class="onlyinpro" style="margin:1em 0;">
							Available in Pro version and above.
							<a href="http://localhost/cmindsfree/wp-admin/admin.php?page=cmac_pro">UPGRADE NOW&nbsp;➤</a>
						</div>
					</div>
				</div>
			</div>
			
			<div id="tab-shortcode">
				<div class="block">
					<p>To insert the ads into a page or post use following shortcode: <strong>[cm_ad_changer]</strong><br><br>Here is the list of parameters: </p>
					<ul style="list-style-type: disc; margin-left: 20px;">
						<li><strong>campaign_id</strong> - ID of a campaign (required)</li>
						<li><strong>linked_banner</strong> - Banner is a linked image or just image. Can be 1 or 0 (default: 1)</li>
						<li><strong>debug</strong> - Show the debug info. Can be 1 or 0 (default: 0)</li>
						<li><strong>wrapper</strong> - Wrapper On or Off. Wraps banner with  a div tag. Can be 1 or 0 (default: 0)</li>
						<li><strong>class</strong> - Banner (div) class name</li>
					</ul>
				</div>
			</div>
			
			<div id="tab-upgrade">
                <div class="block">
					<table>
						<tbody>
							<tr>
								<td><?php echo do_shortcode( '[cminds_upgrade_box id="cmac"]' ); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
	</form>
</div>

<style>
.block .setting_row { clear:both; width:100%; }
.block .setting_row .setting_label { width:25%; display:inline-block; vertical-align:top; }
.block .setting_row .setting_content { width:70%; display:inline-block; }
.block .setting_row .setting_content p { margin:5px 0px 0px 0px; font-size:13px; }
.block .setting_row .setting_content p span { color:green !important; opacity:0.8; }
.onlyinpro { color: #aaa !important; }
.onlyinpro * { color: #aaa !important; }
.onlyinpro td { color: #aaa !important; }
.onlyinpro td label { color: #aaa !important; }
.onlyinpro.hide { display: none !important; }
@media only screen and (max-width: 768px) {
	.block .setting_row .setting_label { width:100%; }
	.block .setting_row .setting_content { width:100%; }
}
</style>