<style type="text/css">
    .subsubsub li+li:before {content:'| ';}
</style>

<ul class="subsubsub">
    <?php foreach($submenus as $menu): ?>
        <li><a href="<?php echo $menu['link']; ?>" target="<?php echo $menu['target']; ?>" <?php echo ($menu['current']) ? 'class="current"' : ''; ?>><?php echo $menu['title']; ?></a></li>
    <?php endforeach; ?>
</ul>

<br class="clear" />

<h2>
    <div id="icon-<?php echo CMAC_MENU_OPTION; ?>" class="icon32">
        <br />
    </div>
    <?php echo CMAC_NAME . ' - ' . self::$currentSubpage[0] ?>
</h2>

<?php
if( isset($errors) && !empty($errors) )
{
    ?>
    <ul class="msg_error clear">
        <?php
        foreach($errors as $error) echo '<li>' . $error . '</li>';
        ?>
    </ul>
    <?php
}

if( isset($success) && !empty($success) ) {
    ?>
    <div class="msg_success clear" style="background:#fff; border:1px solid #c3c4c7; border-left-width:4px; box-shadow:0 1px 1px rgba(0,0,0,.04); padding:10px 12px; margin:5px 0 15px; border-left-color:#00a32a;"><?php echo $success ?></div>
	<?php
}
?>