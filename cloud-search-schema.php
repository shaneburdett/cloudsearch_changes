<?php
/**
 * Define index fields
 *
 * @param bool|false $only_array_keys
 *
 * @return array
 */
function acs_get_index_fields( $only_array_keys = false ) {
	// Get default index fields
	$default_fields = acs_get_default_index_fields();

	// Get custom index fields
	$custom_fields = acs_get_custom_index_fields();

	// Merge fields array
	$fields = array_merge( $default_fields, $custom_fields );

	if ( $only_array_keys ) {
		// Return only field array keys
		return array_keys($fields);
	}

	return $fields;
}

/**
 * Define default index fields
 *
 * @return array
 */
function acs_get_default_index_fields() {
	// Define default index fields
	$fields = array(
		'site_id' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'blog_id' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'id' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_type' => array(
			'type' => 'literal',
			'option_key' => 'LiteralOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_status' => array(
			'type' => 'literal',
			'option_key' => 'LiteralOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_format' => array(
			'type' => 'literal',
			'option_key' => 'LiteralOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_title' => array(
			'type' => 'text',
			'option_key' => 'TextOptions',
			'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true,
				'HighlightEnabled' => true,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
		'post_content' => array(
			'type' => 'text',
			'option_key' => 'TextOptions',
			'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => false,
				'HighlightEnabled' => true,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
		'post_excerpt' => array(
			'type' => 'text',
			'option_key' => 'TextOptions',
			'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => false,
				'HighlightEnabled' => true,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
		'post_url' => array(
			'type' => 'text',
			'option_key' => 'TextOptions',
			'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => false,
				'ReturnEnabled' => true,
				'SortEnabled' => false,
				'HighlightEnabled' => false,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
        'post_image' => array(
            'type' => 'text',
            'option_key' => 'TextOptions',
            'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => false,
                'ReturnEnabled' => true,
                'SortEnabled' => false,
                'HighlightEnabled' => false,
                'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
            )
        ),
		'post_date' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_date_gmt' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_modified' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_modified_gmt' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_author' => array(
			'type' => 'int',
			'option_key' => 'IntOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true
			)
		),
		'post_author_name' => array(
			'type' => 'text',
			'option_key' => 'TextOptions',
			'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'SortEnabled' => true,
				'HighlightEnabled' => true,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
		'post_extra' => array(
			'type' => 'text',
			'option_key' => 'TextOptions',
			'option_value' => array(
				'FacetEnabled' => false,
				'SearchEnabled' => false,
				'ReturnEnabled' => true,
				'SortEnabled' => false,
				'HighlightEnabled' => false,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
		'category' => array(
			'type' => 'text-array',
			'option_key' => 'TextArrayOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'HighlightEnabled' => false,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		),
		'tag' => array(
			'type' => 'text-array',
			'option_key' => 'TextArrayOptions',
			'option_value' => array(
				'FacetEnabled' => true,
				'SearchEnabled' => true,
				'ReturnEnabled' => true,
				'HighlightEnabled' => false,
				'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
			)
		)
	);

	return $fields;
}

/**
 * Define custom index fields
 *
 * @return array
 */
function acs_get_custom_index_fields() {
	$fields = array();

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Define custom index fields (adding custom fields)
	$acs_schema_fields = $settings->acs_schema_fields;
	
	//Get Custom Field Parameters
    $acs_int_fields = array_map('trim',str_getcsv(str_replace( '-', '_',$settings->acs_schema_fields_int)));
    $acs_double_fields = array_map('trim',str_getcsv(str_replace( '-', '_',$settings->acs_schema_fields_double)));
    $acs_sortable_fields = array_map('trim',str_getcsv(str_replace( '-', '_',$settings->acs_schema_fields_sortable)));
	
	if ( ! empty ( $acs_schema_fields ) ) {
		// If there are some custom fields
		$acs_schema_fields = explode( ACS::SEPARATOR, $acs_schema_fields );

		// Loop custom fields
		foreach ( $acs_schema_fields as $acs_schema_field ) {
			// Replace field slug "-" with "_" due to Amazon CloudSearch valid pattern rule
			$acs_schema_field_clean = str_replace( '-', '_', $acs_schema_field );
            
            // Check if Field is INT or Double
            if (isset($acs_int_fields) && in_array($acs_schema_field_clean,$acs_int_fields)) {
                $acs_option_key_field = 'IntOptions';
            } else if (isset($acs_double_fields) && in_array($acs_schema_field_clean,$acs_double_fields)) {
                $acs_option_key_field = 'DoubleOptions';
            } else {
                $acs_option_key_field = 'TextOptions';
            }
            // Check if field is sortable
            
            $acs_sort_enabled_field = (isset($acs_sortable_fields) && in_array($acs_schema_field,$acs_sortable_fields)) ? true : false;
            
			// By default all schema fields are indexed as a text string and they are not enabled for facet, highlight or sort
			$fields[ ACS::CUSTOM_FIELD_PREFIX . $acs_schema_field_clean ] = array(
				'type' => 'text',
				'option_key' => $acs_option_key_field,
				'option_value' => array(
					'FacetEnabled' => false,
					'SearchEnabled' => true,
					'ReturnEnabled' => true,
					'SortEnabled' => $acs_sort_enabled_field,
					'HighlightEnabled' => false,
					'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
				)
			);
		}
	}

	// Define custom index taxonomies (adding custom taxonomies)
	$acs_schema_taxonomies = $settings->acs_schema_taxonomies;

	if ( ! empty ( $acs_schema_taxonomies ) ) {
		// If there are some custom taxonomies
		$acs_schema_taxonomies = explode( ACS::SEPARATOR, $acs_schema_taxonomies );

		// Loop custom taxonomies
		foreach ( $acs_schema_taxonomies as $acs_schema_taxonomy ) {
            // Replace taxonomy slug "-" with "_" due to Amazon CloudSearch valid pattern rule
            $acs_schema_taxonomy_clean = str_replace( '-', '_', $acs_schema_taxonomy );

			// All schema taxonomies are indexed as a text array and there are not enabled for facet, highlight and sort
			$fields[ ACS::CUSTOM_TAXONOMY_PREFIX . $acs_schema_taxonomy_clean ] = array(
				'type' => 'text-array',
				'option_key' => 'TextArrayOptions',
				'option_value' => array(
					'FacetEnabled' => false,
					'SearchEnabled' => true,
					'ReturnEnabled' => true,
					'SortEnabled' => false,
					'HighlightEnabled' => false,
					'AnalysisScheme' => ACS::ANALYSIS_SCHEMA
				)
			);
		}
	}

	return $fields;
}
