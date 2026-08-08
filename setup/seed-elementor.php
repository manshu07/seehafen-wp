<?php
/**
 * One-time: build Elementor page data for all Seehafen pages.
 * Run: wp eval-file /var/www/html/wp-content/seed-elementor.php --allow-root
 */

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$HOME = home_url( '/' );

// ---------- Helpers ----------

function sh_el_id() {
	return substr( md5( uniqid( '', true ) ), 0, 7 );
}

function sh_el_section( $settings, $columns, $is_inner = false ) {
	return array(
		'id'       => sh_el_id(),
		'elType'   => 'section',
		'settings' => $settings,
		'elements' => $columns,
		'isInner'  => $is_inner,
	);
}

function sh_el_column( $settings, $widgets, $is_inner = false ) {
	return array(
		'id'       => sh_el_id(),
		'elType'   => 'column',
		'settings' => $settings,
		'elements' => $widgets,
		'isInner'  => $is_inner,
	);
}

function sh_el_widget( $type, $settings ) {
	return array(
		'id'         => sh_el_id(),
		'elType'     => 'widget',
		'widgetType' => $type,
		'settings'   => $settings,
		'elements'   => array(),
	);
}

function sh_el_heading( $title, $size = 'h2', $align = 'left', $color = '#071f42' ) {
	return sh_el_widget(
		'heading',
		array(
			'title'        => $title,
			'header_size'  => $size,
			'align'        => $align,
			'title_color'  => $color,
			'typography_typography' => 'custom',
			'typography_font_size'  => array( 'unit' => 'px', 'size' => ( 'h1' === $size ? 48 : ( 'h3' === $size ? 22 : 32 ) ), 'sizes' => array() ),
			'typography_font_weight' => '700',
			'typography_line_height' => array( 'unit' => 'em', 'size' => 1.1, 'sizes' => array() ),
		)
	);
}

function sh_el_text( $html, $color = '#3c4451' ) {
	return sh_el_widget(
		'text-editor',
		array(
			'editor'       => $html,
			'text_color'   => $color,
			'typography_typography' => 'custom',
			'typography_font_size'  => array( 'unit' => 'px', 'size' => 16, 'sizes' => array() ),
			'typography_line_height' => array( 'unit' => 'em', 'size' => 1.6, 'sizes' => array() ),
		)
	);
}

function sh_el_button( $text, $url, $align = 'left', $solid = true ) {
	$bg = $solid ? '#c9a063' : 'transparent';
	$fg = $solid ? '#ffffff' : '#071f42';
	$border = $solid ? '' : '1px solid #071f42';
	return sh_el_widget(
		'button',
		array(
			'text'             => $text,
			'link'             => array( 'url' => $url, 'is_external' => '', 'nofollow' => '' ),
			'align'            => $align,
			'background_color' => $bg,
			'button_text_color' => $fg,
			'border_border'    => $solid ? '' : 'solid',
			'border_width'     => $solid ? array() : array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true ),
			'border_color'     => $solid ? '' : '#071f42',
			'typography_typography' => 'custom',
			'typography_font_size'  => array( 'unit' => 'px', 'size' => 14, 'sizes' => array() ),
			'typography_font_weight' => '700',
			'text_transform'   => 'uppercase',
			'letter_spacing'   => array( 'unit' => 'px', 'size' => 1.5, 'sizes' => array() ),
			'button_border_radius' => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
			'button_padding'   => array( 'unit' => 'px', 'top' => '14', 'right' => '30', 'bottom' => '14', 'left' => '30', 'isLinked' => false ),
		)
	);
}

function sh_el_image( $attachment_id, $align = 'center', $width = 100 ) {
	$url = wp_get_attachment_url( $attachment_id );
	return sh_el_widget(
		'image',
		array(
			'image'      => array( 'id' => $attachment_id, 'url' => $url ),
			'image_size' => 'full',
			'align'      => $align,
			'width'      => array( 'unit' => '%', 'size' => $width, 'sizes' => array() ),
		)
	);
}

function sh_el_icon_list( $items ) {
	$list = array();
	foreach ( $items as $text ) {
		$list[] = array(
			'text'          => $text,
			'selected_icon' => array( 'value' => 'fas fa-check', 'library' => 'fa-solid' ),
			'_id'           => sh_el_id(),
		);
	}
	return sh_el_widget(
		'icon-list',
		array(
			'icon_list'    => $list,
			'icon_color'   => '#c9a063',
			'text_color'   => '#071f42',
			'space_between' => array( 'unit' => 'px', 'size' => 12, 'sizes' => array() ),
		)
	);
}

