<?php
/**
 * Meta boxes for Seehafen CPTs.
 *
 * @package Seehafen_CPT
 */

/**
 * Registers and saves meta boxes for the Seehafen custom post types.
 */
class Seehafen_CPT_Meta {

	/**
	 * Meta field configuration, keyed by post type.
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->fields = array();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
	}

	/**
	 * Lazy field configuration (avoids __() before init).
	 *
	 * @return array
	 */
	private function get_fields() {
		if ( ! empty( $this->fields ) ) {
			return $this->fields;
		}

		$this->fields = array(
			'service'      => array(
				'_seehafen_lead'         => array(
					'label' => __( 'Lead', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_heading'      => array(
					'label' => __( 'Heading', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_copy'         => array(
					'label' => __( 'Copy', 'seehafen' ),
					'type'  => 'textarea',
				),
				'_seehafen_points'       => array(
					'label'       => __( 'Points (one per line)', 'seehafen' ),
					'type'        => 'textarea',
					'description' => __( 'Each line becomes one checklist item.', 'seehafen' ),
				),
				'_seehafen_hero_image'   => array(
					'label' => __( 'Hero Image URL', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_home_image'   => array(
					'label' => __( 'Home Card Image URL', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_home_text'    => array(
					'label' => __( 'Home Card Text', 'seehafen' ),
					'type'  => 'textarea',
				),
				'_seehafen_detail_image' => array(
					'label' => __( 'Detail Image URL', 'seehafen' ),
					'type'  => 'text',
				),
			),
			'reference'    => array(
				'_seehafen_location' => array(
					'label' => __( 'Location', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_detail'   => array(
					'label' => __( 'Detail (e.g. rooms / units)', 'seehafen' ),
					'type'  => 'text',
				),
			),
			'offer'        => array(
				'_seehafen_label'        => array(
					'label' => __( 'Label (e.g. Miete)', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_location'     => array(
					'label' => __( 'Location', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_price'        => array(
					'label' => __( 'Price (e.g. 1\'450.–)', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_rooms'        => array(
					'label' => __( 'Rooms', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_area'         => array(
					'label' => __( 'Area (e.g. 32 m²)', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_external_url' => array(
					'label' => __( 'External detail URL (Homegate)', 'seehafen' ),
					'type'  => 'url',
				),
			),
			'team_member'  => array(
				'_seehafen_initials' => array(
					'label' => __( 'Initials (avatar)', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_role'     => array(
					'label' => __( 'Role', 'seehafen' ),
					'type'  => 'text',
				),
				'_seehafen_bio'      => array(
					'label' => __( 'Bio', 'seehafen' ),
					'type'  => 'textarea',
				),
			),
		);

		return $this->fields;
	}

	/**
	 * Register meta boxes for each post type.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		$fields = $this->get_fields();

		foreach ( $fields as $post_type => $fields ) {
			if ( empty( $fields ) ) {
				continue;
			}

			add_meta_box(
				'seehafen_meta_box',
				__( 'Seehafen Details', 'seehafen' ),
				array( $this, 'render_meta_box' ),
				$post_type,
				'normal',
				'high',
				array( 'fields' => $fields )
			);
		}
	}

	/**
	 * Render a meta box.
	 *
	 * @param WP_Post $post Post object.
	 * @param array   $box  Meta box arguments.
	 *
	 * @return void
	 */
	public function render_meta_box( $post, $box ) {
		$fields = $box['args']['fields'];

		wp_nonce_field( 'seehafen_save_meta', 'seehafen_meta_nonce' );

		foreach ( $fields as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			?>
			<p>
				<label for="<?php echo esc_attr( $key ); ?>" style="display:block;font-weight:600;margin-bottom:4px;">
					<?php echo esc_html( $field['label'] ); ?>
				</label>
				<?php if ( 'textarea' === $field['type'] ) : ?>
					<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="5" style="width:100%;"><?php echo esc_textarea( $value ); ?></textarea>
				<?php else : ?>
					<input type="<?php echo esc_attr( 'url' === $field['type'] ? 'url' : 'text' ); ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" style="width:100%;" />
				<?php endif; ?>
				<?php if ( ! empty( $field['description'] ) ) : ?>
					<small style="color:#666;"><?php echo esc_html( $field['description'] ); ?></small>
				<?php endif; ?>
			</p>
			<?php
		}
	}

	/**
	 * Save meta box values.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public function save_meta_boxes( $post_id ) {
		if ( ! isset( $_POST['seehafen_meta_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['seehafen_meta_nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'seehafen_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$post_type = get_post_type( $post_id );

		$fields = $this->get_fields();

		if ( ! isset( $fields[ $post_type ] ) ) {
			return;
		}

		foreach ( $fields[ $post_type ] as $key => $field ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}

			$value = wp_unslash( $_POST[ $key ] );

			if ( 'url' === $field['type'] ) {
				$value = esc_url_raw( $value );
			} elseif ( 'textarea' === $field['type'] ) {
				$value = sanitize_textarea_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}

			update_post_meta( $post_id, $key, $value );
		}
	}
}
