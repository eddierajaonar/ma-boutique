<?php
/**
 * Page d'accueil (prioritaire sur home.php et index.php).
 */

get_header();
?>
<!-- Image section-->
<div>
	<img class="w-full h-auto" alt="" src="<?php header_image(); ?>"
	width="<?php echo absint( get_custom_header()->width ); ?>" 
	height="<?php echo absint( get_custom_header()->height ); ?>">
</div>

<?php
get_footer();