function sh_el_icon_box( $title, $desc, $icon = 'fas fa-home', $img_id = 0 ) {
	$settings = array(
		'title_text'       => $title,
		'description_text' => $desc,
		'selected_icon'    => array( 'value' => $icon, 'library' => 'fa-solid' ),
		'title_size'       => 'h3',
		'title_color'      => '#071f42',
		'description_color' => '#3c4451',
		'icon_color'       => '#c9a063',
		'icon_size'        => array( 'unit' => 'px', 'size' => 36, 'sizes' => array() ),
		'position'         => 'top',
		'title_bottom_space' => array( 'unit' => 'px', 'size' => 10, 'sizes' => array() ),
	);
	return sh_el_widget( 'icon-box', $settings );
}

function sh_el_image_carousel( $images ) {
	$items = array();
	foreach ( $images as $id ) {
		$items[] = array(
			'image' => array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) ),
			'_id'   => sh_el_id(),
		);
	}
	return sh_el_widget(
		'image-carousel',
		array(
			'carousel'        => $items,
			'slides_to_show'  => '1',
			'slides_to_scroll' => '1',
			'navigation'      => 'yes',
			'autoplay'        => 'yes',
			'autoplay_speed'  => 4000,
			'pause_on_hover'  => 'yes',
			'image_size'      => 'full',
			'show_captions'   => 'yes',
		)
	);
}

function sh_el_posts( $post_type, $per_page, $columns = 3, $orderby = 'menu_order' ) {
	return sh_el_widget(
		'posts',
		array(
			'posts_post_type'  => $post_type,
			'posts_per_page'   => $per_page,
			'columns'          => $columns,
			'posts_orderby'    => $orderby,
			'posts_order'      => 'asc',
			'show_thumbnail'   => 'yes',
			'thumbnail_size'   => 'large',
			'show_title'       => 'yes',
			'title_tag'        => 'h3',
			'show_excerpt'     => 'yes',
			'excerpt_length'   => 40,
			'show_read_more'   => 'no',
			'classic_title_color' => '#071f42',
		)
	);
}

function sh_el_shortcode( $code ) {
	return sh_el_widget( 'shortcode', array( 'shortcode' => $code ) );
}

function sh_el_spacer( $size = 60 ) {
	return sh_el_widget( 'spacer', array( 'space' => array( 'unit' => 'px', 'size' => $size, 'sizes' => array() ) ) );
}

function sh_col( $width, $widgets ) {
	return sh_el_column( array( '_column_size' => $width, '_inline_size' => null ), $widgets );
}

function sh_full_col( $widgets ) {
	return sh_col( 100, $widgets );
}

function sh_sec_padded( $widgets, $bg = '', $css = '' ) {
	$settings = array(
		'layout'   => 'boxed',
		'gap'      => 'default',
		'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
		'padding'  => array( 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
	);
	if ( $bg ) {
		$settings['background_background'] = 'classic';
		$settings['background_color']      = $bg;
	}
	if ( $css ) {
		$settings['css_classes'] = $css;
	}
	return sh_el_section( $settings, array( sh_full_col( $widgets ) ) );
}

function sh_sec_split( $left, $right, $left_width = 50, $right_width = 50, $bg = '', $css = '' ) {
	$settings = array(
		'layout'   => 'boxed',
		'gap'      => 'extended',
		'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
		'padding'  => array( 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
	);
	if ( $bg ) {
		$settings['background_background'] = 'classic';
		$settings['background_color']      = $bg;
	}
	if ( $css ) {
		$settings['css_classes'] = $css;
	}
	return sh_el_section(
		$settings,
		array(
			sh_col( $left_width, $left ),
			sh_col( $right_width, $right ),
		)
	);
}

function sh_el_save( $post_id, $data, $template = 'elementor_header_footer' ) {
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $post_id, '_elementor_page_settings', array( 'template' => $template ) );
	update_post_meta( $post_id, '_wp_page_template', $template );
}

// ---------- Asset lookup ----------

function sh_attachment_by_path( $path ) {
	$filename = basename( $path );
	$posts = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'name'           => sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) ),
			'fields'         => 'ids',
		)
	);
	if ( $posts ) {
		return $posts[0];
	}
	// Fallback: search by meta path suffix.
	$by_meta = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_attached_file',
			'meta_value'     => $filename,
			'meta_compare'   => 'LIKE',
			'fields'         => 'ids',
		)
	);
	return $by_meta ? $by_meta[0] : 0;
}

function sh_page_id( $slug ) {
	$p = get_page_by_path( $slug );
	return $p ? $p->ID : 0;
}

// ---------- Page builders ----------

