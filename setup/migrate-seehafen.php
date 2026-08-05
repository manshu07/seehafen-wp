<?php
/**
 * One-time migration: seeds all Seehafen content 1:1 from the React SPA.
 * Run via: wp eval-file /var/www/html/wp-content/migrate-seehafen.php
 * Then delete this file.
 *
 * @package Seehafen
 */

defined( 'ABSPATH' ) || exit;

$asset_base = '/var/www/html/wp-content/themes/seehafen/assets/img';

/**
 * Import an image from the theme assets into the media library.
 *
 * @param string $relative Relative path under assets/img.
 * @param string $title    Attachment title.
 *
 * @return int Attachment ID or 0 on failure.
 */
function sh_import_image( $relative, $title ) {
	global $asset_base;

	$file = $asset_base . '/' . ltrim( $relative, '/' );

	if ( ! file_exists( $file ) ) {
		return 0;
	}

	$tmp = wp_tempnam( basename( $file ) );
	copy( $file, $tmp );

	$file_array = array(
		'name'     => basename( $file ),
		'tmp_name' => $tmp,
	);

	$attachment_id = media_handle_sideload( $file_array, 0, $title );

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	return $attachment_id;
}

/**
 * Create a post of a given type with meta.
 *
 * @param string $type      Post type.
 * @param string $title     Title.
 * @param string $slug      Slug.
 * @param array  $meta      Meta key => value.
 * @param string $term_slug Taxonomy term (optional).
 * @param int    $image_id  Featured image attachment ID.
 * @param int    $order     Menu order.
 *
 * @return int Post ID.
 */
function sh_create_post( $type, $title, $slug, $meta = array(), $term_slug = '', $image_id = 0, $order = 0 ) {
	$post_id = wp_insert_post( array(
		'post_type'    => $type,
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_status'  => 'publish',
		'menu_order'   => $order,
	), true );

	if ( is_wp_error( $post_id ) ) {
		return 0;
	}

	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	if ( '' !== $term_slug ) {
		wp_set_object_terms( $post_id, array( $term_slug ), 'service_type' );
	}

	if ( $image_id ) {
		set_post_thumbnail( $post_id, $image_id );
	}

	return $post_id;
}

$log = array();

// 1. Taxonomies.
wp_insert_term( 'primary', 'service_type' );
wp_insert_term( 'additional', 'service_type' );
wp_insert_term( 'verkauft', 'reference_type', array( 'description' => 'Verkauft' ) );
wp_insert_term( 'vermietet', 'reference_type', array( 'description' => 'Vermietet' ) );
wp_insert_term( 'verwaltung', 'reference_type', array( 'description' => 'Verwaltungsmandat' ) );
$log[] = 'taxonomies created';

