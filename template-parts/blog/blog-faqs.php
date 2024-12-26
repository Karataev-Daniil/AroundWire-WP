<?php if (have_rows('post-faqs')) : ?>
    <div class="faqs" itemscope itemtype="https://schema.org/FAQPage">
        <h2 class="title-large">FAQ</h2>
        <?php while (have_rows('post-faqs')) : the_row(); ?>
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <span class="body-medium-semibold faq-title" itemprop="name">
                    <?php the_sub_field('faq-title'); ?>
                </span>
                <div class="faq-content" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <p class="body-medium-regular" itemprop="text">
                        <?php the_sub_field('faq-text'); ?>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
