<?php
/**
 * Template tags — reusable markup helpers matching the original SPA 1:1.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output a lucide-style icon inline (stroke-based, aria-hidden).
 *
 * @param string $name Icon name.
 *
 * @return void
 */
function seehafen_icon( $name ) {
	$paths = array(
		'arrow-down'   => '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
		'arrow-left'   => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
		'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'building'     => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
		'calendar'     => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>',
		'check'        => '<path d="M20 6 9 17l-5-5"/>',
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'external'     => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
		'mail'         => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'map-pin'      => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
		'menu'         => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
		'phone'        => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'ruler'        => '<path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/>',
		'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return;
	}

	printf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG markup.
	);
}

/**
 * Output the logo link.
 *
 * @return void
 */
function seehafen_logo() {
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php esc_attr_e( 'Seehafen & Partner – Startseite', 'seehafen' ); ?>">
		<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Seehafen & Partner Immobilien AG', 'seehafen' ); ?>" />
	</a>
	<?php
}

/**
 * Render the page hero section.
 *
 * @param string      $label Kicker label.
 * @param string      $title Heading.
 * @param string      $text  Lead text.
 * @param string|bool $image Hero image URL or false to hide.
 *
 * @return void
 */
function seehafen_page_hero( $label, $title, $text, $image = false ) {
	$hero_image = ( false === $image ) ? null : ( $image ? $image : get_stylesheet_directory_uri() . '/assets/img/property-hero.jpg' );
	$grid_class = $hero_image ? ' page-hero-grid' : ' page-hero-grid page-hero-grid-text-only';
	?>
	<section class="page-hero">
		<div class="content<?php echo esc_attr( $grid_class ); ?>">
			<div class="page-hero-copy">
				<span class="kicker"><?php echo esc_html( $label ); ?></span>
				<h1><?php echo esc_html( $title ); ?></h1>
				<p><?php echo esc_html( $text ); ?></p>
			</div>
			<?php if ( $hero_image ) : ?>
				<div class="page-hero-media">
					<img src="<?php echo esc_url( $hero_image ); ?>" alt="" />
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Render the CTA strip.
 *
 * @return void
 */
function seehafen_cta_section() {
	$kicker  = get_theme_mod( 'seehafen_cta_kicker', 'Kostenloses Erstgespräch' );
	$heading = get_theme_mod( 'seehafen_cta_heading', 'Lassen Sie uns über Ihre Immobilie sprechen.' );
	$text    = get_theme_mod( 'seehafen_cta_text', 'Montag bis Freitag · 08:00–12:00 und 13:30–17:00 Uhr' );
	$button  = get_theme_mod( 'seehafen_cta_button', 'Kontakt aufnehmen' );
	?>
	<section class="contact-strip">
		<div class="content">
			<div>
				<span class="kicker"><?php echo esc_html( $kicker ); ?></span>
				<h2><?php echo esc_html( $heading ); ?></h2>
				<p><?php echo esc_html( $text ); ?></p>
			</div>
			<a class="button button-light" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>">
				<?php echo esc_html( $button ); ?>
				<?php seehafen_icon( 'arrow-right' ); ?>
			</a>
		</div>
	</section>
	<?php
}

/**
 * Get the process steps as an array of [number, title, text].
 *
 * @return array
 */
function seehafen_get_process() {
	$defaults = array(
		array( '01', 'Erstgespräch', 'Wir besprechen Ihre Ziele, Anforderungen und Erwartungen in einem persönlichen Gespräch.' ),
		array( '02', 'Analyse', 'Wir analysieren Ihre Immobilie oder Ihren Bedarf und entwickeln eine massgeschneiderte Strategie.' ),
		array( '03', 'Umsetzung', 'Wir setzen die vereinbarten Massnahmen professionell, transparent und zuverlässig um.' ),
		array( '04', 'Partnerschaft', 'Wir begleiten Sie langfristig und stehen Ihnen als vertrauensvoller Partner zur Seite.' ),
	);

	$raw = get_theme_mod( 'seehafen_process_steps', '' );

	if ( empty( $raw ) ) {
		return $defaults;
	}

	$steps = array();

	foreach ( explode( "\n", $raw ) as $index => $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '||', $line ) );

		if ( count( $parts ) < 2 ) {
			continue;
		}

		$steps[] = array(
			sprintf( '%02d', $index + 1 ),
			$parts[0],
			isset( $parts[1] ) ? $parts[1] : '',
		);
	}

	return $steps;
}