// 2. Services (primary).
$primary_services = array(
	array(
		'slug'        => 'immobilienverkauf',
		'title'       => 'Immobilienverkauf',
		'hero'        => 'property-hero.jpg',
		'detail'      => 'team-1.jpg',
		'home_image'  => 'team-1.jpg',
		'home_text'   => 'Persönlich begleitet – von der fundierten Bewertung bis zum erfolgreichen Abschluss.',
		'lead'        => 'Wir positionieren Ihre Immobilie klar und begleiten den Verkauf persönlich bis zum Abschluss.',
		'heading'     => 'Ein Verkauf mit Plan und persönlicher Begleitung.',
		'copy'        => 'Von der ersten Einschätzung bis zur Schlüsselübergabe erhalten Sie eine klare Strategie, eine hochwertige Präsentation und einen festen Ansprechpartner.',
		'points'      => array( 'Marktgerechte Positionierung', 'Professionelle Vermarktung', 'Besichtigungen und Verhandlungen', 'Begleitung bis zum Vertragsabschluss' ),
	),
	array(
		'slug'        => 'immobilienbewertung',
		'title'       => 'Immobilienbewertung',
		'hero'        => 'property-1.jpg',
		'detail'      => 'team-3.jpg',
		'home_image'  => 'team-3.jpg',
		'home_text'   => 'Nachvollziehbare Entscheidungsgrundlagen für Eigentümer, Käufer und Investoren.',
		'lead'        => 'Eine fundierte Bewertung schafft Sicherheit für Verkauf, Kauf und langfristige Entscheidungen.',
		'heading'     => 'Klarheit über den Wert Ihrer Immobilie.',
		'copy'        => 'Wir verbinden Marktkenntnis, Lage, Zustand und Potenzial zu einer nachvollziehbaren Einschätzung – verständlich erklärt und auf Ihre Situation abgestimmt.',
		'points'      => array( 'Analyse von Lage und Objekt', 'Vergleich mit dem aktuellen Markt', 'Einschätzung von Potenzialen', 'Persönliche Besprechung der Ergebnisse' ),
	),
	array(
		'slug'        => 'stockwerkeigentum',
		'title'       => 'Stockwerkeigentum',
		'hero'        => 'about.jpg',
		'detail'      => 'team-2.jpg',
		'home_image'  => 'about.jpg',
		'home_text'   => 'Strukturierte Verwaltung, transparente Kommunikation und persönliche Betreuung.',
		'lead'        => 'Wir verwalten Stockwerkeigentum strukturiert, transparent und mit Blick auf die Gemeinschaft.',
		'heading'     => 'Verwaltung, die Eigentümer entlastet.',
		'copy'        => 'Wir koordinieren Administration, Unterhalt und Versammlungen zuverlässig und sorgen für klare Kommunikation zwischen allen Beteiligten.',
		'points'      => array( 'Eigentümerversammlungen', 'Budget und Abrechnungen', 'Unterhalt und technische Koordination', 'Transparente Eigentümerkommunikation' ),
	),
	array(
		'slug'        => 'mietliegenschaften',
		'title'       => 'Mietliegenschaften',
		'hero'        => 'property-2.jpg',
		'detail'      => 'property-3.jpg',
		'home_image'  => 'team-2.jpg',
		'home_text'   => 'Zuverlässige Bewirtschaftung mit klaren Prozessen und Blick auf den langfristigen Werterhalt.',
		'lead'        => 'Eine verlässliche Bewirtschaftung schützt den Wert Ihrer Liegenschaft und entlastet Sie im Alltag.',
		'heading'     => 'Persönlich betreut. Nachhaltig bewirtschaftet.',
		'copy'        => 'Von der Vermietung bis zur Abrechnung übernehmen wir die laufende Betreuung Ihrer Mietliegenschaft effizient und nachvollziehbar.',
		'points'      => array( 'Mieterbetreuung und Vermietung', 'Mietzinsinkasso und Mahnwesen', 'Nebenkostenabrechnungen', 'Unterhalt und Objektkontrollen' ),
	),
);

$service_ids = array();

foreach ( $primary_services as $index => $service ) {
	$hero_id  = sh_import_image( $service['hero'], $service['title'] . ' hero' );
	$detail_id = sh_import_image( $service['detail'], $service['title'] . ' detail' );
	$home_id  = sh_import_image( $service['home_image'], $service['title'] . ' home' );

	$hero_url  = $hero_id ? wp_get_attachment_url( $hero_id ) : '';
	$detail_url = $detail_id ? wp_get_attachment_url( $detail_id ) : '';
	$home_url  = $home_id ? wp_get_attachment_url( $home_id ) : '';

	$post_id = sh_create_post(
		'service',
		$service['title'],
		$service['slug'],
		array(
			'_seehafen_lead'         => $service['lead'],
			'_seehafen_heading'      => $service['heading'],
			'_seehafen_copy'         => $service['copy'],
			'_seehafen_points'       => implode( "\n", $service['points'] ),
			'_seehafen_hero_image'   => $hero_url,
			'_seehafen_detail_image' => $detail_url,
			'_seehafen_home_image'   => $home_url,
			'_seehafen_home_text'    => $service['home_text'],
		),
		'primary',
		$hero_id,
		$index + 1
	);

	$service_ids[ $service['slug'] ] = $post_id;
}

$log[] = 'primary services: ' . count( $primary_services );

