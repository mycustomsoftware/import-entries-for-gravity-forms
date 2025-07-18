<?php

namespace ImportEntriesGravityForms\Admin;

class EnqueueScripts
{
	function __construct(){
		add_action('admin_enqueue_scripts',array($this,'enqueue_scripts'));
	}
	function enqueue_scripts(){
		$min = IMPORT_ENTRIES_GRAVITY_FORMS_PATH_ENV == "production" ? ".min" : "";
		wp_enqueue_script("import-entries-for-gravity-forms",plugins_url("assets/js/import-entries-for-gravity-forms{$min}.js",IMPORT_ENTRIES_GRAVITY_FORMS_PATH_FILE),array(),IMPORT_ENTRIES_GRAVITY_FORMS_VER,true);
	}
}
