<?php
/**
 * "Related Jobs Pages": a "near me" page lists the job landings found by a search key.
 *
 * The key is stored on the near-me page; the custom title and description are stored
 * on each related landing page and edited from the near-me page's meta box.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

const AW_RELATED_KEY_META   = '_related_jobs_key';
const AW_RELATED_TITLE_META = 'custom_related_job_title';
const AW_RELATED_DESC_META  = 'description';

/**
 * Published pages that match "<key> jobs", except the page itself.
 *
 * @return WP_Post[]
 */
function aw_related_job_pages( int $page_id, string $key ): array {
	if ( '' === trim( $key ) ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			's'              => $key . ' jobs',
			'post__not_in'   => array( $page_id ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
		)
	);
}

add_action( 'add_meta_boxes_page', 'aw_add_related_jobs_metabox' );

/**
 * Adds the meta box to pages.
 */
function aw_add_related_jobs_metabox(): void {
	add_meta_box( 'aw-related-jobs', 'Related Jobs Pages', 'aw_render_related_jobs_metabox', 'page', 'normal', 'high' );
}

/**
 * Search key plus a table of the matched pages with editable title and description.
 */
function aw_render_related_jobs_metabox( WP_Post $post ): void {
	$key   = (string) get_post_meta( $post->ID, AW_RELATED_KEY_META, true );
	$pages = aw_related_job_pages( $post->ID, $key );

	wp_nonce_field( 'aw_related_jobs', 'aw_related_jobs_nonce' );
	?>
	<p>
		<label for="aw-related-jobs-key"><strong>Search key</strong></label>
		<input type="text" class="widefat" name="aw_related_jobs_key" id="aw-related-jobs-key" value="<?php echo esc_attr( $key ); ?>" placeholder="e.g. Plumber">
		<span class="description">Pages whose title matches “key jobs” are listed below and on the page.</span>
	</p>
	<table class="widefat striped">
		<thead>
			<tr>
				<th>Custom title</th>
				<th>Page</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( ! $pages ) : ?>
				<tr><td colspan="3">No related jobs found.</td></tr>
			<?php endif; ?>
			<?php foreach ( $pages as $page ) : ?>
				<tr>
					<td>
						<input type="text" class="widefat" name="aw_related_title[<?php echo (int) $page->ID; ?>]" value="<?php echo esc_attr( (string) get_post_meta( $page->ID, AW_RELATED_TITLE_META, true ) ); ?>">
					</td>
					<td><a href="<?php echo esc_url( (string) get_edit_post_link( $page ) ); ?>"><?php echo esc_html( get_the_title( $page ) ); ?></a></td>
					<td>
						<input type="text" class="widefat" name="aw_related_description[<?php echo (int) $page->ID; ?>]" value="<?php echo esc_attr( (string) get_post_meta( $page->ID, AW_RELATED_DESC_META, true ) ); ?>">
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php
}

add_action( 'save_post_page', 'aw_save_related_jobs' );

/**
 * Saves the key and the per-page titles and descriptions the user is allowed to edit.
 */
function aw_save_related_jobs( int $post_id ): void {
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['aw_related_jobs_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['aw_related_jobs_nonce'] ), 'aw_related_jobs' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['aw_related_jobs_key'] ) ) {
		update_post_meta( $post_id, AW_RELATED_KEY_META, sanitize_text_field( wp_unslash( $_POST['aw_related_jobs_key'] ) ) );
	}

	$fields = array(
		'aw_related_title'       => AW_RELATED_TITLE_META,
		'aw_related_description' => AW_RELATED_DESC_META,
	);

	foreach ( $fields as $input => $meta_key ) {
		$values = isset( $_POST[ $input ] ) && is_array( $_POST[ $input ] ) ? wp_unslash( $_POST[ $input ] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- each value is sanitised below.

		foreach ( $values as $page_id => $value ) {
			$page_id = absint( $page_id );

			if ( $page_id && 'page' === get_post_type( $page_id ) && current_user_can( 'edit_post', $page_id ) ) {
				update_post_meta( $page_id, $meta_key, sanitize_text_field( (string) $value ) );
			}
		}
	}
}
