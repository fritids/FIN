<?php
/**
 * AppThemes CSV Importer
 *
 * @package Framework
 * @subpackage Importer
 */

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require_once $class_wp_importer;
}

/**
 * Convert a comma separated file into an associated array.
 * The first row should contain the array keys.
 */
function app_csv_to_array( $file = '', $delimiter=',' ) {
	if( ! file_exists( $file ) || !is_readable( $file ) )
		return false;

	$header = NULL;
	$data = array();
	if ( false !== $handle = fopen( $file, 'r' ) ) {
		while ( false !== $row = fgetcsv( $handle, 1000, $delimiter ) ) {
			if( $header )
				$data[] = array_combine($header, $row);
			else
				$header = $row;
		}
		fclose( $handle );
	}
	return $data;
}

class APP_Importer extends WP_Importer {
	var $post_type;
	var $fields;
	var $custom_fields;
	var $taxonomies;
	var $tax_meta;

	/*
	 * Args can have 3 elements:
	 * 'taxonomies' => array( 'valid', 'taxonomies' ),
	 * 'custom_fields' => array( 'csv_key' => 'internal_key'
	 *			     'csv_key' => array( 'internal_key' => 'key',
	 *						 'default' => 'value' )
	 *			    ),
	 * 'tax_meta' => array( array( 'tax' => array( 'csv_key' => 'tax_key' ))
	 *
	 */
	public function __construct( $post_type = 'post', $fields, $args = '' ) {
		$this->post_type = $post_type;
		$this->fields = $fields;
		$this->taxonomies = $args['taxonomies'];
		$this->tax_meta = $args['tax_meta'];

		// Parse Custom Fields for default values
		$this->custom_fields = array();
		$custom_fields = $args['custom_fields'];
		foreach ($custom_fields as $csv_key => $data){

		    if( is_array( $data ) ){
				$this->custom_fields[ $csv_key ] = $data;
		    }else{
				$this->custom_fields[ $csv_key ] = array(
								"internal_key" => $data,
								"default" => ""
				);
		    }
		}
	}

	/**
	 * Display import page title
	 */
	function header() {
		echo '<div class="wrap">';
		screen_icon( 'tools' );
		echo '<h2>' . __( 'Import CSV', 'appthemes' ) . '</h2>';

	}

	/**
	 * Close div.wrap
	 */
	function footer() {
		echo '</div>';
	}

	/**
	 * Display introductory text and file upload form
	 */
	function greet() {
		echo '<div class="narrow">';
		echo '<p>'.__( 'Below you will find the AppThemes import tool which allows you to import items from other systems via a .csv (comma-separated values) document. This is usually done with an Excel spreadsheet saved as a .csv. The formatting of the .csv file must be mapped correctly otherwise the import tool will not work. Use the .csv templates found in the "examples" theme folder.', 'appthemes' ).'</p>';
		echo '<p>'.__( 'Choose a CSV file to upload, then click Upload file and import.', 'appthemes' ).'</p>';
		wp_import_upload_form( 'admin.php?page=csv-importer&amp;step=1' );
		echo '</div>';
	}

	/**
	 * Registered callback function for the WordPress Importer
	 */
	function dispatch() {
		$this->header();

		$step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step'];
		switch ( $step ) {
			case 0:
				$this->greet();
				break;
			case 1:
				check_admin_referer( 'import-upload' );
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
				break;
		}

		$this->footer();
	}

	/**
	 *
	 */
	function import() {
		$file = wp_import_handle_upload();

		if ( isset( $file['error'] ) ) {
			echo '<p><strong>' . __( 'Sorry, there has been an error.', 'appthemes' ) . '</strong><br />';
			echo esc_html( $file['error'] ) . '</p>';
			return false;
		}

		$this->process( $file['file'] );

		echo '<h3>';
		printf( __('All done. <a href="%s">Have fun!</a>', 'appthemes' ), home_url() );
		echo '</h3>';
	}

	/**
	 *
	 */
	function process( $file ) {
		$rows = app_csv_to_array( $file );
		$posts = array();
		$tax_meta = array();

		foreach( $rows as $row ) {
		    $post = array( 'post_type' => $this->post_type );
		    $tax_input = array();
		    $post_meta = array();
		    $custom_fields_added = array();

		    foreach( $row as $key => $val ) {

				if ( array_key_exists( $key, $this->custom_fields ) ) {
				    $post_meta[ $this->custom_fields[ $key ]['internal_key'] ] = $val;
				    $custom_fields_added[] = $key;
				} else if ( in_array( $key, $this->taxonomies ) ) {
				    $tax_input[ $key ] = explode( ', ', $val );
				} else if ( array_key_exists( $key, $this->fields ) ) {
				    $post[ $this->fields[ $key ] ] = $val;
				} else {
				    foreach( $this->tax_meta as $tax => $fields ) {
						if ( array_key_exists( $key, $fields ) ) {
						    $tax_meta[ $tax ][ $tax_input[ $tax ][0] ][ $this->tax_meta[ $tax ][ $key ] ] = $val;
						}
				    }
				}

		    }

		    // Loop through each custom field and make sure it was added
		    foreach($this->custom_fields as $csv_key => $data){

				if( !in_array($csv_key, $custom_fields_added) ){
	
				    // Add default key if it is defined
				    if( isset($data['default']) ){
						$post_meta[ $data['internal_key'] ] = $data['default'];
				    }
				}
		    }

		    $post['tax_input'] = $tax_input;
		    $post['post_meta'] = $post_meta;
		    $posts[] = $post;
		}

		foreach( $tax_meta as $tax => $terms ) {
			foreach( $terms as $term => $meta_data ) {
				if ( ! ( $t = term_exists( $term, $tax ) ) )
					$t = wp_insert_term( $term, $tax );

				foreach( $meta_data as $meta_key => $meta_value ) {
					if ( 'desc' == substr( $meta_key, -4 ) )
						wp_update_term( $t['term_id'], $tax, array( 'description' => sanitize_text_field( $meta_value ) ) );
					else if ( function_exists( 'update_metadata' ) )
						update_metadata( $tax, $t['term_id'], $meta_key, $meta_value );
				}
			}
		}

		foreach( $posts as $post ) {
			foreach( $post['tax_input'] as $tax => $terms ) {
				foreach( $terms as $term ) {
					if ( ! ( $t = term_exists( $term, $tax ) ) )
						$t = wp_insert_term( $term, $tax );
					$_terms[] = $t['term_id'];
				}
				$post['tax_input'][ $tax ] = $_terms;
			}

			$post_meta = $post['post_meta'];
			unset( $post['post_meta'] );

			$post_id = wp_insert_post( $post );

			foreach( $post_meta as $meta_key => $meta_value )
				add_post_meta( $post_id, $meta_key, $meta_value, true );
		}
	}
}
?>