/**
 * Render the process section.
 *
 * @param bool $compact Compact variant (used on services page).
 *
 * @return void
 */
function seehafen_process_section( $compact = false ) {
	$steps  = seehafen_get_process();
	$class  = $compact ? ' process-section compact' : ' process-section';
	$kicker = $compact ? 'Unser Prozess' : 'Unser Prozess';
	?>
	<section class="<?php echo esc_attr( $class ); ?>">
		<div class="content">
			<div class="section-heading split-heading">
				<div>
					<span class="kicker"><?php echo esc_html( $kicker ); ?></span>
					<h2><?php esc_html_e( 'So arbeiten wir.', 'seehafen' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Strukturiert, transparent und immer im Interesse unserer Kundinnen und Kunden.', 'seehafen' ); ?></p>
			</div>
			<div class="process-grid">
				<?php foreach ( $steps as $step ) : ?>
					<article>
						<span><?php echo esc_html( $step[0] ); ?></span>
						<h3><?php echo esc_html( $step[1] ); ?></h3>
						<p><?php echo esc_html( $step[2] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Get the company values as an array of [title, text].
 *
 * @return array
 */
function seehafen_get_values() {
	$defaults = array(
		array( 'Verlässlichkeit', 'Wir halten, was wir versprechen, und kommunizieren transparent.' ),
		array( 'Persönliche Betreuung', 'Sie haben einen festen Ansprechpartner, der Ihre Ziele kennt.' ),
		array( 'Fachkompetenz', 'Erfahrung und fundierte Marktkenntnis bilden die Basis unserer Arbeit.' ),
		array( 'Nachhaltigkeit', 'Der langfristige Werterhalt Ihrer Immobilie steht im Mittelpunkt.' ),
	);

	$raw = get_theme_mod( 'seehafen_values', '' );

	if ( empty( $raw ) ) {
		return $defaults;
	}

	$values = array();

	foreach ( explode( "\n", $raw ) as $line ) {
		$line = trim( $line );

		if ( '' === $line ) {
			continue;
		}

		$parts = array_map( 'trim', explode( '||', $line ) );

		if ( count( $parts ) < 2 ) {
			continue;
		}

		$values[] = array( $parts[0], $parts[1] );
	}

	return $values;
}

/**
 * Get services, optionally filtered by service_type term.
 *
 * @param string $type Term slug or empty for all.
 *
 * @return WP_Query
 */
function seehafen_get_services( $type = '' ) {
	$args = array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	if ( '' !== $type ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'service_type',
				'field'    => 'slug',
				'terms'    => $type,
			),
		);
	}

	return new WP_Query( $args );
}

/**
 * Get references, optionally filtered by reference_type term.
 *
 * @param string $type Term slug or empty for all.
 * @param int    $limit Number of posts (-1 for all).
 *
 * @return WP_Query
 */
function seehafen_get_references( $type = '', $limit = -1 ) {
	$args = array(
		'post_type'      => 'reference',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	if ( '' !== $type ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'reference_type',
				'field'    => 'slug',
				'terms'    => $type,
			),
		);
	}

	return new WP_Query( $args );
}

/**
 * Get the offer showcase items.
 *
 * @return WP_Query
 */
