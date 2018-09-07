<?php
/**
 * Sync (add) a document to the index
 *
 * @param \WP_Post $post
 * @param bool $from_save_transaction
 *
 * @return bool
 */
function acs_index_document( $post, $from_save_transaction = false ) {
	return acs_put_documents( array( acs_prepare_document( $post, $from_save_transaction ) ) );
}

/**
 * Delete a document from the index
 *
 * @param \WP_Post $post
 *
 * @return bool
 */
function acs_delete_document( $post ) {
	return acs_put_documents( array( array(
		'type' => 'delete',
		'id' => acs_get_document_key( $post->ID )
	) ) );
}

/**
 * Prepare document from post, with all index fields
 *
 * @param \WP_Post $post
 * @param bool $from_save_transaction
 *
 * @return array
 */
function acs_prepare_document( $post, $from_save_transaction = false ) {
    try {
        // Get settings option
        $settings = ACS::get_instance()->get_settings();

        // Get author
        $post_author = $post->post_author;
        $post_author_info = get_userdata($post_author);
        $post_author_name = $post_author_info->display_name;
        if ( empty( $post_author_name ) ) $post_author_name = '-';

        // Get default taxonomies
        $taxonomy_category = acs_get_term_list( $post->ID, 'category' );
        $taxonomy_tag = acs_get_term_list( $post->ID, 'post_tag' );

        // Get custom taxonomies
        $taxonomies_custom = array();
        $acs_schema_taxonomies = $settings->acs_schema_taxonomies;

        if ( ! empty ( $acs_schema_taxonomies ) ) {
            // If there are some custom taxonomies
            $acs_schema_taxonomies = explode( ACS::SEPARATOR, $acs_schema_taxonomies );

            // Loop custom taxonomies
            foreach ( $acs_schema_taxonomies as $acs_schema_taxonomy ) {
                // Get current post custom taxonomy values
                $taxonomy_custom = acs_get_term_list( $post->ID, $acs_schema_taxonomy );

                // Replace taxonomy slug "-" with "_" due to Amazon CloudSearch valid pattern rule
                $acs_schema_taxonomy_clean = str_replace( '-', '_', $acs_schema_taxonomy );

                $taxonomies_custom[ ACS::CUSTOM_TAXONOMY_PREFIX . $acs_schema_taxonomy_clean ] = $taxonomy_custom;
            }
        }

        // Get custom fields
        $fields_custom = array();
        $acs_schema_fields = $settings->acs_schema_fields;
        
        //Get Custom Field Parameters
        $acs_int_fields = array_map('trim',str_getcsv($settings->acs_schema_fields_int));
        $acs_double_fields = array_map('trim',str_getcsv($settings->acs_schema_fields_double));

        if ( ! empty ( $acs_schema_fields ) ) {
            // If there are some custom fields
            $acs_schema_fields = explode( ACS::SEPARATOR, $acs_schema_fields );

            // Loop custom fields
            foreach ( $acs_schema_fields as $acs_schema_field ) {
                // Get current post custom field values
                if ( $from_save_transaction && isset( $_POST[ $acs_schema_field ] ) ) {
                    // Read from request POST
                    $field_custom = $_POST[ $acs_schema_field ];
                }
                else {
                    // Read from post meta
                    $field_custom = get_post_meta( $post->ID, $acs_schema_field, true );
                }
                // Verfy & Convert INT and Double Fields
                if (isset($acs_int_fields) && in_array($acs_schema_field,$acs_int_fields)) {
                    $field_custom = intval($field_custom);
                } else if (isset($acs_double_fields) && in_array($acs_schema_field,$acs_double_fields)) {
                    $field_custom = doubleval($field_custom);
                }

                // Replace field slug "-" with "_" due to Amazon CloudSearch valid pattern rule
                $acs_schema_field_clean = str_replace( '-', '_', $acs_schema_field );

	            if ( ! empty( $field_custom ) ) $fields_custom[ ACS::CUSTOM_FIELD_PREFIX . $acs_schema_field_clean ] = $field_custom;
            }
        }

	    $post_image = '';
	    if ( ! empty( $settings->acs_schema_fields_custom_image_id ) && is_plugin_active( 'multiple-post-thumbnails/multi-post-thumbnails.php' ) ) {
		    // Retrieve post image from "Multiple Post Thumbnails" plugin if a image id is provided and plugin is active
		    $post_image = MultiPostThumbnails::get_post_thumbnail_url( $post->post_type, $settings->acs_schema_fields_custom_image_id, $post->ID );
	    }
	    else if ( ! empty( $settings->acs_schema_fields_image_size ) ) {
		    // Retrieve post image if a image size name is provided
		    $image_object = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $settings->acs_schema_fields_image_size );

		    if ( ! empty( $image_object ) && ! empty( $image_object[0] ) && $image_object[0] != '' ) {
			    // Found image, use it
			    $post_image = $image_object[0];
		    }
	    }

	    // Prepare fields array (merging default fields and custom fields/taxonomies
        $fields = array(
            'site_id' => acs_get_site_id(),
            'blog_id'=> acs_get_blog_id(),
            'id' => $post->ID,
            'post_type' => $post->post_type,
            'post_status' => $post->post_status,
            'post_format' => get_post_format( $post->ID ),
            'post_title' => $post->post_title,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_url' => get_permalink($post->ID),
            'post_image' => $post_image,
            'post_date' => strtotime( $post->post_date ),
            'post_date_gmt' => strtotime( $post->post_date_gmt ),
            'post_modified' => strtotime( $post->post_modified ),
            'post_modified_gmt' => strtotime( $post->post_modified_gmt ),
            'post_author' => $post_author,
            'post_author_name' => $post_author_name,
            'category' => $taxonomy_category,
            'tag' => $taxonomy_tag
        );
        if ( ! empty( $taxonomies_custom ) ) $fields = array_merge( $fields, $taxonomies_custom );
        if ( ! empty( $fields_custom ) ) $fields = array_merge( $fields, $fields_custom );

        // Manipulate standard fields (in your sub-theme add a filter "cloud_search_<POST_TYPE>_fields" that adds all necessary fields of your theme)
        $fields = apply_filters( "cloud_search_{$post->post_type}_fields", $fields, $post, $from_save_transaction );

        // Prepare doc object
        $doc = array(
            'type' => 'add',
            'id' => acs_get_document_key( $post->ID ),
            'fields' => $fields
        );

        return $doc;
    }
    catch ( \Exception $e ) {
        return null;
    }
}

/**
 * Put to index a list of documents
 *
 * @param array $docs
 *
 * @return bool
 */
function acs_put_documents( $docs ) {
    try {
        // Get client
        $client = acs_get_domain_client();
        $docs = json_encode( $docs );
        $docs = str_replace("\u000b","",$docs);
        // Upload documents
        $result = $client->uploadDocuments( array(
            'documents' => $docs,
            'contentType' => 'application/json'
        ) );

        // Return result
        return $result->getPath( 'status' ) == 'success';
    }
    catch ( \Exception $e ) {
	    error_log( __( 'Error uploading documents', ACS::PREFIX ) . ': ' . $e->getMessage() );

        return false;
    }
}

/**
 * Return taxonomy terms by post id
 *
 * @param $post_id
 * @param $taxonomy
 *
 * @return array
 */
function acs_get_term_list( $post_id, $taxonomy ) {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

	$terms = get_the_terms( $post_id, $taxonomy );
	$term_list = array();
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$term_list[] = $term->term_id . $settings->acs_schema_fields_separator . $term->name;
		}
	}
	return $term_list;
}
