<?php

namespace ImportEntriesGravityForms\Queries;

class GetAllForms
{
	public static function get_all_not_trashed_forms(){
		$value = wp_cache_get( 'import_entries_gravity_forms_get_all_not_trashed_forms' );
		if ( false === $value ) {
			global $wpdb;
			$sql = $wpdb->prepare( "SELECT `id`, `title` FROM %i WHERE %i = %d", array(
				$wpdb->prefix.'gf_form',
				"is_trash",
				"0"
			));
			$value = $wpdb->get_results($sql);
			wp_cache_set( 'import_entries_gravity_forms_get_all_not_trashed_forms', $value );
		}
		return $value;
	}
}