function seehafen_get_offers() {
	$args = array(
		'post_type'      => 'offer',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	return new WP_Query( $args );
}

/**
 * Get team members.
 *
 * @return WP_Query
 */
function seehafen_get_team() {
	$args = array(
		'post_type'      => 'team_member',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	return new WP_Query( $args );
}

/**
 * Render one reference tile from a reference post.
 *
 * @param WP_Post $post Reference post.
 *
 * @return void
 */
function seehafen_reference_tile( $post ) {
	$title    = get_the_title( $post );
	$location = get_post_meta( $post->ID, '_seehafen_location', true );
	$detail   = get_post_meta( $post->ID, '_seehafen_detail', true );
	$type     = '';

	$terms = get_the_terms( $post, 'reference_type' );

	if ( $terms && ! is_wp_error( $terms ) ) {
		$type = $terms[0]->name;
	}

	$image = get_the_post_thumbnail_url( $post, 'full' );

	if ( ! $image ) {
		$image = get_stylesheet_directory_uri() . '/assets/img/property-1.jpg';
	}
	?>
	<article class="reference-tile">
		<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
		<div class="reference-tile-body">
			<span class="reference-type"><?php echo esc_html( $type ); ?></span>
			<h3><?php echo esc_html( $title ); ?></h3>
			<div class="reference-tile-meta">
				<?php if ( $location ) : ?>
					<span><?php seehafen_icon( 'map-pin' ); ?> <?php echo esc_html( $location ); ?></span>
				<?php endif; ?>
				<?php if ( $detail ) : ?>
					<span><?php seehafen_icon( 'ruler' ); ?> <?php echo esc_html( $detail ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
}

/**
 * Render the offer showcase (carousel).
 *
 * @return void
 */
function seehafen_offer_showcase() {
	$offers   = seehafen_get_offers();
	$external = seehafen_homegate_url();
	$items    = array();

	while ( $offers->have_posts() ) {
		$offers->the_post();

		$image = get_the_post_thumbnail_url( get_the_ID(), 'full' );

		if ( ! $image ) {
			$image = get_stylesheet_directory_uri() . '/assets/img/property-1.jpg';
		}

		$items[] = array(
			'label'    => get_post_meta( get_the_ID(), '_seehafen_label', true ),
			'title'    => get_the_title(),
			'price'    => get_post_meta( get_the_ID(), '_seehafen_price', true ),
			'location' => get_post_meta( get_the_ID(), '_seehafen_location', true ),
			'rooms'    => get_post_meta( get_the_ID(), '_seehafen_rooms', true ),
			'area'     => get_post_meta( get_the_ID(), '_seehafen_area', true ),
			'image'    => $image,
		);
	}

	wp_reset_postdata();

	$total = count( $items );

	if ( 0 === $total ) {
		return;
	}

	$first = $items[0];
	?>
	<div class="offer-showcase" data-offer-showcase data-total="<?php echo esc_attr( $total ); ?>">
		<div class="offer-showcase-heading">
			<div>
				<span class="kicker">Immobilien</span>
				<h2><?php esc_html_e( 'Unsere aktuellen Angebote.', 'seehafen' ); ?></h2>
			</div>
			<a class="text-link" href="<?php echo esc_url( $external ); ?>" target="_blank" rel="noreferrer">
				<?php esc_html_e( 'Alle Angebote', 'seehafen' ); ?>
				<?php seehafen_icon( 'external' ); ?>
			</a>
		</div>

		<div class="offer-showcase-stage">
			<div class="offer-showcase-image">
				<img src="<?php echo esc_url( $first['image'] ); ?>" alt="<?php echo esc_attr( $first['title'] ); ?>" data-offer-image />
				<span data-offer-counter>01 / <?php echo esc_html( sprintf( '%02d', $total ) ); ?></span>
			</div>
			<article class="offer-showcase-info">
				<span class="reference-type" data-offer-label><?php echo esc_html( $first['label'] ); ?></span>
				<h3 data-offer-title><?php echo esc_html( $first['title'] ); ?></h3>
				<p class="offer-showcase-price"><span>CHF</span> <span data-offer-price><?php echo esc_html( $first['price'] ); ?></span><small> / Monat</small></p>
				<div class="offer-showcase-facts">
					<span data-offer-location><?php seehafen_icon( 'map-pin' ); ?> <?php echo esc_html( $first['location'] ); ?></span>
					<span data-offer-rooms><?php seehafen_icon( 'building' ); ?> <?php echo esc_html( $first['rooms'] ); ?></span>
					<span data-offer-area><?php seehafen_icon( 'ruler' ); ?> <?php echo esc_html( $first['area'] ); ?></span>
				</div>
				<a class="offer-showcase-detail" href="<?php echo esc_url( $external ); ?>" target="_blank" rel="noreferrer">
					<?php esc_html_e( 'Auf Homegate ansehen', 'seehafen' ); ?>
					<?php seehafen_icon( 'external' ); ?>
				</a>
			</article>
		</div>

		<div class="offer-showcase-footer">
			<div class="offer-showcase-controls" aria-label="Angebote wechseln">
				<button type="button" data-offer-prev aria-label="Vorheriges Angebot"><?php seehafen_icon( 'arrow-left' ); ?></button>
				<button type="button" data-offer-next aria-label="Nächstes Angebot"><?php seehafen_icon( 'arrow-right' ); ?></button>
			</div>
		</div>

		<script type="application/json" data-offer-data><?php echo wp_json_encode( $items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON in script tag. ?></script>
	</div>
	<?php
}
