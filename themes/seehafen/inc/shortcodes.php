<?php
/**
 * Seehafen shortcodes — render the SPA's exact section markup from WP data.
 * Design comes from the verbatim SPA CSS; JS comes from assets/js/main.js.
 *
 * @package Seehafen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline lucide-style icon.
 *
 * @param string $name Icon name.
 */
function seehafen_icon( $name ) {
	$paths = array(
		'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-left'   => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',
		'arrow-down'   => '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
		'calendar'     => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>',
		'check'        => '<path d="M20 6 9 17l-5-5"/>',
		'map-pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'ruler'        => '<path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/>',
		'building'     => '<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>',
		'external'     => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
		'phone'        => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'mail'         => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'menu'         => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
		'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-' . esc_attr( $name ) . '" aria-hidden="true">' . $paths[ $name ] . '</svg>';
}

/**
 * Get image URL for a post (ACF field or featured).
 *
 * @param int    $post_id Post ID.
 * @param string $field   ACF field name (optional).
 */
function seehafen_img_url( $post_id, $field = '' ) {
	if ( $field && function_exists( 'get_field' ) ) {
		$img = get_field( $field, $post_id );
		if ( is_array( $img ) && ! empty( $img['url'] ) ) {
			return $img['url'];
		}
		if ( is_numeric( $img ) && $img ) {
			return wp_get_attachment_url( $img );
		}
	}
	$tid = get_post_thumbnail_id( $post_id );
	return $tid ? wp_get_attachment_url( $tid ) : '';
}

/**
 * Render the CTA strip.
 */
function seehafen_cta_shortcode() {
	return '<section class="contact-strip">
		<div class="content">
			<div>
				<span class="kicker">Kostenloses Erstgespräch</span>
				<h2>Lassen Sie uns über Ihre Immobilie sprechen.</h2>
				<p>Montag bis Freitag · 08:00–12:00 und 13:30–17:00 Uhr</p>
			</div>
			<a class="button button-light" href="' . esc_url( home_url( '/kontakt' ) ) . '">Kontakt aufnehmen ' . seehafen_icon( 'arrow-right' ) . '</a>
		</div>
	</section>';
}
add_shortcode( 'seehafen_cta', 'seehafen_cta_shortcode' );

/**
 * Render the home hero.
 */
function seehafen_home_hero_shortcode() {
	$hero = '';
	$attachments = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => 1, 'meta_key' => '_wp_attached_file', 'meta_value' => 'hero-team-house.png', 'meta_compare' => 'LIKE', 'fields' => 'ids' ) );
	if ( $attachments ) {
		$hero = wp_get_attachment_url( $attachments[0] );
	}
	$fallback = file_exists( get_stylesheet_directory() . '/assets/hero-team-house.png' ) ? get_stylesheet_directory_uri() . '/assets/hero-team-house.png' : '';
	return '<section class="hero">
		<img src="' . esc_url( $hero ? $hero : $fallback ) . '" alt="Das Seehafen-Team im Gespräch vor einer modernen Immobilie" />
		<div class="hero-overlay"></div>
		<div class="content hero-content">
			<p class="hero-eyebrow">Langfristig. Persönlich. Verlässlich.</p>
			<h1>Immobilien<br />mit Weitblick.</h1>
			<p class="hero-lead">Persönliche Beratung, verantwortungsvolle Entscheidungen und engagierte Begleitung.</p>
			<a class="button hero-cta" href="' . esc_url( home_url( '/kontakt' ) ) . '">' . seehafen_icon( 'calendar' ) . ' Beratung vereinbaren</a>
		</div>
	</section>';
}
add_shortcode( 'seehafen_home_hero', 'seehafen_home_hero_shortcode' );

/**
 * Render the home intro + service cards.
 */
