<?php

namespace ImportEntriesGravityForms;

class LoadTextDomain
{
	function __construct(){
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_filter( 'load_textdomain_mofile', array( $this, 'load_textdomain_mofile' ), 10, 2 );
	}
	function load_textdomain_mofile($mofile, $domain){
		if ( IMPORT_ENTRIES_GRAVITY_FORMS_SLUG === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
			$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
			$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( IMPORT_ENTRIES_GRAVITY_FORMS_PATH_FILE ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
		}
		return $mofile;
	}
	function load_plugin_textdomain(){
		load_plugin_textdomain( IMPORT_ENTRIES_GRAVITY_FORMS_SLUG, false, dirname( plugin_basename( IMPORT_ENTRIES_GRAVITY_FORMS_PATH_FILE ) ) . '/languages' );
	}
}
