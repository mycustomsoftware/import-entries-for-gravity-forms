<?php

namespace ImportEntriesGravityForms\Import;

use GFExport;

class AddPage
{
	function __construct(){
		add_action( 'gform_export_page_import_entries', array($this, 'display') );
	}
	function display(){
		GFExport::page_header();
		require_once GFIMPORTPATH."/view.php";
		GFExport::page_footer();
	}
}
