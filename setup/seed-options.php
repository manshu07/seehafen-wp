<?php
/**
 * One-time: store additional services, process, values in options; add home texts to services.
 * Run: wp eval-file /var/www/html/wp-content/seed-options.php --allow-root
 */

$data = json_decode( file_get_contents( '/tmp/seed-data.json' ), true );
if ( ! $data ) {
	wp_die( 'seed-data.json missing' );
}

update_option( 'seehafen_additional_services', $data['additionalServices'] );
update_option( 'seehafen_process', $data['process'] );

$values = array(
	array( 'Verlässlichkeit', 'Wir halten, was wir versprechen, und kommunizieren transparent.' ),
	array( 'Persönliche Betreuung', 'Sie haben einen festen Ansprechpartner, der Ihre Ziele kennt.' ),
	array( 'Fachkompetenz', 'Erfahrung und fundierte Marktkenntnis bilden die Basis unserer Arbeit.' ),
	array( 'Nachhaltigkeit', 'Der langfristige Werterhalt Ihrer Immobilie steht im Mittelpunkt.' ),
);
update_option( 'seehafen_values', $values );

// Home service card texts (from homeServices, keyed by service title).
$home_texts = array();
foreach ( $data['homeServices'] as $hs ) {
	$home_texts[ $hs['title'] ] = $hs['text'];
}

$services = get_posts( array( 'post_type' => 'service', 'posts_per_page' => 10 ) );
foreach ( $services as $s ) {
	$title = get_the_title( $s->ID );
	if ( isset( $home_texts[ $title ] ) ) {
		update_post_meta( $s->ID, 'seehafen_home_text', $home_texts[ $title ] );
	}
}

echo 'options + home texts seeded' . PHP_EOL;
