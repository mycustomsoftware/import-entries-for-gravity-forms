<?php
/**
 * Plugin Name: Import entries for Gravity Forms
 * Plugin URI: https://wordpress.org/plugins/import-entries-for-gravity-forms
 * Description:
 * Version: 1.0.0
 * Author:      My Custom Software
 * Author URI: https://github.com/mycustomsoftware
 *  License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.4
 **/
if(!defined('GFIMPORTPATH')){
	define("GFIMPORTPATH",__DIR__);
}
if(!defined('GFIMPORTENV')){
	define("GFIMPORTENV",'production');
}
if(!defined('GFIMPORTFILE')){
	define("GFIMPORTFILE",__FILE__);
}
require_once GFIMPORTPATH.'/vendor/autoload.php';
use ImportEntriesGravityForms\Admin\EnqueueScripts;
use ImportEntriesGravityForms\Ajax\ImportEntriesGfTableData;
use ImportEntriesGravityForms\Ajax\ImportGfTableCsv;
use ImportEntriesGravityForms\Import\AddMenuItem;
use ImportEntriesGravityForms\Import\AddPage;
class GFImportMain {
	function __construct(){
		new ImportGfTableCsv();
		new ImportEntriesGfTableData();
		if(is_admin()){
			new AddMenuItem();
			new AddPage();
		}
		new EnqueueScripts();
	}

}
new GFImportMain();