function sh_build_home( $HOME ) {
	$hero_img = sh_attachment_by_path( 'hero-team-house.png' );
	$ref_ids  = array();
	$refs     = get_posts( array( 'post_type' => 'reference', 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	foreach ( $refs as $r ) {
		$tid = get_post_thumbnail_id( $r->ID );
		if ( $tid ) {
			$ref_ids[] = $tid;
		}
	}
	$offer_ids = array();
	$offers    = get_posts( array( 'post_type' => 'offer', 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	foreach ( $offers as $o ) {
		$tid = get_post_thumbnail_id( $o->ID );
		if ( $tid ) {
			$offer_ids[] = $tid;
		}
	}

	$sections   = array();
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'background_background' => 'classic',
			'background_image'      => $hero_img ? array( 'id' => $hero_img, 'url' => wp_get_attachment_url( $hero_img ) ) : '',
			'background_position'   => 'center center',
			'background_size'       => 'cover',
			'background_overlay_background' => 'classic',
			'background_overlay_color'      => 'rgba(7,31,66,0.55)',
			'min_height'  => array( 'unit' => 'px', 'size' => 520, 'sizes' => array() ),
			'css_classes' => 'hero-section',
		),
		array(
			sh_col(
				60,
				array(
					sh_el_text( '<p class="hero-eyebrow">Langfristig. Persönlich. Verlässlich.</p>', '#ffffff' ),
					sh_el_heading( 'Immobilien<br />mit Weitblick.', 'h1', 'left', '#ffffff' ),
					sh_el_text( '<p class="hero-lead">Persönliche Beratung, verantwortungsvolle Entscheidungen und engagierte Begleitung.</p>', '#e8eaf0' ),
					sh_el_button( 'Beratung vereinbaren', home_url( '/kontakt' ), 'left', true ),
				)
			),
		)
	);

	// Intro / expertise.
	$svc_widgets = array();
	$services    = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$i           = 0;
	foreach ( $services as $s ) {
		$tid = get_post_thumbnail_id( $s->ID );
		if ( $tid ) {
			$svc_widgets[] = sh_el_image( $tid, 'center', 100 );
		}
		$svc_widgets[] = sh_el_heading( get_the_title( $s->ID ), 'h3', 'left' );
		$svc_widgets[] = sh_el_text( '<p>' . esc_html( $s->post_excerpt ) . '</p>' );
		$svc_widgets[] = sh_el_button( 'Mehr erfahren', get_permalink( $s->ID ), 'left', false );
		$svc_widgets[] = sh_el_spacer( 30 );
		$i++;
	}
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '90', 'right' => '20', 'bottom' => '40', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'home-intro-section',
		),
		array(
			sh_col(
				100,
				array(
					sh_el_text( '<span class="kicker">Unsere Expertise</span>' ),
					sh_el_heading( 'Persönlich begleitet.<br />Klar entschieden.', 'h2', 'left' ),
					sh_el_text( '<p>Der Verkauf oder die Bewirtschaftung einer Liegenschaft ist mehr als eine Transaktion. Wir führen Sie sicher durch den gesamten Prozess – professionell, transparent und mit Herzblut.</p>' ),
					sh_el_spacer( 40 ),
				)
			),
		)
	);
	$sections[] = sh_el_section(
		array(
			'layout' => 'boxed',
			'gap'    => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding' => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '40', 'left' => '20', 'isLinked' => false ),
		),
		array(
			sh_col( 25, array_slice( $svc_widgets, 0, 6 ) ),
			sh_col( 25, array_slice( $svc_widgets, 6, 6 ) ),
			sh_col( 25, array_slice( $svc_widgets, 12, 6 ) ),
			sh_col( 25, array_slice( $svc_widgets, 18, 6 ) ),
		)
	);

	// Offers.
	$sections[] = sh_sec_padded(
		array(
			sh_el_text( '<span class="kicker">Immobilien</span>' ),
			sh_el_heading( 'Unsere aktuellen Angebote.', 'h2', 'left' ),
			sh_el_spacer( 20 ),
			$offer_ids ? sh_el_image_carousel( $offer_ids ) : sh_el_text( '<p>Keine Angebote vorhanden.</p>' ),
		),
		'#f8f7f4',
		'home-offers-section'
	);

	// References preview.
	$sections[] = sh_sec_padded(
		array(
			sh_el_text( '<span class="kicker">Referenzen</span>' ),
			sh_el_heading( 'Kürzlich verkaufte Objekte.', 'h2', 'left' ),
			sh_el_spacer( 20 ),
			$ref_ids ? sh_el_image_carousel( $ref_ids ) : sh_el_text( '<p>Keine Referenzen vorhanden.</p>' ),
			sh_el_spacer( 10 ),
			sh_el_button( 'Alle Referenzen', home_url( '/referenzen' ), 'left', false ),
		),
		'',
		'home-references-section'
	);

	// CTA strip.
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'background_background' => 'classic',
			'background_color'      => '#071f42',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '60', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'contact-strip-section',
		),
		array(
			sh_col(
				70,
				array(
					sh_el_text( '<span class="kicker" style="color:#c9a063">Kostenloses Erstgespräch</span>' ),
					sh_el_heading( 'Lassen Sie uns über Ihre Immobilie sprechen.', 'h2', 'left', '#ffffff' ),
					sh_el_text( '<p style="color:#c9cfda">Montag bis Freitag · 08:00–12:00 und 13:30–17:00 Uhr</p>', '#c9cfda' ),
				)
			),
			sh_col( 30, array( sh_el_button( 'Kontakt aufnehmen', home_url( '/kontakt' ), 'right', true ) ) ),
		)
	);

	return $sections;
}

