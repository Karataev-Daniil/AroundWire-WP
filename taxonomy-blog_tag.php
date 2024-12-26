<?php
get_header('blog');
?>
    <section class="blog__wrapper">
        <div class="container-small">
            <?php get_template_part( 'template-parts/blog/form-search');?>
            
            <div class="tatle-block">
                <div class="label-large">AroundWire Blog</div>
                <h1 class="display-large">Unleash your home’s potential.<br> Discover expert tips & tricks</h1>
                <p class="body-large-medium">You can find almost any product or service you need from local providers online</p>
            </div>

            <?php get_template_part( 'template-parts/blog/posts/three-columns-by-tags' );?>

            <?php get_template_part( 'template-parts/blog/posts/four-columns-slaider' );?>

            <?php get_template_part( 'template-parts/blog/form-newsletter' );?>
        </div>
    </section>

<?php 
get_footer(); 
?>