// 3. Services (additional).
$additional_services = array(
	array(
		'slug'   => 'erstvermietung',
		'title'  => 'Erstvermietung & Neubau',
		'image'  => 'property-1.jpg',
		'text'   => 'Von der Marktanalyse bis zur Übergabe: klare Positionierung und koordinierte Erstvermietung.',
		'points' => array( 'Markt- und Standortanalysen', 'Mietpreisgestaltung', 'Branding und Marketing', 'Übergaben und Mieterkoordination' ),
	),
	array(
		'slug'   => 'baumanagement',
		'title'  => 'Baumanagement & Unterhalt',
		'image'  => 'property-2.jpg',
		'text'   => 'Strukturierte Planung und Kontrolle für Sanierungen, Unterhalt und Umbauten.',
		'points' => array( 'Sanierungs- und Unterhaltsplanung', 'Offertvergleich und Vergabe', 'Termin- und Kostenkontrolle', 'Abnahmen und Qualitätskontrollen' ),
	),
	array(
		'slug'   => 'administration',
		'title'  => 'Administration & Recht',
		'image'  => 'team-1.jpg',
		'text'   => 'Sorgfältige Unterstützung bei Verträgen, Verfahren und der laufenden Dokumentation.',
		'points' => array( 'Mietverträge und Anpassungen', 'Beendigung von Mietverhältnissen', 'Begleitung bei Schlichtungsverfahren', 'Dokumentenmanagement' ),
	),
	array(
		'slug'   => 'investments',
		'title'  => 'Immobilieninvestments',
		'image'  => 'property-3.jpg',
		'text'   => 'Persönliche Begleitung bei Investitionsentscheiden sowie An- und Verkaufsprozessen.',
		'points' => array( 'Investitionsberatung', 'Off-Market-Deals', 'Portfolio- und Potenzialanalysen', 'Begleitung bei An- und Verkäufen' ),
	),
);

foreach ( $additional_services as $index => $service ) {
	$image_id = sh_import_image( $service['image'], $service['title'] );
	$image_url = $image_id ? wp_get_attachment_url( $image_id ) : '';

	sh_create_post(
		'service',
		$service['title'],
		$service['slug'],
		array(
			'_seehafen_copy'   => $service['text'],
			'_seehafen_points' => implode( "\n", $service['points'] ),
			'_seehafen_hero_image' => $image_url,
		),
		'additional',
		$image_id,
		$index + 5
	);
}

$log[] = 'additional services: ' . count( $additional_services );

// 4. Offers.
$offers = array(
	array(
		'slug'     => 'schaffhausen-15-zimmer',
		'title'    => 'Moderne 1.5-Zimmer-Wohnung in Schaffhausen',
		'image'    => 'offers/schaffhausen-15-zimmer.avif',
		'label'    => 'Miete',
		'location' => 'Schaffhausen',
		'price'    => "1'450.–",
		'rooms'    => '1.5 Zimmer',
		'area'     => '32 m²',
	),
	array(
		'slug'     => 'huttwil-35-zimmer',
		'title'    => 'Moderne 3.5-Zimmer-Wohnung im Neubau – Huttwil',
		'image'    => 'offers/huttwil-35-zimmer.avif',
		'label'    => 'Miete',
		'location' => 'Huttwil',
		'price'    => "1'570.–",
		'rooms'    => '3.5 Zimmer',
		'area'     => '72 m²',
	),
	array(
		'slug'     => 'wohlen-lagerraum',
		'title'    => 'Lagerraum zur Miete an der Oberen Haldenstrasse in Wohlen',
		'image'    => 'offers/wohlen-lagerraum.avif',
		'label'    => 'Miete',
		'location' => 'Wohlen',
		'price'    => '250.–',
		'rooms'    => '1 Zimmer',
		'area'     => '',
	),
);

foreach ( $offers as $index => $offer ) {
	$image_id = sh_import_image( $offer['image'], $offer['title'] );

	sh_create_post(
		'offer',
		$offer['title'],
		$offer['slug'],
		array(
			'_seehafen_label'    => $offer['label'],
			'_seehafen_location' => $offer['location'],
			'_seehafen_price'    => $offer['price'],
			'_seehafen_rooms'    => $offer['rooms'],
			'_seehafen_area'     => $offer['area'],
		),
		'',
		$image_id,
		$index + 1
	);
}