function sh_build_firma() {
	$sections = array();
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'company-about-section',
		),
		array(
			sh_col(
				60,
				array(
					sh_el_text( '<span class="kicker">Über uns</span>' ),
					sh_el_heading( 'Drei Persönlichkeiten.<br />Eine Leidenschaft.', 'h1', 'left' ),
					sh_el_text( '<p class="company-about-lead">Wir betreuen Immobilien mit Engagement, Fachwissen und Weitblick – persönlich, effizient und immer im Interesse unserer Kundschaft.</p>' ),
				)
			),
			sh_col(
				40,
				array(
					sh_el_text( '<p>Als unabhängiges Immobilienunternehmen hören wir zu, denken voraus und schaffen klare Lösungen.</p><p>Unser Anspruch ist, Immobilien nicht nur zu verwalten oder zu vermitteln, sondern Werte nachhaltig zu sichern und weiterzuentwickeln.</p>' ),
				)
			),
		)
	);

	// Team.
	$team_widgets = array();
	$members      = get_posts( array( 'post_type' => 'team_member', 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	foreach ( $members as $m ) {
		$role = get_field( 'seehafen_role', $m->ID );
		$team_widgets[] = sh_el_icon_box(
			get_the_title( $m->ID ),
			( $role ? $role . ' — ' : '' ) . wp_strip_all_tags( $m->post_content ),
			'fas fa-user'
		);
	}
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '60', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'background_background' => 'classic',
			'background_color'      => '#f8f7f4',
			'css_classes' => 'company-team-section',
		),
		array(
			sh_full_col(
				array(
					sh_el_text( '<span class="kicker">Unser Team</span>' ),
					sh_el_heading( 'Persönlich für Sie da.', 'h2', 'left' ),
					sh_el_text( '<p>Drei Persönlichkeiten, ein gemeinsamer Anspruch: Ihre Immobilie zuverlässig und mit Weitblick zu begleiten.</p>' ),
					sh_el_spacer( 30 ),
				)
			),
		)
	);
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'background_background' => 'classic',
			'background_color'      => '#f8f7f4',
		),
		array(
			sh_col( 33, array( $team_widgets[0] ) ),
			sh_col( 33, array( $team_widgets[1] ) ),
			sh_col( 33, array( $team_widgets[2] ) ),
		)
	);

	// Values + process.
	$values = array(
		array( 'Verlässlichkeit', 'Wir halten, was wir versprechen, und kommunizieren transparent.' ),
		array( 'Persönliche Betreuung', 'Sie haben einen festen Ansprechpartner, der Ihre Ziele kennt.' ),
		array( 'Fachkompetenz', 'Erfahrung und fundierte Marktkenntnis bilden die Basis unserer Arbeit.' ),
		array( 'Nachhaltigkeit', 'Der langfristige Werterhalt Ihrer Immobilie steht im Mittelpunkt.' ),
	);
	$process = array(
		array( '01', 'Erstgespräch', 'Wir besprechen Ihre Ziele, Anforderungen und Erwartungen in einem persönlichen Gespräch.' ),
		array( '02', 'Analyse', 'Wir analysieren Ihre Immobilie oder Ihren Bedarf und entwickeln eine massgeschneiderte Strategie.' ),
		array( '03', 'Umsetzung', 'Wir setzen die vereinbarten Massnahmen professionell, transparent und zuverlässig um.' ),
		array( '04', 'Partnerschaft', 'Wir begleiten Sie langfristig und stehen Ihnen als vertrauensvoller Partner zur Seite.' ),
	);
	$val_widgets = array();
	foreach ( $values as $v ) {
		$val_widgets[] = sh_el_icon_box( $v[0], $v[1], 'fas fa-check' );
	}
	$proc_widgets = array();
	foreach ( $process as $p ) {
		$proc_widgets[] = sh_el_icon_box( $p[1], $p[2], 'fas fa-arrow-right' );
	}
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'company-values-section',
		),
		array(
			sh_full_col( array( sh_el_text( '<span class="kicker">Werte & Arbeitsweise</span>' ), sh_el_heading( 'Klar in der Haltung.<br />Strukturiert im Handeln.', 'h2', 'left' ), sh_el_spacer( 40 ) ) ),
		)
	);
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '40', 'left' => '20', 'isLinked' => false ),
		),
		array(
			sh_col( 50, array_merge( array( sh_el_heading( 'Unsere Werte', 'h3' ), sh_el_spacer( 15 ) ), $val_widgets ) ),
			sh_col( 50, array_merge( array( sh_el_heading( 'So arbeiten wir', 'h3' ), sh_el_spacer( 15 ) ), $proc_widgets ) ),
		)
	);

	return $sections;
}

