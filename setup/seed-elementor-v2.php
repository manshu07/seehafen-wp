<?php
/**
 * One-time v2: rebuild Elementor page data using SPA-exact markup via HTML widgets + theme shortcodes.
 * Run: wp eval-file /var/www/html/wp-content/seed-elementor-v2.php --allow-root
 */

function sh2_id() {
	return substr( md5( uniqid( '', true ) ), 0, 7 );
}

function sh2_html_widget( $html ) {
	return array(
		'id'         => sh2_id(),
		'elType'     => 'widget',
		'widgetType' => 'html',
		'settings'   => array( 'html' => $html ),
		'elements'   => array(),
	);
}

function sh2_shortcode_widget( $code ) {
	return sh2_html_widget( do_shortcode( $code ) );
}

function sh2_section( $widgets, $css = '', $full = true ) {
	$settings = array(
		'layout'      => $full ? 'full_width' : 'boxed',
		'gap'         => 'no',
		'content_width' => array( 'unit' => 'px', 'size' => 1200, 'sizes' => array() ),
		'padding'     => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
	);
	if ( $css ) {
		$settings['css_classes'] = $css;
	}
	return array(
		'id'       => sh2_id(),
		'elType'   => 'section',
		'settings' => $settings,
		'elements' => array(
			array(
				'id'       => sh2_id(),
				'elType'   => 'column',
				'settings' => array(
					'_column_size' => 100,
					'_inline_size' => null,
					'padding'      => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
				),
				'elements' => $widgets,
				'isInner'  => false,
			),
		),
		'isInner' => false,
	);
}

function sh2_save( $post_id, $data ) {
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
	update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	update_post_meta( $post_id, '_elementor_page_settings', array( 'template' => 'elementor_header_footer' ) );
	update_post_meta( $post_id, '_wp_page_template', 'elementor_header_footer' );
}

function sh2_page_id( $slug ) {
	$p = get_page_by_path( $slug );
	return $p ? $p->ID : 0;
}

function sh2_asset_url( $path ) {
	$attachments = get_posts( array( 'post_type' => 'attachment', 'posts_per_page' => 1, 'meta_key' => '_wp_attached_file', 'meta_value' => 'assets/' . basename( $path ), 'meta_compare' => 'LIKE', 'fields' => 'ids' ) );
	return $attachments ? wp_get_attachment_url( $attachments[0] ) : '';
}

// ---------- Service detail shortcode (used via do_shortcode below) ----------
add_shortcode(
	'seehafen_service_detail_render',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'id' => 0 ), $atts );
		$post_id = (int) $atts['id'];
		if ( ! $post_id ) {
			return '';
		}
		$lead    = get_post_meta( $post_id, 'seehafen_lead', true );
		$heading = get_post_meta( $post_id, 'seehafen_heading', true );
		$points  = (array) get_post_meta( $post_id, 'seehafen_points', true );
		$img     = '';
		$home_img = function_exists( 'get_field' ) ? get_field( 'seehafen_home_image', $post_id ) : '';
		if ( is_array( $home_img ) && ! empty( $home_img['url'] ) ) {
			$img = $home_img['url'];
		} elseif ( is_numeric( $home_img ) && $home_img ) {
			$img = wp_get_attachment_url( $home_img );
		}
		$points_html = '';
		foreach ( $points as $point ) {
			$points_html .= '<li>' . seehafen_icon( 'check' ) . ' <span>' . esc_html( $point ) . '</span></li>';
		}
		return '<section class="service-detail">
			<div class="content service-detail-header-grid">
				<div class="service-detail-heading">
					<span class="kicker">Dienstleistungen</span>
					<h1>' . esc_html( get_the_title( $post_id ) ) . '</h1>
					<p>' . esc_html( $lead ) . '</p>
				</div>
				<div class="service-detail-explanation">
					<span class="kicker">Unsere Leistung</span>
					<h2>' . esc_html( $heading ) . '</h2>
					<p>' . esc_html( get_post_field( 'post_content', $post_id ) ) . '</p>
					<a class="button button-solid" href="' . esc_url( home_url( '/kontakt' ) ) . '">Beratung anfragen ' . seehafen_icon( 'arrow-right' ) . '</a>
				</div>
			</div>
			<div class="service-detail-support-wrap">
				<div class="content service-detail-support">
					<img src="' . esc_url( $img ) . '" alt="" />
					<ul class="service-detail-points">' . $points_html . '</ul>
				</div>
			</div>
		</section>';
	}
);

