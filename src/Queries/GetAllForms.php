<?php

namespace ImportEntriesGravityForms\Queries;

class GetAllForms
{
	public static function get_all_not_trashed_forms(){
		$value = wp_cache_get( 'import_entries_gravity_forms_get_all_not_trashed_forms' );
		if ( false === $value ) {
			global $wpdb;
			$value = $wpdb->get_results($wpdb->prepare( "SELECT `id`, `title` FROM %i WHERE %s = %d", array(
				$wpdb->prefix.'gf_form',
				"is_trash",
				0
			)));
			wp_cache_set( 'import_entries_gravity_forms_get_all_not_trashed_forms', $value );
		}
		return $value;
	}
}
