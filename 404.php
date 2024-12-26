<?php get_header(); ?>

    <div class="error404__wrapper">
        <div class="error404-block">
            <img src="/wp-content/uploads/2024/08/arrow_404_v2-1.svg" alt="" class="error404-block-img">
            <div class="error404-block-info">
                <div class="display-largest">404</div>
                <h1 class="display-small">Sorry! This Page Isn’t Available</h1>
                <p class="body-medium-medium">The page you were looking for couldn’t be found.</p>
                <a href="<?php echo home_url(); ?>" class="button-small primary-button-small">Back to Home Page</a>
            </div>
        </div>
    </div>
    
<?php get_footer(); ?>