$log[] = 'offers: ' . count( $offers );

// 5. References.
$references = array(
	array( 'Mehrfamilienhaus', 'Hägglingen AG', 'verkauft', '6 Wohnungen', 'references/sale-haegglingen-6.jpg' ),
	array( 'Wohnportfolio', 'Olten SO', 'verkauft', '24 Wohnungen', 'references/sale-olten-24.jpg' ),
	array( '3.5-Zimmer-Wohnung', 'Zürich ZH', 'verkauft', '3.5 Zimmer', 'references/sale-zuerich-35.jpg' ),
	array( '3.5-Zimmer-Wohnung', 'Bubikon ZH', 'verkauft', '3.5 Zimmer', 'references/sale-bubikon-35.jpg' ),
	array( '2.5-Zimmer-Wohnung', 'Hinwil ZH', 'verkauft', '2.5 Zimmer', 'references/sale-hinwil-25.jpg' ),
	array( '4.5-Zimmer-Wohnung', 'Dällikon ZH', 'verkauft', '4.5 Zimmer', 'references/sale-daellikon-45.png' ),
	array( '4.5-Zimmer-Wohnung', 'Würenlos AG', 'vermietet', '4.5 Zimmer', 'references/rent-wuerenlos-45.jpg' ),
	array( '1.5-Zimmer-Wohnung', 'Zürich ZH', 'vermietet', '1.5 Zimmer', 'references/rent-zuerich-15.jpg' ),
	array( 'Zwei 4.5-Zimmer-Wohnungen', 'Aarburg AG', 'vermietet', '2 Wohnungen', 'references/rent-aarburg-45.png' ),
	array( '4.5-Zimmer-Wohnung', 'Reichenburg SZ', 'vermietet', '4.5 Zimmer', 'references/rent-reichenburg-45.jpg' ),
	array( '3.5-Zimmer-Wohnung', 'Rudolfstetten AG', 'vermietet', '3.5 Zimmer', 'references/rent-rudolfstetten-35.png' ),
	array( '4.5- & 3.5-Zimmer-Wohnungen', 'Altstetten ZH', 'vermietet', '2 Wohnungen', 'references/rent-altstetten.jpg' ),
	array( 'Attika-Maisonette-Terrassenhaus', 'Rieden SG', 'vermietet', '5.5 Zimmer', 'references/rent-rieden-attika.jpg' ),
	array( '5.5-Zimmer-Wohnung', 'Zürich ZH', 'vermietet', '5.5 Zimmer', 'references/rent-zuerich-55.jpg' ),
	array( '2.5- & 3.5-Zimmer-Wohnungen', 'Wohlen AG', 'vermietet', '2 Wohnungen', 'references/rent-wohlen-25-35.jpg' ),
	array( '3.5-Zimmer-Wohnung', 'Zürich ZH', 'vermietet', '3.5 Zimmer', 'references/rent-zuerich-35.jpg' ),
	array( '4.5-Zimmer-Wohnung', 'Wohlen AG', 'vermietet', '4.5 Zimmer', 'references/rent-wohlen-45.jpg' ),
	array( '4-Zimmer-Reihenhaus', 'Wohlen AG', 'vermietet', '4 Zimmer', 'references/rent-wohlen-reihenhaus.jpg' ),
	array( 'Gewerbefläche', 'Wohlen AG', 'vermietet', 'Gewerbe', 'references/rent-wohlen-gewerbe.jpg' ),
	array( '1.5-Zimmer-Wohnung', 'Opfikon ZH', 'vermietet', '1.5 Zimmer', 'references/rent-opfikon-15.jpg' ),
	array( 'Wohnliegenschaft', 'Bubendorf BL', 'verwaltung', '6 Wohnungen', 'references/manage-bubendorf-6.jpg' ),
	array( 'Wohn- und Geschäftsliegenschaft', '', 'verwaltung', '2 Ladenflächen · 6 Wohnungen', 'references/manage-shops-apartments.jpg' ),
	array( 'Wohnliegenschaft', 'Staad SG', 'verwaltung', '8 Wohnungen', 'references/manage-staad-8.jpg' ),
	array( 'Wohnliegenschaft', 'Hägglingen AG', 'verwaltung', '6 Wohnungen', 'references/manage-haegglingen-6.jpg' ),
	array( 'Wohnliegenschaft', 'Rheineck SG', 'verwaltung', '12 Wohnungen', 'references/manage-rheineck-12.jpg' ),
	array( 'Wohnliegenschaft', 'Glarus GL', 'verwaltung', '8 Wohnungen', 'references/manage-glarus-8.jpg' ),
	array( 'Wohnliegenschaft', 'Hägglingen AG', 'verwaltung', '8 Wohnungen', 'references/manage-haegglingen-8.png' ),
	array( 'Wohn- und Gewerbeliegenschaft', 'Schaffhausen SH', 'verwaltung', '16 Wohnungen · 2 Gewerbeflächen', 'references/manage-schaffhausen.png' ),
);

