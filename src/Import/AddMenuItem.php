<?php

namespace ImportEntriesGravityForms\Import;

class AddMenuItem
{
	public static $id = "import_entries";
	public static $icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 512 512" version="1.1"><title>import</title><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Combined-Shape" fill="#000000" transform="translate(42.666667, 85.333333)"><path d="M405.333333,-1.42108547e-14 L405.333333,362.666667 L21.3333333,362.666667 L21.3333333,85.3333333 L64,85.3333333 L64,320 L362.666667,320 L362.666667,42.6666667 L277.333333,42.6666667 L277.333333,-1.42108547e-14 L405.333333,-1.42108547e-14 Z M128,-1.42108547e-14 C185.3601,-1.42108547e-14 232.145453,45.2758765 234.568117,102.039688 L234.666667,106.666667 L234.666,183.152 L283.581722,134.248389 L313.751611,164.418278 L213.333333,264.836556 L112.915055,164.418278 L143.084945,134.248389 L192,183.152 L192,106.666667 C192,72.5828078 165.356374,44.7219012 131.760486,42.7753108 L128,42.6666667 L7.10542736e-15,42.6666667 L7.10542736e-15,-1.42108547e-14 L128,-1.42108547e-14 Z"></path></g></g></svg>';
	function __construct(){
		add_filter( 'gform_export_menu', array($this, 'add_item'));
	}
	function add_item( $menu_items ){
		$menu_items[] = array(
			'name'  => self::$id,
			'label' => esc_html( 'Import Entries', 'import-entries-for-gravity-forms' ),
			'icon'  => self::$icon
		);
		return $menu_items;
	}
}