// ---------- Legal pages content ----------
$legal_pages = array(
	'impressum' => '<h2>Unternehmensinformationen</h2>
<p><strong>Seehafen &amp; Partner Immobilien AG</strong><br />Bahnhofstrasse 4<br />6430 Schwyz<br />Schweiz</p>
<h2>Kontakt</h2>
<p>E-Mail: <a href="mailto:info@seehafen-immobilien.ch">info@seehafen-immobilien.ch</a></p>
<h2>Handelsregistereintrag</h2>
<p>Eingetragener Firmenname: Seehafen &amp; Partner Immobilien AG<br />Handelsregister des Kantons Schwyz<br />UID: CHE-437.125.709</p>
<h2>Haftungsausschluss</h2>
<p>Die Inhalte dieser Website werden mit grösster Sorgfalt erstellt und regelmässig geprüft. Dennoch übernimmt die Seehafen &amp; Partner Immobilien AG keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität der bereitgestellten Informationen.</p>
<p>Als Diensteanbieter sind wir gemäss den anwendbaren gesetzlichen Bestimmungen für eigene Inhalte auf diesen Seiten verantwortlich. Eine Verpflichtung zur Überwachung übermittelter oder gespeicherter fremder Informationen besteht nicht. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden diese Inhalte umgehend entfernt.</p>
<h2>Urheberrecht</h2>
<p>Die auf dieser Website veröffentlichten Inhalte und Werke unterliegen dem schweizerischen Urheberrecht. Jede Art der Vervielfältigung, Bearbeitung, Verbreitung oder sonstigen Verwertung ausserhalb der Grenzen des Urheberrechts bedarf der vorgängigen schriftlichen Zustimmung des jeweiligen Rechteinhabers.</p>',
	'datenschutz' => '<p>Der Schutz Ihrer persönlichen Daten ist der Seehafen &amp; Partner Immobilien AG ein wichtiges Anliegen. In dieser Datenschutzerklärung informieren wir Sie darüber, wie personenbezogene Daten auf dieser Website bearbeitet werden.</p>
<h2>Verantwortliche Stelle</h2>
<p>Verantwortlich für die Datenbearbeitung im Sinne des schweizerischen Datenschutzgesetzes (DSG) ist:</p>
<p><strong>Seehafen &amp; Partner Immobilien AG</strong><br />Bahnhofstrasse 4<br />6430 Schwyz<br />Schweiz<br />E-Mail: <a href="mailto:info@seehafen-immobilien.ch">info@seehafen-immobilien.ch</a></p>
<h2>Erhebung und Bearbeitung personenbezogener Daten</h2>
<p>Personenbezogene Daten werden erhoben, wenn Sie uns diese freiwillig mitteilen, beispielsweise bei der Kontaktaufnahme per E-Mail oder über ein Kontaktformular. Dabei kann es sich insbesondere um Name, E-Mail-Adresse, Telefonnummer oder weitere von Ihnen übermittelte Informationen handeln. Die Bearbeitung dieser Daten erfolgt ausschliesslich zum Zweck der Bearbeitung Ihrer Anfrage oder zur Kontaktaufnahme mit Ihnen.</p>
<h2>Zweck der Datenbearbeitung</h2>
<p>Die Bearbeitung personenbezogener Daten erfolgt zur Beantwortung von Anfragen, zur Erfüllung vertraglicher und vorvertraglicher Pflichten sowie zur Erbringung unserer Dienstleistungen im Bereich Immobilien.</p>
<h2>Weitergabe von Daten an Dritte</h2>
<p>Eine Weitergabe personenbezogener Daten an Dritte erfolgt nur, sofern dies zur Vertragserfüllung erforderlich ist, eine gesetzliche Verpflichtung besteht oder Sie ausdrücklich eingewilligt haben. Eine Übermittlung ins Ausland oder an Drittstaaten findet nicht statt.</p>
<h2>Datensicherheit</h2>
<p>Wir setzen angemessene technische und organisatorische Sicherheitsmassnahmen ein, um personenbezogene Daten vor unbefugtem Zugriff, Verlust, Missbrauch oder Manipulation zu schützen. Diese Massnahmen werden entsprechend der technologischen Entwicklung laufend angepasst.</p>
<h2>Cookies</h2>
<p>Diese Website verwendet Cookies, um die Funktionalität und Benutzerfreundlichkeit zu verbessern. Cookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden. Sie können die Verwendung von Cookies in den Einstellungen Ihres Browsers einschränken oder deaktivieren. Die Deaktivierung kann die Funktionalität der Website beeinträchtigen.</p>
<h2>Rechte der betroffenen Personen</h2>
<p>Sie haben im Rahmen der geltenden datenschutzrechtlichen Bestimmungen das Recht auf Auskunft über die zu Ihrer Person gespeicherten Daten sowie das Recht auf Berichtigung, Löschung oder Einschränkung der Bearbeitung. Anfragen richten Sie bitte an die oben genannte Kontaktadresse.</p>
<h2>Änderungen dieser Datenschutzerklärung</h2>
<p>Die Seehafen &amp; Partner Immobilien AG behält sich vor, diese Datenschutzerklärung jederzeit anzupassen, insbesondere bei Änderungen gesetzlicher Vorgaben oder bei Weiterentwicklungen der Website oder Dienstleistungen.</p>',
	'agb' => '<h2>1. Geltungsbereich</h2>
<p>Diese AGB gelten für alle Dienstleistungen der Seehafen &amp; Partner Immobilien AG im Bereich Immobilienbewirtschaftung, Vermarktung, Beratung und verwandte Dienstleistungen.</p>
<h2>2. Vertragsabschluss</h2>
<p>Ein Vertrag kommt durch schriftliche Bestätigung des Auftrags durch die Seehafen &amp; Partner Immobilien AG zustande. Mündliche Nebenabreden bedürfen der schriftlichen Bestätigung.</p>
<h2>3. Leistungsumfang</h2>
<p>Der Umfang der zu erbringenden Leistungen ergibt sich aus dem jeweiligen Einzelvertrag. Die Seehafen &amp; Partner Immobilien AG erbringt ihre Leistungen mit der Sorgfalt eines ordentlichen Kaufmanns.</p>
<h2>4. Honorare und Zahlungsbedingungen</h2>
<p>Die Honorare werden im Einzelvertrag vereinbart. Rechnungen sind innert 30 Tagen nach Rechnungsstellung ohne Abzug zahlbar. Bei Zahlungsverzug werden Verzugszinsen von 5 % p. a. berechnet.</p>
<h2>5. Vertraulichkeit</h2>
<p>Die Seehafen &amp; Partner Immobilien AG verpflichtet sich, alle im Rahmen der Geschäftsbeziehung erlangten Informationen vertraulich zu behandeln.</p>
<h2>6. Haftung</h2>
<p>Die Haftung der Seehafen &amp; Partner Immobilien AG beschränkt sich auf Vorsatz und grobe Fahrlässigkeit. Eine weitergehende Haftung ist ausgeschlossen, soweit gesetzlich zulässig.</p>
<h2>7. Anwendbares Recht und Gerichtsstand</h2>
<p>Es gilt schweizerisches Recht. Ausschliesslicher Gerichtsstand ist Schwyz.</p>',
);
$legal_titles = array(
	'impressum' => 'Impressum',
	'datenschutz' => 'Datenschutzerklärung',
	'agb' => 'Allgemeine Geschäftsbedingungen'
);

