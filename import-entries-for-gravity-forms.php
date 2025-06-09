<?php
/**
 * Plugin Name: Import entries for Gravity Forms
 * Plugin URI: https://wordpress.org/plugins/import-entries-for-gravity-forms
 * Description: Simplify your workflow with Import Entries for Gravity Forms, the essential tool for importing data into your Gravity Forms effortlessly. Whether you’re migrating data from another system, updating existing forms, or consolidating entries, this plugin saves you time and effort.
 * Version: 1.0.2
 * Author:      My Custom Software
 * Author URI: https://github.com/mycustomsoftware
 *  License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.4
 * Text Domain: import-entries-for-gravity-forms
 * Domain Path: /languages
 **/
if(!defined('ABSPATH')) {
	exit;
}
if(!defined('GFIMPORTVERSION')){
	define("GFIMPORTVERSION","1.0.2");
}
if(!defined('GFIMPORTPATH')){
	define("GFIMPORTPATH",__DIR__);
}
if(!defined('GFIMPORTENV')){
	define("GFIMPORTENV",'production');
}
if(!defined('GFIMPORTFILE')){
	define("GFIMPORTFILE",__FILE__);
}
if(!defined('GFIMPORTSLUG')){
	define("GFIMPORTSLUG",'import-entries-for-gravity-forms');
}
require_once GFIMPORTPATH.'/vendor/autoload.php';
use ImportEntriesGravityForms\Admin\EnqueueScripts;
use ImportEntriesGravityForms\Ajax\ImportEntriesGfTableData;
use ImportEntriesGravityForms\Ajax\ImportGfTableCsv;
use ImportEntriesGravityForms\Import\AddMenuItem;
use ImportEntriesGravityForms\Import\AddPage;
use ImportEntriesGravityForms\LoadTextDomain;

class GFImportMain {
	public $slug = GFIMPORTSLUG;
	function __construct(){
		new LoadTextDomain();
		add_action( 'install_plugins_pre_plugin-information', array( $this, 'add_plugin_info_popup_content' ), 9 );
		add_filter('plugin_row_meta', array($this,'add_view_details_link'), 10, 2);
		new ImportGfTableCsv();
		new ImportEntriesGfTableData();
		if(is_admin()){
			new AddMenuItem();
			new AddPage();
		}
		new EnqueueScripts();
	}
	function add_plugin_info_popup_content() {
		if ( sanitize_key($_REQUEST['plugin']) != $this->slug ) {
			return;
		}
		require_once __DIR__ . '/README.html';
		exit;
	}
	function add_view_details_link($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$plugin_links = array(
				wp_kses_post(
					sprintf(
						'<a href="%s" class="thickbox open-plugin-details-modal" title="%s">%s</a>',
						self_admin_url('plugin-install.php?tab=plugin-information&plugin='.$this->slug.'&TB_iframe=true&width=772&height=450'),
						esc_html("View details", 'import-entries-for-gravity-forms'),
						esc_html("View details", 'import-entries-for-gravity-forms'),
					)
				),
				wp_kses_post(
					sprintf(
						'<a href="%s" title="%s">%s</a>',
						self_admin_url('admin.php?page=gf_export&subview=import_entries'),
						esc_html("Import entries", 'import-entries-for-gravity-forms'),
						esc_html("Import entries", 'import-entries-for-gravity-forms')
					)
				)
			);
			$links = array_merge($links, $plugin_links);
		}
		return $links;
	}

}
new GFImportMain();
