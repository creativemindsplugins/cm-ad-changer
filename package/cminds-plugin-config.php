<?php
ob_start();
include plugin_dir_path(__FILE__) . 'views/plugin_compare_table.php';
$plugin_compare_table = ob_get_contents();
ob_end_clean();
$cminds_plugin_config = array(
	'plugin-is-pro'						=> FALSE,
	'plugin-is-addon'					=> FALSE,
	'plugin-version'					=> '2.0.3',
	'plugin-abbrev'						=> 'cmac',
	'plugin-file'						=> CMAC_PLUGIN_FILE,
    'plugin-campign'					=> '?utm_source=adchangerfree&utm_campaign=freeupgrade',
	'plugin-affiliate'					=> '',
	'plugin-redirect-after-install'		=> admin_url( 'admin.php?page=cmac_settings' ),
	'plugin-show-guide'                 => TRUE,
    'plugin-guide-text'                 => '<div style="display:block">
        <ol>
         <li>Go to the plugin <strong>"Campaigns"</strong> in the admin dashboard left menu</li>
         <li>Add your first campaign. </li>
         <li>Set a name or add notes to your campaign</li>
        <li>Upload the graphics / banners and define the campaign type.</li>
       <li>Use shortcode with the campaign id and place it on any page or post.</li>
       <li>You can also use the sidebar widget with the campaign id.</li>
       <li>You can also use the shortcode in your template code using WordPress php command do_shortcode.</li>
         </ol>
    </div>',
     'plugin-guide-video-height'         => 240,
     'plugin-guide-videos'               => array(
          array( 'title' => 'Installation tutorial', 'video_id' => '162714908' ),
     ),
        'plugin-upgrade-text'           => 'Good Reasons to Upgrade to Pro',
    'plugin-upgrade-text-list'      => array(
        array( 'title' => 'Mobile device and responsive support', 'video_time' => '0:50' ),
        array( 'title' => 'Banner variations', 'video_time' => '1:25' ),
        array( 'title' => 'AdSense campaing support', 'video_time' => '2:00' ),
        array( 'title' => 'Video campaing support', 'video_time' => '2:28' ),
        array( 'title' => 'Campaing groups', 'video_time' => '3:15' ),
        array( 'title' => 'Testing your campaings', 'video_time' => '4:07' ),
        array( 'title' => 'Reports and statistics' , 'video_time' => 'More'),
        array( 'title' => 'HTML banners' , 'video_time' => 'More'),
        array( 'title' => 'Customer dashboard support...' , 'video_time' => 'More'),
      ),
    'plugin-upgrade-video-height'   => 240,
    'plugin-upgrade-videos'         => array(
        array( 'title' => 'Ad Changer Premium Features', 'video_id' => '112134428' ),
    ),
	'plugin-dir-path'			 => plugin_dir_path( CMAC_PLUGIN_FILE ),
	'plugin-dir-url'			 => plugin_dir_url( CMAC_PLUGIN_FILE ),
	'plugin-basename'			 => plugin_basename( CMAC_PLUGIN_FILE ),
	'plugin-icon'				 => '',
	'plugin-name'				 => CMAC_LICENSE_NAME,
	'plugin-license-name'		 => CMAC_LICENSE_NAME,
	'plugin-slug'				 => '',
	'plugin-short-slug'			 => 'ad-changer',
	'plugin-menu-item'			 => CMAC_MENU_OPTION,
	'plugin-textdomain'			 => CMAC_SLUG_NAME,
	'plugin-userguide-key'		 => '178-cmac-getting-started-the-server-plugin',
	'plugin-store-url'			 => 'https://www.cminds.com/wordpress-plugins-library/adchanger?utm_source=adchanger&utm_campaign=freeupgrade&upgrade=1',
	'plugin-support-url'		 => 'https://www.cminds.com/contact/',
	'plugin-review-url'			 => 'https://wordpress.org/support/view/plugin-reviews/cm-ad-changer',
	'plugin-changelog-url'		 => CMAC_RELEASE_NOTES,
	'plugin-licensing-aliases'	 => array( 'CM Ad Changer Pro', 'CM Ad Changer Pro Special' ),
	'plugin-compare-table'	     => $plugin_compare_table,
);