foreach ( $references as $index => $reference ) {
	list( $title, $location, $type, $detail, $image ) = $reference;

	$image_id = sh_import_image( $image, $title );

	$post_id = wp_insert_post( array(
		'post_type'   => 'reference',
		'post_title'  => $title,
		'post_status' => 'publish',
		'menu_order'  => $index + 1,
	), true );

	if ( is_wp_error( $post_id ) ) {
		continue;
	}

	wp_set_object_terms( $post_id, array( $type ), 'reference_type' );
	update_post_meta( $post_id, '_seehafen_location', $location );
	update_post_meta( $post_id, '_seehafen_detail', $detail );

	if ( $image_id ) {
		set_post_thumbnail( $post_id, $image_id );
	}
}

$log[] = 'references: ' . count( $references );

// 6. Team members.
$team = array(
	array( 'Eduard Laska', 'eduard-laska', 'EL', 'Geschäftsführer', 'Über 10 Jahre Erfahrung in der Immobilienbranche · Eidg. Fachausweis' ),
	array( 'Dorentina Laska', 'dorentina-laska', 'DL', 'Sachbearbeiterin Immobilien', 'Persönliche und zuverlässige Betreuung unserer Kundschaft' ),
	array( 'Jozefina Markaj', 'jozefina-markaj', 'JM', 'Vermarktung', 'Dipl. Innenarchitektin · Immobilien wirkungsvoll positioniert' ),
);

foreach ( $team as $index => $member ) {
	list( $name, $slug, $initials, $role, $bio ) = $member;

	wp_insert_post( array(
		'post_type'   => 'team_member',
		'post_title'  => $name,
		'post_name'   => $slug,
		'post_status' => 'publish',
		'menu_order'  => $index + 1,
		'meta_input'  => array(
			'_seehafen_initials' => $initials,
			'_seehafen_role'     => $role,
			'_seehafen_bio'      => $bio,
		),
	) );
}

$log[] = 'team: ' . count( $team );

