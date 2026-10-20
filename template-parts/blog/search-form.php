<?php
/**
 * Blog search panel, opened by the search button in the blog menu.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="searchPanel" class="search-panel<?php echo '' !== get_search_query() ? ' has-content' : ''; ?>">
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>
			<span class="screen-reader-text">Search for:</span>
			<input type="search" class="search-field body-medium-regular" placeholder="Search…" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
			<button type="button" class="search-clear-button" data-search-clear aria-label="Clear search"></button>
		</label>
	</form>
</div>