function sh_build_services() {
	$sections = array();
	$sections[] = sh_sec_split(
		array(
			sh_el_text( '<span class="kicker">Dienstleistungen</span>' ),
			sh_el_heading( 'Immobilien. Einfach gut begleitet.', 'h1', 'left' ),
			sh_el_text( '<p>Umfassende Immobiliendienstleistungen für Eigentümer, Investoren und Mieter – mit einem festen Ansprechpartner und klaren Lösungen.</p>' ),
		),
		array(),
		70,
		30
	);

	// Primary services grid (2x2).
	$cards = array();
	$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	foreach ( $services as $s ) {
		$tid = get_post_thumbnail_id( $s->ID );
		$w   = array();
		if ( $tid ) {
			$w[] = sh_el_image( $tid, 'center', 100 );
		}
		$w[] = sh_el_heading( get_the_title( $s->ID ), 'h2', 'left' );
		$w[] = sh_el_text( '<p>' . esc_html( get_post_meta( $s->ID, 'seehafen_lead', true ) ) . '</p>' );
		$w[] = sh_el_button( 'Mehr erfahren', get_permalink( $s->ID ), 'left', false );
		$cards[] = $w;
	}
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '20', 'right' => '20', 'bottom' => '60', 'left' => '20', 'isLinked' => false ),
		),
		array(
			sh_col( 50, $cards[0] ),
			sh_col( 50, $cards[1] ),
		)
	);
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
		),
		array(
			sh_col( 50, $cards[2] ),
			sh_col( 50, $cards[3] ),
		)
	);

	// Additional services (4).
	$data      = json_decode( file_get_contents( '/tmp/seed-data.json' ), true );
	$additional = isset( $data['additionalServices'] ) ? $data['additionalServices'] : array();
	$extra_widgets = array();
	foreach ( $additional as $svc ) {
		$img = sh_attachment_by_path( $svc['image'] );
		$w   = array();
		if ( $img ) {
			$w[] = sh_el_image( $img, 'center', 100 );
		}
		$w[] = sh_el_heading( $svc['title'], 'h3', 'left' );
		$w[] = sh_el_text( '<p>' . esc_html( $svc['text'] ) . '</p>' );
		if ( ! empty( $svc['points'] ) ) {
			$w[] = sh_el_icon_list( $svc['points'] );
		}
		$w[] = sh_el_button( 'Beratung anfragen', home_url( '/kontakt' ), 'left', false );
		$extra_widgets[] = $w;
	}
	$sections[] = sh_sec_padded(
		array(
			sh_el_text( '<span class="kicker">Weitere Fachbereiche</span>' ),
			sh_el_heading( 'Ergänzend für Sie da.', 'h2', 'left' ),
			sh_el_text( '<p>Bei komplexeren Vorhaben koordinieren wir auch die angrenzenden Themen – übersichtlich und aus einer Hand.</p>' ),
			sh_el_spacer( 30 ),
		),
		'#f8f7f4',
		'secondary-services-section'
	);
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'background_background' => 'classic',
			'background_color'      => '#f8f7f4',
		),
		array(
			sh_col( 50, $extra_widgets[0] ),
			sh_col( 50, $extra_widgets[1] ),
		)
	);
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'background_background' => 'classic',
			'background_color'      => '#f8f7f4',
		),
		array(
			sh_col( 50, $extra_widgets[2] ),
			sh_col( 50, $extra_widgets[3] ),
		)
	);

	// Process.
	$process = array(
		array( '01', 'Erstgespräch', 'Wir besprechen Ihre Ziele, Anforderungen und Erwartungen in einem persönlichen Gespräch.' ),
		array( '02', 'Analyse', 'Wir analysieren Ihre Immobilie oder Ihren Bedarf und entwickeln eine massgeschneiderte Strategie.' ),
		array( '03', 'Umsetzung', 'Wir setzen die vereinbarten Massnahmen professionell, transparent und zuverlässig um.' ),
		array( '04', 'Partnerschaft', 'Wir begleiten Sie langfristig und stehen Ihnen als vertrauensvoller Partner zur Seite.' ),
	);
	$proc_widgets = array();
	foreach ( $process as $p ) {
		$proc_widgets[] = sh_el_icon_box( $p[1], $p[2], 'fas fa-arrow-right' );
	}
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '80', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'process-section-el',
		),
		array(
			sh_full_col(
				array(
					sh_el_text( '<span class="kicker">Unser Prozess</span>' ),
					sh_el_heading( 'So arbeiten wir.', 'h2', 'left' ),
					sh_el_text( '<p>Strukturiert, transparent und immer im Interesse unserer Kundinnen und Kunden.</p>' ),
					sh_el_spacer( 30 ),
				)
			),
		)
	);
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
		),
		array(
			sh_col( 25, array( $proc_widgets[0] ) ),
			sh_col( 25, array( $proc_widgets[1] ) ),
			sh_col( 25, array( $proc_widgets[2] ) ),
			sh_col( 25, array( $proc_widgets[3] ) ),
		)
	);

	return $sections;
}