// 7. Pages.
$pages = array(
	array( 'Start', 'start', '', '' ),
	array( 'Firma', 'firma', 'page-firma.php', '' ),
	array( 'Dienstleistungen', 'dienstleistungen', 'page-dienstleistungen.php', '' ),
	array( 'Angebote', 'angebote', 'page-angebote.php', '' ),
	array( 'Referenzen', 'referenzen', 'page-referenzen.php', '' ),
	array( 'Kontakt', 'kontakt', 'page-kontakt.php', '' ),
	array( 'Impressum', 'impressum', 'page-legal.php', '<h2>Unternehmensinformationen</h2><p><strong>Seehafen &amp; Partner Immobilien AG</strong><br />Bahnhofstrasse 4<br />6430 Schwyz<br />Schweiz</p><h2>Kontakt</h2><p>E-Mail: <a href="mailto:info@seehafen-immobilien.ch">info@seehafen-immobilien.ch</a></p><h2>Handelsregistereintrag</h2><p>Eingetragener Firmenname: Seehafen &amp; Partner Immobilien AG<br />Handelsregister des Kantons Schwyz<br />UID: CHE-437.125.709</p><h2>Haftungsausschluss</h2><p>Die Inhalte dieser Website werden mit grösster Sorgfalt erstellt und regelmässig geprüft. Dennoch übernimmt die Seehafen &amp; Partner Immobilien AG keine Gewähr für die Richtigkeit, Vollständigkeit und Aktualität der bereitgestellten Informationen.</p><p>Als Diensteanbieter sind wir gemäss den anwendbaren gesetzlichen Bestimmungen für eigene Inhalte auf diesen Seiten verantwortlich. Eine Verpflichtung zur Überwachung übermittelter oder gespeicherter fremder Informationen besteht nicht. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden diese Inhalte umgehend entfernt.</p><h2>Urheberrecht</h2><p>Die auf dieser Website veröffentlichten Inhalte und Werke unterliegen dem schweizerischen Urheberrecht. Jede Art der Vervielfältigung, Bearbeitung, Verbreitung oder sonstigen Verwertung ausserhalb der Grenzen des Urheberrechts bedarf der vorgängigen schriftlichen Zustimmung des jeweiligen Rechteinhabers.</p>' ),
	array( 'Datenschutz', 'datenschutz', 'page-legal.php', '<p>Der Schutz Ihrer persönlichen Daten ist der Seehafen &amp; Partner Immobilien AG ein wichtiges Anliegen. In dieser Datenschutzerklärung informieren wir Sie darüber, wie personenbezogene Daten auf dieser Website bearbeitet werden.</p><h2>Verantwortliche Stelle</h2><p>Verantwortlich für die Datenbearbeitung im Sinne des schweizerischen Datenschutzgesetzes (DSG) ist:</p><p><strong>Seehafen &amp; Partner Immobilien AG</strong><br />Bahnhofstrasse 4<br />6430 Schwyz<br />Schweiz<br />E-Mail: <a href="mailto:info@seehafen-immobilien.ch">info@seehafen-immobilien.ch</a></p><h2>Erhebung und Bearbeitung personenbezogener Daten</h2><p>Personenbezogene Daten werden erhoben, wenn Sie uns diese freiwillig mitteilen, beispielsweise bei der Kontaktaufnahme per E-Mail oder über ein Kontaktformular. Dabei kann es sich insbesondere um Name, E-Mail-Adresse, Telefonnummer oder weitere von Ihnen übermittelte Informationen handeln. Die Bearbeitung dieser Daten erfolgt ausschliesslich zum Zweck der Bearbeitung Ihrer Anfrage oder zur Kontaktaufnahme mit Ihnen.</p><h2>Zweck der Datenbearbeitung</h2><p>Die Bearbeitung personenbezogener Daten erfolgt zur Beantwortung von Anfragen, zur Erfüllung vertraglicher und vorvertraglicher Pflichten sowie zur Erbringung unserer Dienstleistungen im Bereich Immobilien.</p><h2>Weitergabe von Daten an Dritte</h2><p>Eine Weitergabe personenbezogener Daten an Dritte erfolgt nur, sofern dies zur Vertragserfüllung erforderlich ist, eine gesetzliche Verpflichtung besteht oder Sie ausdrücklich eingewilligt haben. Eine Übermittlung ins Ausland oder an Drittstaaten findet nicht statt.</p><h2>Datensicherheit</h2><p>Wir setzen angemessene technische und organisatorische Sicherheitsmassnahmen ein, um personenbezogene Daten vor unbefugtem Zugriff, Verlust, Missbrauch oder Manipulation zu schützen. Diese Massnahmen werden entsprechend der technologischen Entwicklung laufend angepasst.</p><h2>Cookies</h2><p>Diese Website verwendet Cookies, um die Funktionalität und Benutzerfreundlichkeit zu verbessern. Cookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden. Sie können die Verwendung von Cookies in den Einstellungen Ihres Browsers einschränken oder deaktivieren. Die Deaktivierung kann die Funktionalität der Website beeinträchtigen.</p><h2>Rechte der betroffenen Personen</h2><p>Sie haben im Rahmen der geltenden datenschutzrechtlichen Bestimmungen das Recht auf Auskunft über die zu Ihrer Person gespeicherten Daten sowie das Recht auf Berichtigung, Löschung oder Einschränkung der Bearbeitung. Anfragen richten Sie bitte an die oben genannte Kontaktadresse.</p><h2>Änderungen dieser Datenschutzerklärung</h2><p>Die Seehafen &amp; Partner Immobilien AG behält sich vor, diese Datenschutzerklärung jederzeit anzupassen, insbesondere bei Änderungen gesetzlicher Vorgaben oder bei Weiterentwicklungen der Website oder Dienstleistungen.</p>' ),
	array( 'AGB', 'agb', 'page-legal.php', '<h2>1. Geltungsbereich</h2><p>Diese AGB gelten für alle Dienstleistungen der Seehafen &amp; Partner Immobilien AG im Bereich Immobilienbewirtschaftung, Vermarktung, Beratung und verwandte Dienstleistungen.</p><h2>2. Vertragsabschluss</h2><p>Ein Vertrag kommt durch schriftliche Bestätigung des Auftrags durch die Seehafen &amp; Partner Immobilien AG zustande. Mündliche Nebenabreden bedürfen der schriftlichen Bestätigung.</p><h2>3. Leistungsumfang</h2><p>Der Umfang der zu erbringenden Leistungen ergibt sich aus dem jeweiligen Einzelvertrag. Die Seehafen &amp; Partner Immobilien AG erbringt ihre Leistungen mit der Sorgfalt eines ordentlichen Kaufmanns.</p><h2>4. Honorare und Zahlungsbedingungen</h2><p>Die Honorare werden im Einzelvertrag vereinbart. Rechnungen sind innert 30 Tagen nach Rechnungsstellung ohne Abzug zahlbar. Bei Zahlungsverzug werden Verzugszinsen von 5 % p. a. berechnet.</p><h2>5. Vertraulichkeit</h2><p>Die Seehafen &amp; Partner Immobilien AG verpflichtet sich, alle im Rahmen der Geschäftsbeziehung erlangten Informationen vertraulich zu behandeln.</p><h2>6. Haftung</h2><p>Die Haftung der Seehafen &amp; Partner Immobilien AG beschränkt sich auf Vorsatz und grobe Fahrlässigkeit. Eine weitergehende Haftung ist ausgeschlossen, soweit gesetzlich zulässig.</p><h2>7. Anwendbares Recht und Gerichtsstand</h2><p>Es gilt schweizerisches Recht. Ausschliesslicher Gerichtsstand ist Schwyz.</p>' ),
);

