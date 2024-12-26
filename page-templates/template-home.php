<?php
/*
Template Name: Homepages 2
*/
get_header();
?>
<section class="homepages__wrapper">
    <div class="container-big">
        <div class="hero-block__flex">
            <h1 class="display-largest">
                <b><?php the_field('hero-block-title-red'); ?></b>
                <br>
                <?php the_field('hero-block-title'); ?>
            </h1>
            <div>
                <p class="title-largest"><?php the_field('hero-block-tagline'); ?></p>
                <p class="body-larger-medium"><?php the_field('hero-block-text'); ?></p>
            </div>
        </div>
    </div>
    <div class="hero-block__relative">
        <button  class="noLink link-button nofollow button-larger primary-button-larger form-popup-button"><?php the_field('hero-block-button'); ?></button>
    </div>
</section>

<?php
$excluded_urls = [];

$excluded_urls[] = rtrim(home_url(), '/');
$excluded_urls[] = home_url('/sitemap/');
?>

<section class="homepages__wrapper">
    <div class="container-big">
        <div class="catalog">
            <!-- Catalog Top -->
            <div class="catalog-top">
                <?php if (have_rows('catalog-top')) : ?>
                    <?php while (have_rows('catalog-top')) : the_row(); ?>
                        <?php
                        $url = get_sub_field('profession-url');
                        if ($url) {
                            $excluded_urls[] = rtrim($url, '/');
                        }
                        ?>
                        <div class="profession-card">
                            <picture>
                                <source media="(min-width: 1300px)" srcset="<?php the_sub_field('profession-img'); ?>">
                                <img src="<?php the_sub_field('profession-img-mobile'); ?>" alt="<?php the_sub_field('profession-title'); ?>" width="600" height="480" loading="lazy">
                            </picture>
                            <div>
                                <h2 class="title-larger"><?php the_sub_field('profession-title'); ?></h2>
                                <h3 class="body-large-medium"><?php the_sub_field('profession-text'); ?></h3>
                                <a class="button-medium primary-button-medium" href="<?php echo esc_url($url); ?>">Get Started</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- Catalog Bottom -->
            <div class="catalog-bottom">
                <?php if (have_rows('catalog-bottom')) : ?>
                    <?php while (have_rows('catalog-bottom')) : the_row(); ?>
                        <?php
                        $url = get_sub_field('profession-url');
                        if ($url) {
                            $excluded_urls[] = rtrim($url, '/');
                        }
                        ?>
                        <div class="profession-card">
                            <picture>
                                <source media="(min-width: 1300px)" srcset="<?php the_sub_field('profession-img'); ?>">
                                <img src="<?php the_sub_field('profession-img-mobile'); ?>" alt="<?php the_sub_field('profession-title'); ?>" width="600" height="480" loading="lazy">
                            </picture>
                            <div>
                                <h2 class="title-larger"><?php the_sub_field('profession-title'); ?></h2>
                                <h3 class="body-large-medium"><?php the_sub_field('profession-text'); ?></h3>
                                <a class="button-medium primary-button-medium" href="<?php echo esc_url($url); ?>">Get Started</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

            <!-- Catalog More -->
            <div class="catalog-more">
                <p class="title-large">Join a vibrant community of skilled trade professionals and get the opportunity to shine!</p>
                <p >
                <?php
                $args = [
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ];
                $query = new WP_Query($args);
            
                // Array for pages with "Jobs"
                $jobs_pages = [];
            
                // Get all pages
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $raw_title = get_the_title();
                
                        // Check if the title contains "Jobs" and does not contain "Leads"
                        if (strpos($raw_title, 'Jobs') !== false && strpos($raw_title, 'Leads') === false) :
                            $jobs_pages[] = [
                                'url' => get_permalink(),
                                'title' => $raw_title
                            ];
                        endif;
                    endwhile;
                    wp_reset_postdata();
                endif;
            
                // Count the number of pages with "Jobs"
                $total_jobs_pages = count($jobs_pages); // Total number of pages with "Jobs"
                $current_page_index = 0; // Current page index
            
                // Output the pages with "Jobs"
                foreach ($jobs_pages as $page) :
                    $current_page_index++;
                
                    // Clean the title from unnecessary phrases
                    $clean_title = preg_replace(
                        '/\bJobs\b(\s+)?|\s?(in Las Vegas\s–\sApply Now!|in Las Vegas\s–\sApply Today!|Leads in Las Vegas\s–\sGet Your Customers Now!|Leads in Las Vegas\s–\sGet Your Costumers Now!)$/i',
                        '',
                        $page['title']
                    );
                
                    // Remove any extra space at the end, if present
                    $clean_title = preg_replace('/\s+$/', '', $clean_title);
                
                    // Output the link
                    ?>
                    <span class="link-wrapper link-medium-underline">
                        <a href="<?php echo esc_url($page['url']); ?>"><?php echo esc_html($clean_title); ?></a>
                        <?php 
                        // Check if this is not the last page, then add a comma
                        if ($current_page_index < $total_jobs_pages) : ?>
                            <span class="comma">,</span>
                        <?php endif; ?>
                    </span>
                        
                <?php endforeach; ?>
                    <span class="body-medium-medium">and more – enlist today!</span>
                </p>
            </div>
        </div>
    </div>
