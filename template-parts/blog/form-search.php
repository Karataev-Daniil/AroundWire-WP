<div id="searchPanel" class="search-panel has-content">
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/blog/')); ?>">
        <label>
            <span class="screen-reader-text"><?php echo _x('Search for:', 'label'); ?></span>
            <input class="search-field body-medium-regular" placeholder="<?php echo esc_attr_x('Search…', 'placeholder'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="button" id="clear-search" class="search-clear-button" aria-label="<?php echo _x('Clear search', 'button label'); ?>"></button>
        </label>
    </form>
</div>