$page_ids = array();

foreach ( $pages as $index => $page ) {
	list( $title, $slug, $template, $content ) = $page;

	$post_id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_status'  => 'publish',
		'post_content' => $content,
		'menu_order'   => $index,
	), true );

	if ( is_wp_error( $post_id ) ) {
		continue;
	}

	if ( '' !== $template ) {
		update_post_meta( $post_id, '_wp_page_template', $template );
	}

	if ( 'Impressum' === $title ) {
		update_post_meta( $post_id, '_seehafen_seo_description', 'Impressum und Unternehmensinformationen der Seehafen & Partner Immobilien AG.' );
	}

	if ( 'Datenschutz' === $title ) {
		update_post_meta( $post_id, '_seehafen_seo_description', 'Datenschutzerklärung der Seehafen & Partner Immobilien AG.' );
	}

	if ( 'AGB' === $title ) {
		update_post_meta( $post_id, '_seehafen_seo_description', 'Allgemeine Geschäftsbedingungen der Seehafen & Partner Immobilien AG.' );
	}

	$page_ids[ $slug ] = $post_id;
}

$log[] = 'pages: ' . count( $pages );

// 8. Front page + permalinks.
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $page_ids['start'] );
update_option( 'page_for_posts', 0 );

// 9. Menus.
$menu_primary_id = wp_create_nav_menu( 'Hauptmenü' );
$menu_footer_id  = wp_create_nav_menu( 'Footer' );

