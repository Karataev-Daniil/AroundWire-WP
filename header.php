<!DOCTYPE html>
<html lang="en-US">
<head>
    <?php if (is_404()) : ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>AroundWire</title>
    <?php endif; ?>

    <?php if (is_page_template('page-templates/template-home.php') || is_page_template('landing-layout.php')) : ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title><?php wp_title(); ?></title>
        <style name="primary-kit-lendings">
            :root {
                /* Primary colors 'menu' */
                --menu-color: <?php the_field('main-menu_color') ?>;
                --menu-logo: url('<?php the_field('main-menu_logo') ?>');
            
                /* Primary colors 'hero-block' */
                --hero-block-color: <?php the_field('hero-block_color') ?>;
                --hero-block-color-two: <?php the_field('hero-block_color-two') ?>;
            
                /* Primary colors 'benefits-block' */
                --benefits-block-color: <?php the_field('benefits-block_color') ?>;
                --benefits-icon-color-basic: <?php the_field('benefit_icon_basic_color') ?>;
                --benefits-icon-color-second: <?php the_field('benefit_icon_second_color') ?>;

                --success-steps-background-color: <?php the_field('success-steps-block_color') ?>;
                --success-steps-number-color: <?php the_field('card-number-color') ?>;

                --expand-clientele-background-color: <?php the_field('expand-clientele-block_color') ?>;
            
                /* Primary colors 'more-views' */
                --more-views-block-color: <?php the_field('more-views-block_color') ?>;
            
                /* Primary colors 'start-work-block' */
                --start-work-block-color: <?php the_field('start-work-block_color') ?>;
                --start-work-icon-color-basic: <?php the_field('start-work__icon__basic-color') ?>;
                --start-work-icon-color-second: <?php the_field('start-work__icon__second-color') ?>;
            
                --faqs-background-color: <?php the_field('faqs-block_color') ?>;
            
                --fitback-block-color: <?php the_field('fitback-block_color'); ?>;
                --fitback-block-color-2: <?php the_field('fitback-block_color_2'); ?>;
                --fitback-text-color: <?php the_field('fitback-text_color'); ?>;
                --footer-menu-color: <?php the_field('footer-menu_color'); ?>;
            }
        </style>
    <?php endif; ?>

    <?php if (is_page_template('page-templates/template-near-me.php') || is_page_template('page-templates/template-sitemap-near-me.php')) : ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title><?php wp_title(); ?></title>
    <?php endif; ?>

    <style name="primary-kit">
        :root {
            --menu-logo-black: url('/wp-content/uploads/2024/07/logo_black.svg');
        }
    </style>
    <meta name="google-site-verification" content="ZV2GpAnN3tqLZCjqD0Pn64TBAb_8YhcacN0fx2FNbuQ" />

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KZJ3N5T3');</script>
    <!-- End Google Tag Manager -->

    <script src="//code.jivosite.com/widget/CmI9Acp1H1" async></script>

    <?php wp_head(); ?>
</head>
<?php
$menu_version = 'default-menu';
$menu_version_design = get_field('menu_version') ?: 'default-menu'; // Значение по умолчанию

if (is_page_template('page-templates/template-sitemap-near-me.php') || is_page_template('page-templates/template-near-me.php') || is_404() || is_page_template('landing-layout.php')) {
    $menu_version = 'landing-and-error-menu';
} elseif (is_page_template('page-templates/template-home.php')) {
    $menu_version = 'homepage-menu';
} elseif (is_page_template('landing-layout.php')) {

} 
?>
<body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KZJ3N5T3"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <nav aria-label="main menu" class="main-menu <?php echo $menu_version ? ' ' . esc_attr($menu_version) : ''; ?> <?php echo $menu_version_design ? esc_attr($menu_version_design) : ''; ?>">
        <ul id="menu-main-menu" class="menu">
            <li id="menu-item-1149" class="header-menu__logo menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-1145 current_page_item menu-item-1149">
                <a href="<?php echo home_url(); ?>" aria-current="page">Be a Pro with AroundWire and Get More Clients</a>
            </li>
            <li id="menu-item-1835" class="header-menu__flex no-link menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-1835">
                <ul class="sub-menu">
                	<li id="menu-item-238" class="header-menu__number menu-item menu-item-type-custom menu-item-object-custom menu-item-238"><a href="tel:+17029793555">1-702-979-3555</a></li>
                	<li id="menu-item-240" class="header-menu__button nofollow menu-item menu-item-type-custom menu-item-object-custom menu-item-240">
                        <button  class="noLink link-button form-popup-button">Join Us Today!</button>
                    </li>
                </ul>
            </li>
        </ul> 
    </nav>