</section>


<section class="homepages__wrapper">
    <div class="container-xsmall">
        <div class="benefits">
            <div class="benefits-flex">
                <h2 class="display-large"><?php the_field('benefits-title'); ?></h2>
                <p class="body-larger-medium"><?php the_field('benefits-text'); ?></p>
            </div>
            <div class="benefits-items">
                <?php if (have_rows('benefits-item')) : ?>
                    <?php while (have_rows('benefits-item')) : the_row(); ?>
                        <div class="benefits-item">
                            <h3 class="body-larger-semibold"><?php the_sub_field('benefits-item-text'); ?></h3>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="benefits-button">
                <button  class="noLink link-button nofollow button-larger primary-button-larger form-popup-button">Get Started</button>
            </div>
            <div class="benefits-video">
                <video id="benefits-video" playsinline="true" autoplay="true" loop="true" muted="true" preload="auto">
                    <source src="/wp-content/uploads/2024/08/aw_video.webm" type="video/webm">
                    <source src="/wp-content/uploads/2024/08/aw_video.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-big">
        <div class="success-steps">
            <h2 class="display-large"><?php the_field('sussess-steps-title'); ?></h2>
            <p class="body-larger-medium"><?php the_field('sussess-steps-text'); ?></p>
            <div class="steps-items">
                <?php if (have_rows('steps')) : ?>
                    <?php while (have_rows('steps')) : the_row(); ?>
                        <div class="steps-item">
                            <h3 class="title-larger"><?php the_sub_field('steps-title'); ?></h3>
                            <img src="<?php the_sub_field('steps-img'); ?>" alt="<?php the_sub_field('steps-title'); ?>" loading="lazy">
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-xsmall">
        <div class="success-steps">
            <p class="body-larger-medium">Grow With Us</p>
            <div class="success-steps-video">
                <video id="video" playsinline loop preload="auto" poster="/wp-content/uploads/2024/09/video_placeholder.webp">
                    <source src="<?php the_field('sussess-video'); ?>" type="video/mp4">
                </video>
                <div class="video-controls">
                    <button id="playPauseButton">
                        <img src="/wp-content/uploads/2024/08/Play-Button-1.svg" alt="Play" loading="lazy">
                    </button>
                    <button id="muteButton">
                        <img src="/wp-content/uploads/2024/08/Mute-Button.svg" alt="Mute" loading="lazy">
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>    