if ( ! is_wp_error( $menu_primary_id ) ) {
	$uber_uns = wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Über uns',
		'menu-item-url'       => home_url( '/firma/' ),
		'menu-item-status'    => 'publish',
	) );

	wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Über uns',
		'menu-item-url'       => home_url( '/firma/#uber-uns' ),
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $uber_uns,
	) );

	wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Unser Team',
		'menu-item-url'       => home_url( '/firma/#team' ),
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $uber_uns,
	) );

	wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Werte & Arbeitsweise',
		'menu-item-url'       => home_url( '/firma/#werte' ),
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $uber_uns,
	) );

	$dienst = wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Dienstleistungen',
		'menu-item-url'       => home_url( '/dienstleistungen/' ),
		'menu-item-status'    => 'publish',
	) );

	foreach ( $primary_services as $service ) {
		wp_update_nav_menu_item( $menu_primary_id, 0, array(
			'menu-item-title'     => $service['title'],
			'menu-item-url'       => get_permalink( $service_ids[ $service['slug'] ] ),
			'menu-item-status'    => 'publish',
			'menu-item-parent-id' => $dienst,
		) );
	}

	$angebote = wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Angebote',
		'menu-item-url'       => home_url( '/angebote/' ),
		'menu-item-status'    => 'publish',
	) );

	wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Aktuelle Angebote',
		'menu-item-url'       => 'https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag',
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $angebote,
	) );

	wp_update_nav_menu_item( $menu_primary_id, 0, array(
		'menu-item-title'     => 'Referenzen',
		'menu-item-url'       => home_url( '/referenzen/' ),
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $angebote,
	) );

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_primary_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	$log[] = 'primary menu created';
}

if ( ! is_wp_error( $menu_footer_id ) ) {
	foreach ( array( 'Impressum', 'Datenschutz', 'AGB' ) as $legal_title ) {
		wp_update_nav_menu_item( $menu_footer_id, 0, array(
			'menu-item-title'     => $legal_title,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $page_ids[ strtolower( $legal_title ) ],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
		) );
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['footer'] = $menu_footer_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	$log[] = 'footer menu created';
}

// 10. Theme mods (site settings).
set_theme_mod( 'seehafen_phone_land', '+41 44 451 43 02' );
set_theme_mod( 'seehafen_phone_mobile', '+41 79 785 78 80' );
set_theme_mod( 'seehafen_email', 'info@seehafen-immobilien.ch' );
set_theme_mod( 'seehafen_homegate_url', 'https://www.homegate.ch/anbieter/h475138/seehafen-partner-immobilien-ag' );
set_theme_mod( 'seehafen_address_main', "Bahnhofstrasse 4\n6430 Schwyz" );
set_theme_mod( 'seehafen_address_branch', "Cheiblerrain 13\n5610 Wohlen" );
set_theme_mod( 'seehafen_opening_hours', 'Montag bis Freitag<br />08:00–12:00 · 13:30–17:00 Uhr' );
set_theme_mod( 'seehafen_footer_text', 'Persönliche Immobiliendienstleistungen mit Weitblick – in Schwyz, Wohlen und der ganzen Schweiz.' );

set_theme_mod( 'seehafen_process_steps', implode( "\n", array(
	'Erstgespräch||Wir besprechen Ihre Ziele, Anforderungen und Erwartungen in einem persönlichen Gespräch.',
	'Analyse||Wir analysieren Ihre Immobilie oder Ihren Bedarf und entwickeln eine massgeschneiderte Strategie.',
	'Umsetzung||Wir setzen die vereinbarten Massnahmen professionell, transparent und zuverlässig um.',
	'Partnerschaft||Wir begleiten Sie langfristig und stehen Ihnen als vertrauensvoller Partner zur Seite.',
) ) );

set_theme_mod( 'seehafen_values', implode( "\n", array(
	'Verlässlichkeit||Wir halten, was wir versprechen, und kommunizieren transparent.',
	'Persönliche Betreuung||Sie haben einen festen Ansprechpartner, der Ihre Ziele kennt.',
	'Fachkompetenz||Erfahrung und fundierte Marktkenntnis bilden die Basis unserer Arbeit.',
	'Nachhaltigkeit||Der langfristige Werterhalt Ihrer Immobilie steht im Mittelpunkt.',
) ) );

$log[] = 'theme mods set';

// 11. Activate theme.
wp_clean_themes_cache();
switch_theme( 'seehafen' );

// Flush rewrite rules.
flush_rewrite_rules();

$log[] = 'theme activated: seehafen';

echo 'MIGRATION DONE:' . "\n" . implode( "\n", $log ) . "\n";