function sh_build_service_detail( $post_id ) {
	$hero_id = get_field( 'seehafen_hero_image', $post_id );
	$home_id = get_field( 'seehafen_home_image', $post_id );
	$lead    = get_post_meta( $post_id, 'seehafen_lead', true );
	$heading = get_post_meta( $post_id, 'seehafen_heading', true );
	$points  = get_post_meta( $post_id, 'seehafen_points', true );
	if ( ! is_array( $points ) ) {
		$points = array();
	}

	$left = array( sh_el_text( '<span class="kicker">Dienstleistungen</span>' ), sh_el_heading( get_the_title( $post_id ), 'h1', 'left' ) );
	if ( $lead ) {
		$left[] = sh_el_text( '<p>' . esc_html( $lead ) . '</p>' );
	}
	$right = array();
	if ( $heading ) {
		$right[] = sh_el_text( '<span class="kicker">Unsere Leistung</span>' );
		$right[] = sh_el_heading( $heading, 'h2', 'left' );
	}
	$right[] = sh_el_text( '<p>' . esc_html( get_post_field( 'post_content', $post_id ) ) . '</p>' );
	$right[] = sh_el_button( 'Beratung anfragen', home_url( '/kontakt' ), 'left', true );

	$sections   = array();
	$sections[] = sh_sec_split( $left, $right, 45, 55 );
	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '0', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'service-detail-support-section',
		),
		array(
			sh_col( 50, $home_id ? array( sh_el_image( $home_id, 'center', 100 ) ) : array() ),
			sh_col( 50, array_merge( array( sh_el_heading( 'Leistungen im Überblick', 'h3' ), sh_el_spacer( 15 ) ), array( sh_el_icon_list( $points ) ) ) ),
		)
	);
	return $sections;
}

function sh_build_angebote() {
	$data = json_decode( file_get_contents( '/tmp/seed-data.json' ), true );
	$homegate = isset( $data['homegateProfileUrl'] ) ? $data['homegateProfileUrl'] : 'https://www.homegate.ch';
	$img_prop3 = sh_attachment_by_path( 'property-3.jpg' );
	$img_prop2 = sh_attachment_by_path( 'property-2.jpg' );
	$sections = array();
	$sections[] = sh_sec_split(
		array(
			sh_el_text( '<span class="kicker">Angebote</span>' ),
			sh_el_heading( 'Immobilien im Überblick.', 'h1', 'left' ),
			sh_el_text( '<p>Entdecken Sie aktuelle Kauf- und Mietangebote oder werfen Sie einen Blick auf erfolgreich begleitete Projekte.</p>' ),
		),
		array(),
		70,
		30
	);

	$card1 = array();
	if ( $img_prop3 ) {
		$card1[] = sh_el_image( $img_prop3, 'center', 100 );
	}
	$card1[] = sh_el_heading( 'Aktuelle Angebote', 'h2', 'left' );
	$card1[] = sh_el_text( '<p>Verfügbare Kauf- und Mietobjekte auf unserem offiziellen Anbieterprofil.</p>' );
	$card1[] = sh_el_button( 'Auf Homegate', $homegate, 'left', false );

	$card2 = array();
	if ( $img_prop2 ) {
		$card2[] = sh_el_image( $img_prop2, 'center', 100 );
	}
	$card2[] = sh_el_heading( 'Referenzen', 'h2', 'left' );
	$card2[] = sh_el_text( '<p>Eine Auswahl verkaufter, vermieteter und verwalteter Immobilien.</p>' );
	$card2[] = sh_el_button( 'Entdecken', home_url( '/referenzen' ), 'left', false );

	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '20', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'overview-links-section',
		),
		array(
			sh_col( 50, $card1 ),
			sh_col( 50, $card2 ),
		)
	);
	return $sections;
}

function sh_build_referenzen() {
	$sections = array();
	$sections[] = sh_sec_padded(
		array(
			sh_el_heading( 'Referenzen', 'h1', 'left' ),
			sh_el_text( '<p>Ausgewählte erfolgreich verkaufte, vermietete und verwaltete Immobilienprojekte.</p>' ),
			sh_el_spacer( 30 ),
		),
		'',
		'references-title-section'
	);
	$sections[] = sh_sec_padded(
		array( sh_el_posts( 'reference', 28, 3 ) ),
		'',
		'reference-archive-section'
	);
	return $sections;
}

