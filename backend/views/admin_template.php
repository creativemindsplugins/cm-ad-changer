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

    <?php echo $content; ?>
      </div>
</div>