function seehafen_home_intro_shortcode() {
	$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$cards    = '';
	foreach ( $services as $s ) {
		$img   = seehafen_img_url( $s->ID, 'seehafen_home_image' );
		$text  = get_post_meta( $s->ID, 'seehafen_home_text', true );
		$cards .= '<article class="home-service-card">
			<img src="' . esc_url( $img ) . '" alt="" loading="lazy" />
			<div>
				<h3>' . esc_html( get_the_title( $s->ID ) ) . '</h3>
				<p>' . esc_html( $text ) . '</p>
				<a href="' . esc_url( get_permalink( $s->ID ) ) . '">Mehr erfahren ' . seehafen_icon( 'arrow-right' ) . '</a>
			</div>
		</article>';
	}
	return '<section id="expertise" class="home-intro">
		<div class="content">
			<div class="home-heading">
				<div>
					<span class="kicker">Unsere Expertise</span>
					<h2>Persönlich begleitet.<br />Klar entschieden.</h2>
				</div>
				<p>Der Verkauf oder die Bewirtschaftung einer Liegenschaft ist mehr als eine Transaktion. Wir führen Sie sicher durch den gesamten Prozess – professionell, transparent und mit Herzblut.</p>
			</div>
			<div class="home-services">' . $cards . '</div>
		</div>
	</section>';
}
add_shortcode( 'seehafen_home_intro', 'seehafen_home_intro_shortcode' );

/**
 * Render the offers showcase (single-stage + arrows).
 */