function sh_build_kontakt() {
	$sections = array();
	$sections[] = sh_sec_split(
		array(
			sh_el_text( '<span class="kicker">Kontakt</span>' ),
			sh_el_heading( 'Wie können wir Ihnen helfen?', 'h1', 'left' ),
			sh_el_text( '<p>Rufen Sie uns an, schreiben Sie uns eine E-Mail oder senden Sie Ihre Anfrage über das Formular. Wir melden uns persönlich bei Ihnen zurück.</p>' ),
		),
		array(),
		70,
		30
	);

	$sections[] = sh_el_section(
		array(
			'layout'   => 'boxed',
			'gap'      => 'extended',
			'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
			'padding'  => array( 'unit' => 'px', 'top' => '20', 'right' => '20', 'bottom' => '80', 'left' => '20', 'isLinked' => false ),
			'css_classes' => 'contact-page-section',
		),
		array(
			sh_col(
				35,
				array(
					sh_el_text( '<span class="kicker">Direkt erreichbar</span>' ),
					sh_el_heading( 'Persönlich für Sie da.', 'h2', 'left' ),
					sh_el_icon_list( array( '+41 44 451 43 02 (Telefon)', '+41 79 785 78 80 (Mobil)', 'info@seehafen-immobilien.ch (E-Mail)' ) ),
					sh_el_text( '<p><strong>Öffnungszeiten</strong><br />Montag bis Freitag<br />08:00–12:00 · 13:30–17:00 Uhr</p>' ),
					sh_el_spacer( 30 ),
					sh_el_text( '<span class="kicker">Hauptsitz</span><h3 style="margin:4px 0">Schwyz</h3><p>Bahnhofstrasse 4<br />6430 Schwyz</p>' ),
					sh_el_text( '<span class="kicker">Filiale</span><h3 style="margin:4px 0">Wohlen</h3><p>Cheiblerrain 13<br />5610 Wohlen</p>' ),
				)
			),
			sh_col(
				65,
				array(
					sh_el_text( '<span class="kicker">Nachricht senden</span>' ),
					sh_el_heading( 'Ihre Anfrage', 'h2', 'left' ),
					sh_el_text( '<p>Füllen Sie nur die notwendigen Angaben aus.</p>' ),
					sh_el_spacer( 20 ),
					sh_el_shortcode( '[contact-form-7 title="Kontaktformular"]' ),
				)
			),
		)
	);
	return $sections;
}

function sh_build_legal( $content_html ) {
	return array(
		sh_sec_split(
			array(
				sh_el_text( '<span class="kicker">Rechtliches</span>' ),
				sh_el_heading( 'Rechtliches', 'h1', 'left' ),
			),
			array(),
			70,
			30
		),
		sh_sec_padded(
			array( sh_el_text( $content_html ) ),
			'',
			'legal-section'
		),
	);
}

// ---------- Run ----------