$built = array();

// ---------- Home ----------
$home_id = sh2_page_id( 'home' );
if ( $home_id ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_home_hero]' ) ), 'hero-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_home_intro]' ) ), 'home-intro-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_offers_showcase]' ) ), 'home-offers-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_references limit="3" preview="true"]' ) ), 'home-references-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_cta]' ) ), 'contact-strip-section' ),
	);
	sh2_save( $home_id, $data );
	$built[] = 'home';
}

// ---------- Firma ----------
$firma_id = sh2_page_id( 'firma' );
if ( $firma_id ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_company_about]' ) ), 'company-about-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_team]' ) ), 'company-team-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_values]' ) ), 'company-values-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_cta]' ) ), 'contact-strip-section' ),
	);
	sh2_save( $firma_id, $data );
	$built[] = 'firma';
}

// ---------- Dienstleistungen ----------
$dienst_id = sh2_page_id( 'dienstleistungen' );
if ( $dienst_id ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_page_hero label="Dienstleistungen" title="Immobilien. Einfach gut begleitet." text="Umfassende Immobiliendienstleistungen für Eigentümer, Investoren und Mieter – mit einem festen Ansprechpartner und klaren Lösungen." image="' . sh2_asset_url( 'property-hero.jpg' ) . '"]' ) ), 'page-hero-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_primary_services]' ) ), 'services-page-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_secondary_services]' ) ), 'secondary-services-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_process compact="true"]' ) ), 'process-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_cta]' ) ), 'contact-strip-section' ),
	);
	sh2_save( $dienst_id, $data );
	$built[] = 'dienstleistungen';
}

