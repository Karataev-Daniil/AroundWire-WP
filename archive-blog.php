<?php
get_header('blog');
?>
    <section class="blog__wrapper">
        <div class="container-small">
            <?php get_template_part( 'template-parts/blog/form-search');?>

            <div class="tatle-block">
                <div class="label-large">AroundWire Blog</div>
                <h1 class="display-large">Get Expert Tips, Tricks, and Practical Guidelines to Scale Your Business!</h2>
                <p class="body-large-medium">Learn the ins and outs of your industry, as well as best practices and recommendations from seasoned experts.</p>
            </div>
     
            <?php get_template_part( 'template-parts/blog/posts/hero-blocks', 'v2');?>

            <div class="divider"></div>

            <?php get_template_part( 'template-parts/blog/posts/three-colom-popular');?>

            <div class="divider"></div>

            <?php get_template_part( 'template-parts/blog/posts/load-more');?>
            
            <?php get_template_part( 'template-parts/blog/form-newsletter');?>
        </div>
    </section>
<?php
get_footer('footer-menu');
?>