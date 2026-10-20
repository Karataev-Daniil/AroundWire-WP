<?php
/**
 * Document head and the top menu. Blog pages get the blog menu with search.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

$aw_body_classes = is_page_template( 'page-templates/template-sitemap.php' ) ? array( 'sitemap-page' ) : array();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( $aw_body_classes ); ?>>
<?php
wp_body_open();

if ( aw_is_blog_context() ) {
	get_template_part( 'template-parts/header/nav-blog' );
} else {
	get_template_part( 'template-parts/header/nav-main' );
}
