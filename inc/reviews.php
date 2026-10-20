<?php
/**
 * Customer video reviews, shown on the home page and on landings.
 *
 * @package AroundWire
 */

defined( 'ABSPATH' ) || exit;

/**
 * Reviews with photo, profession, text and YouTube embed URL.
 *
 * @return array<int, array{name: string, profession: string, text: string, image: string, video: string}>
 */
function aw_reviews(): array {
	$reviews = array(
		array( 'John Garrison', 'General Contractor', 'John.webp', 'wqC-uwKB7I0', 'With so many sketchy platforms out there, I was initially skeptical about AroundWire. Signing up didn’t cost anything, and now I have one more place to promote myself. Nice work.' ),
		array( 'Will Donovan', 'Plumber', 'WIll.webp', 'dk_8eAcwxnY', 'I know we’re always cautious about new things, but AroundWire is worth trying. I listed my plumbing services and got some interest from new clients.' ),
		array( 'Anthony Perkins', 'HVAC Technician', 'Anthony-Perkins.webp', 'EU5up9nRUFA', 'I listed my HVAC services. It’s hot as hell in Vegas, and another source of customers only helps. Didn’t cost me anything to list. Thanks for the promo.' ),
		array( 'Veronica Smillers', 'Cleaning Specialist', 'Veronica-Smillers.webp', 'qB9r0sRt2PI', 'I hate doing cold calls. When I promote my cleaning business, it is always a PIA. Thanks, AW, for building a platform where I can just list my stuff and get customers. Thanks for supporting small business.' ),
		array( 'Ana Smith', 'Landscape Designer', 'Ana-Smith.webp', 'qypL9E1gSpI', 'I’m in landscaping design, and finding new customers has been a chore, to say the least. With AroundWire, I don’t have to worry about that at all.' ),
		array( 'Tyler Johnson', 'Professional Locksmith', 'Tyler-Johnson.webp', 'uUfXbPGflcY', 'As a locksmith, I like to be solid, just like the keys I make. Listed my stuff. Hopefully, I get customers. Seems to be a nice platform.' ),
	);

	return array_map(
		static fn( array $review ): array => array(
			'name'       => $review[0],
			'profession' => $review[1],
			'image'      => aw_media_url( '2024/08/' . $review[2] ),
			'video'      => 'https://www.youtube.com/embed/' . $review[3],
			'text'       => $review[4],
		),
		$reviews
	);
}

/**
 * Organization schema with the reviews, printed once per page.
 */
function aw_reviews_schema(): void {
	static $printed = false;

	if ( $printed ) {
		return;
	}

	$printed = true;
	$reviews = aw_reviews();

	$schema = array(
		'@context'        => 'https://schema.org',
		'@type'           => 'Organization',
		'name'            => 'AroundWire',
		'url'             => home_url( '/' ),
		'logo'            => aw_media_url( '2024/07/logo_black.svg' ),
		'sameAs'          => array( 'https://www.facebook.com/aroundwire', 'https://www.instagram.com/aroundwire' ),
		'aggregateRating' => array(
			'@type'       => 'AggregateRating',
			'ratingValue' => '5',
			'reviewCount' => (string) count( $reviews ),
		),
		'review'          => array_map(
			static fn( array $review ): array => array(
				'@type'         => 'Review',
				'author'        => array(
					'@type' => 'Person',
					'name'  => $review['name'],
				),
				'reviewRating'  => array(
					'@type'       => 'Rating',
					'ratingValue' => '5',
					'bestRating'  => '5',
					'worstRating' => '1',
				),
				'reviewBody'    => $review['text'],
				'datePublished' => '2024-09-20',
			),
			$reviews
		),
	);

	wp_print_inline_script_tag( (string) wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ), array( 'type' => 'application/ld+json' ) );
}