function seehafen_offers_showcase_shortcode() {
	$offers = get_posts( array( 'post_type' => 'offer', 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$stages = '';
	$i      = 0;
	foreach ( $offers as $o ) {
		$price = get_field( 'seehafen_price', $o->ID );
		$loc   = get_field( 'seehafen_location', $o->ID );
		$rooms = get_post_meta( $o->ID, 'seehafen_rooms', true );
		$area  = get_post_meta( $o->ID, 'seehafen_area', true );
		$label = get_post_meta( $o->ID, 'seehafen_label', true );
		$img   = seehafen_img_url( $o->ID );
		$stages .= '<div class="offer-showcase-stage' . ( 0 === $i ? ' is-active' : '' ) . '">
			<div class="offer-showcase-image">
				<img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_the_title( $o->ID ) ) . '" />
				<span>' . sprintf( '%02d', $i + 1 ) . ' / ' . sprintf( '%02d', count( $offers ) ) . '</span>
			</div>
			<article class="offer-showcase-info">
				<span class="reference-type">' . esc_html( $label ) . '</span>
				<h3>' . esc_html( get_the_title( $o->ID ) ) . '</h3>
				<p class="offer-showcase-price"><span>CHF</span> ' . esc_html( $price ) . '<small> / Monat</small></p>
				<div class="offer-showcase-facts">
					<span>' . seehafen_icon( 'map-pin' ) . ' ' . esc_html( $loc ) . '</span>
					<span>' . seehafen_icon( 'building' ) . ' ' . esc_html( $rooms ) . '</span>
					' . ( $area ? '<span>' . seehafen_icon( 'ruler' ) . ' ' . esc_html( $area ) . '</span>' : '' ) . '
				</div>
				<a class="offer-showcase-detail" href="https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag" target="_blank" rel="noreferrer">Auf Homegate ansehen ' . seehafen_icon( 'external' ) . '</a>
			</article>
		</div>';
		$i++;
	}
	return '<section class="home-offers" id="angebote"><div class="content"><div class="offer-showcase">
		<div class="offer-showcase-heading">
			<div>
				<span class="kicker">Immobilien</span>
				<h2>Unsere aktuellen Angebote.</h2>
			</div>
			<a class="text-link" href="https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag" target="_blank" rel="noreferrer">Alle Angebote ' . seehafen_icon( 'external' ) . '</a>
		</div>
		' . $stages . '
		<div class="offer-showcase-footer">
			<div class="offer-showcase-controls" aria-label="Angebote wechseln">
				<button type="button" aria-label="Vorheriges Angebot">' . seehafen_icon( 'arrow-left' ) . '</button>
				<button type="button" aria-label="Nächstes Angebot">' . seehafen_icon( 'arrow-right' ) . '</button>
			</div>
		</div>
	</div></div></section>';
}
add_shortcode( 'seehafen_offers_showcase', 'seehafen_offers_showcase_shortcode' );

/**
 * Render reference tiles.
 *
 * @param array $atts Shortcode atts.
 */
function seehafen_references_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'     => 9,
			'show_more' => 'false',
			'preview'   => 'false',
		),
		$atts,
		'seehafen_references'
	);
	$refs = get_posts( array( 'post_type' => 'reference', 'posts_per_page' => (int) $atts['limit'], 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$all  = get_posts( array( 'post_type' => 'reference', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC', 'fields' => 'ids' ) );
	$tiles = '';
	foreach ( $refs as $r ) {
		$loc   = get_field( 'seehafen_location', $r->ID );
		$detail = get_field( 'seehafen_detail', $r->ID );
		$type  = get_post_meta( $r->ID, 'seehafen_type', true );
		$img   = seehafen_img_url( $r->ID );
		$tiles .= '<article class="reference-tile">
			<img src="' . esc_url( $img ) . '" alt="' . esc_attr( get_the_title( $r->ID ) ) . '" loading="lazy" />
			<div class="reference-tile-body">
				<span class="reference-type">' . esc_html( $type ) . '</span>
				<h3>' . esc_html( get_the_title( $r->ID ) ) . '</h3>
				<div class="reference-tile-meta">
					' . ( $loc ? '<span>' . seehafen_icon( 'map-pin' ) . ' ' . esc_html( $loc ) . '</span>' : '' ) . '
					' . ( $detail ? '<span>' . seehafen_icon( 'ruler' ) . ' ' . esc_html( $detail ) . '</span>' : '' ) . '
				</div>
			</div>
		</article>';
	}

	if ( 'true' === $atts['preview'] ) {
		return '<section class="home-references"><div class="content">
			<div class="section-heading">
				<div><span class="kicker">Referenzen</span><h2>Kürzlich verkaufte Objekte.</h2></div>
				<a class="text-link" href="' . esc_url( home_url( '/referenzen' ) ) . '">Alle Referenzen ' . seehafen_icon( 'arrow-right' ) . '</a>
			</div>
			<div class="reference-preview-grid">' . $tiles . '</div>
		</div></section>';
	}

	$more = ( 'true' === $atts['show_more'] && count( $all ) > (int) $atts['limit'] ) ? '<div class="reference-show-more"><button class="button button-solid" type="button" aria-controls="reference-grid" aria-expanded="false">Mehr anzeigen ' . seehafen_icon( 'arrow-down' ) . '</button></div>' : '';
	return '<section class="reference-archive"><div class="content"><div class="reference-archive-grid" id="reference-grid">' . $tiles . '</div>' . $more . '</div></section>';
}
add_shortcode( 'seehafen_references', 'seehafen_references_shortcode' );

/**
 * Render the primary service cards (Dienstleistungen page).
 */
function seehafen_primary_services_shortcode() {
	$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$cards    = '';
	foreach ( $services as $s ) {
		$img = seehafen_img_url( $s->ID );
		$cards .= '<article class="primary-service-card">
			<img src="' . esc_url( $img ) . '" alt="" loading="lazy" />
			<div>
				<h2>' . esc_html( get_the_title( $s->ID ) ) . '</h2>
				<a href="' . esc_url( get_permalink( $s->ID ) ) . '">Mehr erfahren ' . seehafen_icon( 'arrow-right' ) . '</a>
			</div>
		</article>';
	}
	return '<section class="services-page"><div class="content primary-service-grid">' . $cards . '</div></section>';
}
add_shortcode( 'seehafen_primary_services', 'seehafen_primary_services_shortcode' );

/**
 * Render the secondary (additional) services.
 */
function seehafen_secondary_services_shortcode() {
	$additional = get_option( 'seehafen_additional_services', array() );
	if ( ! $additional ) {
		return '';
	}
	$items = '';
	foreach ( $additional as $svc ) {
		$img = '';
		if ( ! empty( $svc['image'] ) ) {
			$attachments = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => 1, 'meta_key' => '_wp_attached_file', 'meta_value' => 'assets/' . basename( $svc['image'] ), 'meta_compare' => 'LIKE', 'fields' => 'ids' ) );
			if ( $attachments ) {
				$img = wp_get_attachment_url( $attachments[0] );
			}
		}
		$points = '';
		foreach ( (array) $svc['points'] as $point ) {
			$points .= '<li>' . seehafen_icon( 'check' ) . ' ' . esc_html( $point ) . '</li>';
		}
		$items .= '<article id="' . esc_attr( $svc['id'] ) . '">
			<img src="' . esc_url( $img ) . '" alt="" loading="lazy" />
			<div>
				<h3>' . esc_html( $svc['title'] ) . '</h3>
				<p>' . esc_html( $svc['text'] ) . '</p>
				<ul>' . $points . '</ul>
				<a href="' . esc_url( home_url( '/kontakt' ) ) . '">Beratung anfragen ' . seehafen_icon( 'arrow-right' ) . '</a>
			</div>
		</article>';
	}
	return '<section class="secondary-services"><div class="content"><div class="section-heading split-heading">
		<div><span class="kicker">Weitere Fachbereiche</span><h2>Ergänzend für Sie da.</h2></div>
		<p>Bei komplexeren Vorhaben koordinieren wir auch die angrenzenden Themen – übersichtlich und aus einer Hand.</p>
		</div><div class="secondary-service-grid">' . $items . '</div></div></section>';
}
add_shortcode( 'seehafen_secondary_services', 'seehafen_secondary_services_shortcode' );

/**
 * Render the process section.
 */
function seehafen_process_shortcode( $atts ) {
	$atts     = shortcode_atts( array( 'compact' => 'false' ), $atts, 'seehafen_process' );
	$process  = get_option( 'seehafen_process', array() );
	$items    = '';
	foreach ( $process as $p ) {
		$items .= '<article><span>' . esc_html( $p[0] ) . '</span><h3>' . esc_html( $p[1] ) . '</h3><p>' . esc_html( $p[2] ) . '</p></article>';
	}
	return '<section class="process-section' . ( 'true' === $atts['compact'] ? ' compact' : '' ) . '">
		<div class="content">
			<div class="section-heading split-heading">
				<div><span class="kicker">Unser Prozess</span><h2>So arbeiten wir.</h2></div>
				<p>Strukturiert, transparent und immer im Interesse unserer Kundinnen und Kunden.</p>
			</div>
			<div class="process-grid">' . $items . '</div>
		</div>
	</section>';
}
add_shortcode( 'seehafen_process', 'seehafen_process_shortcode' );

/**
 * Render the team grid.
 */
function seehafen_team_shortcode() {
	$members = get_posts( array( 'post_type' => 'team_member', 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$items   = '';
	foreach ( $members as $m ) {
		$initials = get_post_meta( $m->ID, 'seehafen_initials', true );
		$role     = get_field( 'seehafen_role', $m->ID );
		$items   .= '<article><span class="team-avatar">' . esc_html( $initials ) . '</span><h2>' . esc_html( get_the_title( $m->ID ) ) . '</h2><strong>' . esc_html( $role ) . '</strong><p>' . esc_html( wp_strip_all_tags( $m->post_content ) ) . '</p></article>';
	}
	return '<div class="team-grid">' . $items . '</div>';
}
add_shortcode( 'seehafen_team', 'seehafen_team_shortcode' );

/**
 * Render values + process lists (Firma page).
 */
function seehafen_values_shortcode() {
	$values   = get_option( 'seehafen_values', array() );
	$process  = get_option( 'seehafen_process', array() );
	$val_list = '';
	$i        = 1;
	foreach ( $values as $v ) {
		$val_list .= '<article><span>' . sprintf( '%02d', $i ) . '</span><div><h4>' . esc_html( $v[0] ) . '</h4><p>' . esc_html( $v[1] ) . '</p></div></article>';
		$i++;
	}
	$proc_list = '';
	foreach ( $process as $p ) {
		$proc_list .= '<article><span>' . esc_html( $p[0] ) . '</span><div><h4>' . esc_html( $p[1] ) . '</h4><p>' . esc_html( $p[2] ) . '</p></div></article>';
	}
	return '<div class="company-values-layout">
		<div class="company-values-column"><h3>Unsere Werte</h3><div class="company-detail-list">' . $val_list . '</div></div>
		<div class="company-values-column"><h3>So arbeiten wir</h3><div class="company-detail-list">' . $proc_list . '</div></div>
	</div>';
}
add_shortcode( 'seehafen_values', 'seehafen_values_shortcode' );

/**
 * Render the company about section (Firma page).
 */
function seehafen_company_about_shortcode() {
	return '<section class="company-about" id="uber-uns">
		<div class="content company-about-grid">
			<div class="company-about-copy">
				<span class="kicker">Über uns</span>
				<h1>Drei Persönlichkeiten.<br />Eine Leidenschaft.</h1>
				<p class="company-about-lead">Wir betreuen Immobilien mit Engagement, Fachwissen und Weitblick – persönlich, effizient und immer im Interesse unserer Kundschaft.</p>
			</div>
			<div class="company-about-aside">
				<div class="company-about-text">
					<p>Als unabhängiges Immobilienunternehmen hören wir zu, denken voraus und schaffen klare Lösungen.</p>
					<p>Unser Anspruch ist, Immobilien nicht nur zu verwalten oder zu vermitteln, sondern Werte nachhaltig zu sichern und weiterzuentwickeln.</p>
				</div>
				<nav class="company-about-nav" aria-label="Firma entdecken">
					<a href="#team"><span>01</span><strong>Unser Team</strong>' . seehafen_icon( 'arrow-right' ) . '</a>
					<a href="#werte"><span>02</span><strong>Werte &amp; Arbeitsweise</strong>' . seehafen_icon( 'arrow-right' ) . '</a>
				</nav>
			</div>
		</div>
	</section>';
}
add_shortcode( 'seehafen_company_about', 'seehafen_company_about_shortcode' );

/**
 * Render a page hero (label/title/text/image).
 *
 * @param array $atts Shortcode atts.
 */
function seehafen_page_hero_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'label' => '', 'title' => '', 'text' => '', 'image' => '' ), $atts, 'seehafen_page_hero' );
	$hero_image = '';
	if ( $atts['image'] ) {
		$hero_image = '<div class="page-hero-media"><img src="' . esc_url( $atts['image'] ) . '" alt="" /></div>';
	}
	return '<section class="page-hero">
		<div class="content page-hero-grid' . ( $hero_image ? '' : ' page-hero-grid-text-only' ) . '">
			<div class="page-hero-copy">
				<span class="kicker">' . esc_html( $atts['label'] ) . '</span>
				<h1>' . esc_html( $atts['title'] ) . '</h1>
				<p>' . esc_html( $atts['text'] ) . '</p>
			</div>
			' . $hero_image . '
		</div>
	</section>';
}
add_shortcode( 'seehafen_page_hero', 'seehafen_page_hero_shortcode' );

