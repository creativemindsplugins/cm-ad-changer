<div class="wrap">
	<h2>CM Ad Changer
        <?php if ( strpos( $_SERVER[ 'REQUEST_URI' ], 'cmac_pro' ) !== false ) { ?>
        <?php } else { ?>
            <a href="<?php echo esc_url( get_admin_url( '', 'admin.php?page=cmac_pro' ) ); ?>" class="button button-primary" title="Click to Buy PRO">Upgrade to Pro</a>
        <?php } ?>
    </h2>
	<?php
    echo do_shortcode( '[cminds_free_activation id="cmac"]' );
    ?>
	<div id="cminds_settings_container">
		<?php CMAdChangerBackend::cmac_showNav(); ?>
		<div class="show_hide_pro_options" style="position:absolute; right:20px; margin-top:-50px;">
			<input onclick="jQuery('.onlyinpro').toggleClass('hide'); return false;" type="button" name="" value="Show/hide Pro options" class="button" />
		</div>
		<?php echo $content; ?>
	</div>
</div>