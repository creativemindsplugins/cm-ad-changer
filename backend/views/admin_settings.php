<?php
/**
 * CM Ad Changer
 *
 * @author CreativeMinds (https://www.cminds.com/wordpress-plugins-library/adchanger/)
 * @copyright Copyright (c) 2013, CreativeMinds
 */
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
                <li><a href="#tab-upgrade">Upgrade</a></li>
               <li><a href="#tab-installation">Installation Guide</a></li>
             <li><a href="#tab-shortcode">Shortcode</a></li>
               </ul>

            <div id="tab-upgrade">
                <div class='block'>
                <table><tbody>
                        <tr>
                            <td> <?php echo do_shortcode( '[cminds_upgrade_box id="cmac"]' ); ?></td>
                        </tr>
                    </tbody></table>
            </div>
           </div>



            <div id="tab-installation">
                <div class='block'>
                <table><tbody>
                        <tr>
                            <td><?php echo do_shortcode( '[cminds_free_guide id="cmac"]' ); ?></td>
                        </tr>
                    </tbody></table>
            </div>
           </div>

         <div id="tab-shortcode">
                <div class='block'>
    <p>To insert the ads into a page or post use following shortcode: <strong>[cm_ad_changer]</strong>. Here is the list of parameters: </p>
    <ul style="list-style-type: disc; margin-left: 20px;">
        <li>
            <strong>campaign_id</strong> - ID of a campaign (required)
        </li>
        <li>
            <strong>linked_banner</strong> - Banner is a linked image or just image. Can be 1 or 0 (default: 1)
        </li>
        <li>
            <strong>debug</strong> - Show the debug info. Can be 1 or 0 (default: 0)
        </li>
        <li>
            <strong>wrapper</strong> - Wrapper On or Off. Wraps banner with  a div tag. Can be 1 or 0 (default: 0)
        </li>
        <li>
            <strong>class</strong> - Banner (div) class name
        </li>
    </ul>

            </div>
           </div>

   
        </div>
     </form>
</div>