/**
 * Render the overview links (Angebote page).
 */
function seehafen_overview_links_shortcode() {
	$img_prop3 = '';
	$img_prop2 = '';
	$prop3 = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => 1, 'meta_key' => '_wp_attached_file', 'meta_value' => 'assets/property-3.jpg', 'meta_compare' => 'LIKE', 'fields' => 'ids' ) );
	$prop2 = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => 1, 'meta_key' => '_wp_attached_file', 'meta_value' => 'assets/property-2.jpg', 'meta_compare' => 'LIKE', 'fields' => 'ids' ) );
	if ( $prop3 ) { $img_prop3 = wp_get_attachment_url( $prop3[0] ); }
	if ( $prop2 ) { $img_prop2 = wp_get_attachment_url( $prop2[0] ); }
	return '<section class="overview-links">
		<div class="content">
			<div class="overview-links-heading">
				<div><span class="kicker">Angebote</span><h1>Immobilien im Überblick.</h1></div>
				<p>Entdecken Sie aktuelle Kauf- und Mietangebote oder werfen Sie einen Blick auf erfolgreich begleitete Projekte.</p>
			</div>
			<div class="overview-link-grid">
				<a href="https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag" class="overview-link-card" target="_blank" rel="noreferrer">
					<img src="' . esc_url( $img_prop3 ) . '" alt="" loading="lazy" />
					<div><h2>Aktuelle Angebote</h2><p>Verfügbare Kauf- und Mietobjekte auf unserem offiziellen Anbieterprofil.</p><span>Auf Homegate ' . seehafen_icon( 'external' ) . '</span></div>
				</a>
				<a href="' . esc_url( home_url( '/referenzen' ) ) . '" class="overview-link-card">
					<img src="' . esc_url( $img_prop2 ) . '" alt="" loading="lazy" />
					<div><h2>Referenzen</h2><p>Eine Auswahl verkaufter, vermieteter und verwalteter Immobilien.</p><span>Entdecken ' . seehafen_icon( 'arrow-right' ) . '</span></div>
				</a>
			</div>
		</div>
	</section>';
}
add_shortcode( 'seehafen_overview_links', 'seehafen_overview_links_shortcode' );