// ---------- Service details ----------
$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
foreach ( $services as $s ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_service_detail_render id="' . $s->ID . '"]' ) ), 'service-detail-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_cta]' ) ), 'contact-strip-section' ),
	);
	sh2_save( $s->ID, $data );
	$built[] = 'service:' . $s->post_name;
}

// ---------- Angebote ----------
$angebote_id = sh2_page_id( 'angebote' );
if ( $angebote_id ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_overview_links]' ) ), 'overview-links-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_cta]' ) ), 'contact-strip-section' ),
	);
	sh2_save( $angebote_id, $data );
	$built[] = 'angebote';
}

// ---------- Referenzen ----------
$referenzen_id = sh2_page_id( 'referenzen' );
if ( $referenzen_id ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_references_title]' ) ), 'references-title-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_references limit="9" show_more="true"]' ) ), 'reference-archive-section' ),
		sh2_section( array( sh2_shortcode_widget( '[seehafen_cta]' ) ), 'contact-strip-section' ),
	);
	sh2_save( $referenzen_id, $data );
	$built[] = 'referenzen';
}

// ---------- Kontakt ----------
$kontakt_id = sh2_page_id( 'kontakt' );
if ( $kontakt_id ) {
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_contact_intro]' ) ), 'contact-intro-section' ),
		sh2_section(
			array(
				sh2_html_widget(
					'<section class="contact-page"><div class="content contact-layout"><aside class="contact-sidebar">' . do_shortcode( '[seehafen_contact_sidebar]' ) . '</aside>' . do_shortcode( '[seehafen_contact_form]' ) . '</div></section>'
				),
			),
			'contact-page-section'
		),
	);
	sh2_save( $kontakt_id, $data );
	$built[] = 'kontakt';
}

// ---------- Legal pages ----------
foreach ( $legal_pages as $slug => $html ) {
	$id = sh2_page_id( $slug );
	if ( ! $id ) {
		continue;
	}
	$intro = array(
		'impressum'   => 'Unternehmensinformationen und rechtliche Hinweise der Seehafen & Partner Immobilien AG.',
		'datenschutz' => 'Informationen zur Bearbeitung personenbezogener Daten auf dieser Website.',
		'agb'         => 'Diese AGB regeln die Geschäftsbeziehung zwischen der Seehafen & Partner Immobilien AG und ihren Kunden.',
	);
	$data = array(
		sh2_section( array( sh2_shortcode_widget( '[seehafen_page_hero label="Rechtliches" title="' . ( $legal_titles[ $slug ] ?? ucfirst( $slug ) ) . '" text="' . $intro[ $slug ] . '"]' ) ), 'page-hero-section' ),
		sh2_section( array( sh2_html_widget( '<section class="legal-page"><div class="content legal-content">' . $html . '</div></section>' ) ), 'legal-section' ),
	);
	sh2_save( $id, $data );
	$built[] = $slug;
}

echo 'Built v2 Elementor pages: ' . implode( ', ', $built ) . "\n";