$legal_pages = array(
	'impressum'   => '<h2>Unternehmensinformationen</h2><p><strong>Seehafen & Partner Immobilien AG</strong><br />Bahnhofstrasse 4<br />6430 Schwyz<br />Schweiz</p><h2>Kontakt</h2><p>E-Mail: <a href="mailto:info@seehafen-immobilien.ch">info@seehafen-immobilien.ch</a></p><h2>Handelsregistereintrag</h2><p>Eingetragener Firmenname: Seehafen &amp; Partner Immobilien AG<br />Handelsregister des Kantons Schwyz<br />UID: CHE-437.125.709</p><h2>Haftungsausschluss</h2><p>Die Inhalte dieser Website werden mit grösster Sorgfalt erstellt und regelmässig geprüft. Dennoch übernimmt die Seehafen &amp; Partner Immobilien AG keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität der bereitgestellten Informationen.</p><h2>Urheberrecht</h2><p>Die auf dieser Website veröffentlichten Inhalte und Werke unterliegen dem schweizerischen Urheberrecht. Jede Art der Vervielfältigung, Bearbeitung, Verbreitung oder sonstigen Verwertung ausserhalb der Grenzen des Urheberrechts bedarf der vorgängigen schriftlichen Zustimmung des jeweiligen Rechteinhabers.</p>',
	'datenschutz' => '<p>Der Schutz Ihrer persönlichen Daten ist der Seehafen &amp; Partner Immobilien AG ein wichtiges Anliegen. In dieser Datenschutzerklärung informieren wir Sie darüber, wie personenbezogene Daten auf dieser Website bearbeitet werden.</p><h2>Verantwortliche Stelle</h2><p>Verantwortlich für die Datenbearbeitung im Sinne des schweizerischen Datenschutzgesetzes (DSG) ist:</p><p><strong>Seehafen &amp; Partner Immobilien AG</strong><br />Bahnhofstrasse 4<br />6430 Schwyz<br />Schweiz<br />E-Mail: <a href="mailto:info@seehafen-immobilien.ch">info@seehafen-immobilien.ch</a></p><h2>Erhebung und Bearbeitung personenbezogener Daten</h2><p>Personenbezogene Daten werden erhoben, wenn Sie uns diese freiwillig mitteilen, beispielsweise bei der Kontaktaufnahme per E-Mail oder über ein Kontaktformular. Die Bearbeitung erfolgt ausschliesslich zum Zweck der Bearbeitung Ihrer Anfrage oder zur Kontaktaufnahme.</p><h2>Weitergabe von Daten an Dritte</h2><p>Eine Weitergabe personenbezogener Daten an Dritte erfolgt nur, sofern dies zur Vertragserfüllung erforderlich ist, eine gesetzliche Verpflichtung besteht oder Sie ausdrücklich eingewilligt haben.</p><h2>Cookies</h2><p>Diese Website verwendet Cookies, um die Funktionalität und Benutzerfreundlichkeit zu verbessern. Sie können die Verwendung von Cookies in den Einstellungen Ihres Browsers einschränken oder deaktivieren.</p><h2>Rechte der betroffenen Personen</h2><p>Sie haben im Rahmen der geltenden datenschutzrechtlichen Bestimmungen das Recht auf Auskunft über die zu Ihrer Person gespeicherten Daten sowie das Recht auf Berichtigung, Löschung oder Einschränkung der Bearbeitung. Anfragen richten Sie bitte an die oben genannte Kontaktadresse.</p>',
	'agb'         => '<h2>1. Geltungsbereich</h2><p>Diese AGB gelten für alle Dienstleistungen der Seehafen &amp; Partner Immobilien AG im Bereich Immobilienbewirtschaftung, Vermarktung, Beratung und verwandte Dienstleistungen.</p><h2>2. Vertragsabschluss</h2><p>Ein Vertrag kommt durch schriftliche Bestätigung des Auftrags durch die Seehafen &amp; Partner Immobilien AG zustande.</p><h2>3. Leistungsumfang</h2><p>Der Umfang der zu erbringenden Leistungen ergibt sich aus dem jeweiligen Einzelvertrag.</p><h2>4. Honorare und Zahlungsbedingungen</h2><p>Die Honorare werden im Einzelvertrag vereinbart. Rechnungen sind innert 30 Tagen nach Rechnungsstellung ohne Abzug zahlbar.</p><h2>5. Vertraulichkeit</h2><p>Die Seehafen &amp; Partner Immobilien AG verpflichtet sich, alle im Rahmen der Geschäftsbeziehung erlangten Informationen vertraulich zu behandeln.</p><h2>6. Haftung</h2><p>Die Haftung der Seehafen &amp; Partner Immobilien AG beschränkt sich auf Vorsatz und grobe Fahrlässigkeit.</p><h2>7. Anwendbares Recht und Gerichtsstand</h2><p>Es gilt schweizerisches Recht. Ausschliesslicher Gerichtsstand ist Schwyz.</p>',
);

$built = array();

$home_id = sh_page_id( 'home' );
if ( $home_id ) {
	sh_el_save( $home_id, sh_build_home( $HOME ) );
	$built[] = 'home';
}

$firma_id = sh_page_id( 'firma' );
if ( $firma_id ) {
	sh_el_save( $firma_id, sh_build_firma() );
	$built[] = 'firma';
}

$dienst_id = sh_page_id( 'dienstleistungen' );
if ( $dienst_id ) {
	sh_el_save( $dienst_id, sh_build_services() );
	$built[] = 'dienstleistungen';
}

$angebote_id = sh_page_id( 'angebote' );
if ( $angebote_id ) {
	sh_el_save( $angebote_id, sh_build_angebote() );
	$built[] = 'angebote';
}

$referenzen_id = sh_page_id( 'referenzen' );
if ( $referenzen_id ) {
	sh_el_save( $referenzen_id, sh_build_referenzen() );
	$built[] = 'referenzen';
}

$kontakt_id = sh_page_id( 'kontakt' );
if ( $kontakt_id ) {
	sh_el_save( $kontakt_id, sh_build_kontakt() );
	$built[] = 'kontakt';
}

foreach ( $legal_pages as $slug => $html ) {
	$id = sh_page_id( $slug );
	if ( $id ) {
		sh_el_save( $id, sh_build_legal( $html ) );
		$built[] = $slug;
	}
}

// Service detail pages.
$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
foreach ( $services as $s ) {
	sh_el_save( $s->ID, sh_build_service_detail( $s->ID ) );
	$built[] = 'service:' . $s->post_name;
}

echo 'Built Elementor pages: ' . implode( ', ', $built ) . "\n";