/**
 * Render the contact sidebar (direct contact + locations).
 */
function seehafen_contact_sidebar_shortcode() {
	return '<div class="contact-direct-panel">
		<span class="kicker">Direkt erreichbar</span>
		<h2>Persönlich für Sie da.</h2>
		<div class="contact-methods">
			<a href="tel:+41444514302"><span>' . seehafen_icon( 'phone' ) . '</span><span><small>Telefon</small>+41 44 451 43 02</span></a>
			<a href="tel:+41797857880"><span>' . seehafen_icon( 'phone' ) . '</span><span><small>Mobil</small>+41 79 785 78 80</span></a>
			<a href="mailto:info@seehafen-immobilien.ch"><span>' . seehafen_icon( 'mail' ) . '</span><span><small>E-Mail</small>info@seehafen-immobilien.ch</span></a>
		</div>
		<p><strong>Öffnungszeiten</strong><br />Montag bis Freitag<br />08:00–12:00 · 13:30–17:00 Uhr</p>
	</div>
	<div class="contact-locations">
		<article><span class="kicker">Hauptsitz</span><h3>Schwyz</h3><p>Bahnhofstrasse 4<br />6430 Schwyz</p></article>
		<article><span class="kicker">Filiale</span><h3>Wohlen</h3><p>Cheiblerrain 13<br />5610 Wohlen</p></article>
	</div>';
}
add_shortcode( 'seehafen_contact_sidebar', 'seehafen_contact_sidebar_shortcode' );

/**
 * Render the contact intro.
 */
function seehafen_contact_intro_shortcode() {
	return '<section class="contact-intro"><div class="content contact-intro-copy">
		<span class="kicker">Kontakt</span>
		<h1>Wie können wir Ihnen helfen?</h1>
		<p>Rufen Sie uns an, schreiben Sie uns eine E-Mail oder senden Sie Ihre Anfrage über das Formular. Wir melden uns persönlich bei Ihnen zurück.</p>
	</div></section>';
}
add_shortcode( 'seehafen_contact_intro', 'seehafen_contact_intro_shortcode' );

/**
 * Render the contact form (CF7, SPA styling).
 */
function seehafen_contact_form_shortcode() {
	$form = do_shortcode( '[contact-form-7 title="Kontaktformular"]' );
	return '<div class="contact-form">' . $form . '</div>';
}
add_shortcode( 'seehafen_contact_form', 'seehafen_contact_form_shortcode' );

/**
 * Render the references title section.
 */
function seehafen_references_title_shortcode() {
	return '<section class="references-title"><div class="content"><h1>Referenzen</h1></div></section>';
}
add_shortcode( 'seehafen_references_title', 'seehafen_references_title_shortcode' );