<section class="homepages__wrapper">
    <div class="container-xsmall">
        <div class="use-app">
            <h2 class="display-large"><?php the_field('use-app-title'); ?></h2>
            <img src="<?php the_field('use-app-img'); ?>" alt="<?php the_field('use-app-title'); ?>" loading="lazy" width="600" height="480">
            <p class="body-larger-regular"><?php the_field('use-app-text'); ?></p>
            <div class="use-app-button">
                <button  class="noLink link-button nofollow button-larger primary-button-larger form-popup-button">Join Now!</button>
                <!-- onclick="window.location.href='https://aroundwire.com/api/auth/register'" -->
            </div>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-big">
        <div class="start-work_grid">
            <div class="start-work__reviews">
            <?php
            $type = 'variant2'; 
                    
            load_template_with_args('template-parts/components/modules/block-reviews', [
                'type' => $type
            ]);
            ?>
            </div>
            <div class="start-work__form">
                <?php load_template_with_args('template-parts/components/ui-elements/form-messeng', ['form_type' => 'home_form']);?>
            </div>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-xsmall">
        <div class="faqs">
            <h2 class="display-large"><?php the_field('faqs-title'); ?></h2>
            <p class="body-larger-medium"><?php the_field('faqs-text'); ?></p>
            <div class="faqs_flex">
                <?php
                $args = array(
                    'taxonomy' => 'home-page',
                    'post_type' => 'faqs',
                    'posts_per_page' => 5,
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) : 
                    // JSON-LD Block for FAQs
                    ?>
                    <script type="application/ld+json">
                    {
                      "@context": "https://schema.org",
                      "@type": "FAQPage",
                      "mainEntity": [
                        <?php
                        $faqs = [];

                        // Loop through posts to gather FAQ data
                        while ($query->have_posts()) : $query->the_post();
                            $faqs[] = [
                                "@type" => "Question",
                                "name" => wp_strip_all_tags(get_the_title()),
                                "acceptedAnswer" => [
                                    "@type" => "Answer",
                                    "text" => wp_strip_all_tags(get_the_content())
                                ]
                            ];
                        endwhile;
                    
                        // Ensure no trailing comma in JSON output
                        echo wp_json_encode($faqs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        ?>
                      ]
                    }
                    </script>
                    <?php
                    // Reset post data to use WP_Query again
                    wp_reset_postdata(); 
                
                    // Re-run the query to display FAQs in HTML
                    $query = new WP_Query($args); // Re-query to show FAQs in HTML
                
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="faq-item">
                            <h3 class="title-larger"><?php the_title(); ?><img class="toggle-image" src="/wp-content/uploads/2024/12/open-button.svg" alt="picture-plus" loading="lazy"></h3>
                            <div class="body-medium-regular">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p><?php esc_html_e('No FAQs found.'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-big">
        <div class="blog">
            <h2 class="display-large"><?php the_field('blog-title'); ?></h2>
            <div class="blog__more-links"><p class="body-larger-medium"><?php the_field('blog-text'); ?></p> <a class="link-large-default" href="<?php echo get_post_type_archive_link('blog'); ?>">More Posts ></a></div>
            <div class="blog_grid">
                <?php
                $args = array(
                    'post_type'      => 'blog',
                    'posts_per_page' => 3,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category', 
                            'field'    => 'slug',
                            'terms'    => 'top',
                        ),
                    ),
                );
                $query = new WP_Query( $args );
                if ( $query->have_posts() ) :
                    while ( $query->have_posts() ) : $query->the_post();
                        load_template_with_args('template-parts/components/modules/template-post', [
                            'post_class' => 'default-post',
                            'show_excerpt' => false,
                            'show_tags' => false
                        ]);
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p><?php esc_html_e( 'No posts found.', 'your-theme-textdomain' ); ?></p>
                <?php endif; ?>
            </div>
            <a class="link-large-default" href="<?php echo get_post_type_archive_link('blog'); ?>">More Posts ></a>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-big">
        <div class="buyer-stories">
            <h2 class="display-large"><?php the_field('buyer-stories-title'); ?></h2>
            <p class="body-larger-medium"><?php the_field('buyer-stories-text'); ?></p>
        </div>
    </div>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "AroundWire",
      "url": "https://pro.aroundwire.com/",
      "logo": "https://pro.aroundwire.com/wp-content/uploads/2024/07/logo_black.svg", 
      "sameAs": [
        "https://www.facebook.com/aroundwire",
        "https://www.instagram.com/aroundwire"
      ],
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "5",
        "reviewCount": "6"
      },
      "review": [
        {
          "@type": "Review",
          "author": {"@type": "Person", "name": "John Garrison"},
          "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5", "worstRating": "1"},
          "reviewBody": "With so many sketchy platforms out there, I was initially skeptical about AroundWire. Signing up didn't cost anything, and now I have one more place to promote myself. Nice work.",
          "datePublished": "2024-09-20"
        },
        {
          "@type": "Review",
          "author": {"@type": "Person", "name": "Will Donovan"},
          "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5", "worstRating": "1"},
          "reviewBody": "I know we're always cautious about new things, but AroundWire is worth trying. I listed my plumbing services and got some interest from new clients.",
          "datePublished": "2024-09-20"
        },
        {
          "@type": "Review",
          "author": {"@type": "Person", "name": "Anthony Perkins"},
          "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5", "worstRating": "1"},
          "reviewBody": "I listed my HVAC services. It's hot as hell in Vegas, and another source of customers only helps. Didn't cost me anything to list. Thanks for the promo.",
          "datePublished": "2024-09-20"
        },
        {
          "@type": "Review",
          "author": {"@type": "Person", "name": "Veronica Smillers"},
          "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5", "worstRating": "1"},
          "reviewBody": "I hate doing cold calls. When I promote my cleaning business, it is always a PIA. Thanks, AW, for building a platform where I can just list my stuff and get customers. Thanks for supporting small business.",
          "datePublished": "2024-09-20"
        },
        {
          "@type": "Review",
          "author": {"@type": "Person", "name": "Ana Smith"},
          "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5", "worstRating": "1"},
          "reviewBody": "I'm in landscaping design, and finding new customers has been a chore, to say the least. With AroundWire, I don't have to worry about that at all.",
          "datePublished": "2024-09-20"
        },
        {
          "@type": "Review",
          "author": {"@type": "Person", "name": "Tyler Johnson"},
          "reviewRating": {"@type": "Rating", "ratingValue": "5", "bestRating": "5", "worstRating": "1"},
          "reviewBody": "As a locksmith, I like to be solid, just like the keys I make. Listed my stuff. Hopefully, I get customers. Seems to be a nice platform.",
          "datePublished": "2024-09-20"
        }
      ]
    }
    </script>
    <div class="buyer-stories container-big">
        <div class="buyer-stories__cards">
            <?php
            load_template_with_args('template-parts/components/modules/block-reviews', [
                'type' => 'variant1'
            ]);
            ?>
        </div>
    </div>
</section>

<section class="homepages__wrapper">
    <div class="container-big">
        <div class="get-start">
            <button  class="noLink link-button nofollow primary-button-larger button-larger form-popup-button">Join Now</button>
        </div>
    </div>
</section>

<div id="videoModal" class="video-modal">
    <span class="close" onclick="closeVideoModal()"></span>
    <div class="video-container">
        <iframe id="videoFrame" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
</div>

<?php load_component_with_assets('template-parts/components/ui-elements/scroll-to-top');?>

<div id="thank-you-popup" class="thank-you-popup">
    <div class="thank-you-popup-content">
        <span class="close-popup"><img src="/wp-content/uploads/2024/08/close-button.svg"></span>
        <img src="/wp-content/uploads/2024/08/thank-you-popup-img.svg" loading="lazy">
        <h2 class="display-small">Thank you!</h2>
        <p class="body-medium-medium">Welcome to AroundWire, where small businesses come to succeed.</p>
        <a class="nofollow primary-button-small button-small" href="https://pro.aroundwire.com/">Continue</a>
    </div>
</div>

<?php get_footer(); ?>