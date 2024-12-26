<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <?php if (is_archive() && is_post_type_archive('blog')) : ?>
        <title>AroundWire Blog - Insights for Service Providers & Small Businesses</title>
        <meta property="og:image" content="https://pro.aroundwire.com/wp-content/uploads/2024/11/blog_cover_image.webp">
        <meta property="og:title" content="Blogs & Insights | AroundWire" class="yoast-seo-meta-tag">
        <meta property="og:description" content="Stay updated with the latest insights on small business growth, service provider tips, and marketplace innovations at the AroundWire Blog." class="yoast-seo-meta-tag">
    <?php endif; ?>

    <?php if (is_page('sitemap')) : ?>
        <title><?php echo wp_get_document_title(); ?></title>  
    <?php endif; ?>

    <?php if (is_single() && 'blog' === get_post_type()) : ?>
        <title><?php echo wp_get_document_title(); ?></title>  
    <?php endif; ?>

    <?php if (is_tax('blog_tag')) : ?>
        <title><?php single_term_title(); ?> Tag Artciles - AroundWire</title>
    <?php endif; ?>

    <?php if (is_search()) : ?>
        <?php if (have_posts()) : ?>
            <title>Stay Updated with the Latest Insights - Subscribe to Our Blog</title>
            <meta name="description" content="Get the latest tips, insights, and strategies to help your American business thrive. Subscribe to our blog for regular updates!">
        <?php else : ?>
            <title>No Blogs Found - Stay Tuned for Updates</title>
            <meta name="description" content="It looks like there are no blogs available right now, but don’t worry! Check back soon as we regularly add new content!">
        <?php endif; ?>
    <?php endif; ?>
    
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KZJ3N5T3');</script>

    <script src="//code.jivosite.com/widget/CmI9Acp1H1" async></script>

    <?php wp_head(); ?>
</head>
<?php
$menu_version = 'default-menu';

if (is_search() || is_archive() && is_post_type_archive('blog') || is_tax('blog_tag') || is_search() && have_posts() || is_page('sitemap') || is_singular('blog')) {
    $menu_version = 'blog-menu';
} 
?>
<body <?php body_class( is_page('sitemap') ? 'sitemap-page ' : '' ); ?>>
    
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KZJ3N5T3"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <nav aria-label="menu blog" class="main-menu <?php echo esc_attr($menu_version); ?>">
        <ul id="menu-main-menu-blog" class="menu">
            <li id="menu-item-1148" class="header-menu__logo menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-1148">
                <a href="<?php echo home_url(); ?>">Be a Pro with AroundWire and Get More Clients</a>
            </li>
            <li id="menu-item-1842" class="header-menu__flex no-link menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor current-menu-parent menu-item-has-children menu-item-1842">
                <ul class="sub-menu">
                	<button class="search-button menu-item menu-item-type-custom menu-item-object-custom menu-item-3706" id="searchButton_0"></button>
                    <li id="menu-item-547" class="header-menu__blog menu-item menu-item-type-post_type_archive menu-item-object-blog current-menu-item menu-item-547">
                        <a href="<?php echo get_post_type_archive_link('blog'); ?>" aria-current="page">Blog</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>