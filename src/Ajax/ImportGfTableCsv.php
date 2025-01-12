<?php

namespace ImportEntriesGravityForms\Ajax;

use ImportEntriesGravityForms\GfFields;

class ImportGfTableCsv
{
	function __construct(){
		add_action( 'wp_ajax_import_gf_table_csv', array($this,'import') );
	}
	function import(){
		$verify     = wp_verify_nonce($_POST['import_gf_table_csv_wpnonce'],'import_gf_table_csv');
		if(!$verify){
			wp_send_json_error(
				array(
					"message"=> esc_html("You are not allowed to import files!")
				)
			);
			exit();
		}
		$attach_id = $_POST['csv_file'];
		$form_id   = $_POST['form_id'];
		if(empty($form_id)){
			wp_send_json_error(array(
				'message' => esc_html("Please select form!", "import-entries-for-gravity-forms")
			));
			exit();
		}
		if(empty($attach_id)){
			wp_send_json_error(array(
				'message' => esc_html("Please select file from your library!", "import-entries-for-gravity-forms")
			));
			exit();
		}
		$content_path = get_attached_file($attach_id);
		$filetype = wp_check_filetype($content_path);
		if( str_contains($filetype['ext'], 'csv')  == false){
			wp_send_json_error(array(
				'message'  => esc_html("File format not supported! Import csv file only!", "import-entries-for-gravity-forms")
			));
			exit();
		}
		$data_out = array(
			'fields'     => (new GfFields)->get(),
			'form_id'    => $form_id,
			'offset'     => 1,
			'total_rows_found' => 0,
			'rows'       => []
		);
		$data_out['attach_id'] = $attach_id;
		$row_count = 0;
		if (($handle = fopen($content_path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle)) !== FALSE) {
				if($row_count == 0){
					$num = count($data);
					for ($c=0; $c < $num; $c++) {
						$data_out['rows'][$row_count][$c] = $data[$c];
					}
				}
				$row_count++;
			}
			$data_out['total_rows_found'] = $row_count;
			fclose($handle);
		}
		wp_send_json($data_out);
		exit();
	}
}
