    <?php load_template_with_args('template-parts/components/ui-elements/form-popup');?>
    <footer>
        <?php
        global $footer_menu_version;

        if (!isset($footer_menu_version)) {
            $footer_menu_version = 'default';
        }
        ?>
        <nav aria-label="footer menu" class="footer-menu__wrapper <?php echo esc_attr($footer_menu_version); ?>">
            <div class="container">
                <ul class="footer-menu">
                    <li class="footer-menu_flex-top no-link current-menu-ancestor menu-item-has-children">
                        <ul class="sub-menu">
                        	<div class="footer-menu__block no-link current-menu-ancestor current-menu-parent menu-item-has-children">
                            	<ul class="sub-menu">
                            		<li class="footer-menu__block__item body-medium-regular no-link"><p>Grow Your Business with AroundWire<br> Find Jobs Faster, Get Gigs Effortlessly!</p></li>
                            		<li class="footer-menu__logo menu-item-type-post_type menu-item-object-page menu-item-home current-page_item current_page_item"><a href="<?php echo home_url(); ?>" aria-current="page">Be a Pro with AroundWire and Get More Clients</a></li>
                            	</ul>
                            </div>
                            <div class="footer-menu__block">
                            	<ul class="sub-menu">
                            		<li id="menu-item-273" class="footer-menu__block__item link-medium-default nofollow menu-item"><button class="noLink link-button" onclick="window.location.href='https://aroundwire.com/marketplace?view=grid'">Explore Marketplace</button></li>
                            		<li id="menu-item-274" class="footer-menu__block__item link-medium-default nofollow menu-item"><button class="noLink link-button" onclick="window.location.href='https://aroundwire.com/privacy-policy'">Privacy Policy</button></li>
                            		<li id="menu-item-275" class="footer-menu__block__item link-medium-default nofollow menu-item"><button class="noLink link-button" onclick="window.location.href='https://aroundwire.com/terms-of-service'">Terms of Service</button></li>
                                    <?php if (is_page('sitemap') || is_search() || is_post_type_archive('blog') || is_singular('blog') || is_tax('blog_tag')): ?>
                                        <li id="menu-item-sitemap" class="footer-menu__block__item link-medium-default nofollow menu-item">
                                            <a href="/sitemap/">Sitemap</a>
                                        </li>
                                    <?php endif; ?>
                            	</ul>
                            </div>
                        	<?php if (!is_page_template('landing-layout.php')): ?>
                                <div class="footer-menu__block">
                                    <ul class="sub-menu">
                                        <li class="footer-menu__block__item body-medium-regular no-link menu-item-1801">
                                            <p>Start Building Your Professional Presence on AroundWire.</p>
                                        </li>
                                        <li class="footer-menu__block__item button-larger nofollow menu-item-276">
                                            <button class="noLink link-button form-popup-button">
                                                List your services today!
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="footer-menu__block">
                                    <ul class="sub-menu">
                                        <li class="footer-menu__mass-media no-link menu-item-1841">
                                            <ul class="sub-menu">
                                                <li class="facebook-logo open-in-new-tab menu-item-51">
                                                    <a href="https://www.facebook.com/aroundwire" target="_blank">facebook</a>
                                                </li>
                                                <li class="Instagram-logo open-in-new-tab menu-item-54">
                                                    <a href="https://www.instagram.com/aroundwire" target="_blank">Instagram</a>
                                                </li>
                                                <li class="twitter-logo open-in-new-tab menu-item-53">
                                                    <a href="https://twitter.com/aroundwire" target="_blank">twitter</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="footer-copyright body-small-regular no-link menu-item-1799">
                                            <p>© 2016 – 2024 • AroundWire All Rights Reserved</p>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php if (!is_page_template('landing-layout.php')): ?>
                        <li class="footer-menu_flex-bottom no-link menu-item-1840">
                            <ul class="sub-menu">
                            	<li class="footer-menu__mass-media no-link menu-item-1841">
                                	<ul class="sub-menu">
                                		<li class="facebook-logo open-in-new-tab menu-item-51"><a href="https://www.facebook.com/aroundwire" target="_blank">facebook</a></li>
                                		<li class="Instagram-logo open-in-new-tab menu-item-54"><a href="https://www.instagram.com/aroundwire" target="_blank">Instagram</a></li>
                                		<li class="twitter-logo open-in-new-tab menu-item-53"><a href="https://twitter.com/aroundwire" target="_blank">twitter</a></li>
                                	</ul>
                                </li>
                            	<li class="footer-copyright body-small-regular no-link menu-item-1799"><p>© 2016 – 2024 • AroundWire All Rights Reserved</p></li>
                            </ul>
                        </li>
                    <?php else: ?>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
        <?php wp_footer(); ?>
        <link href="https://meetings.brevo.com/assets/styles/popup.css" rel="stylesheet" />
        <script src="https://meetings.brevo.com/assets/libs/popup.min.js" type="text/javascript"></script>
    </footer>
  </body>
</html>
