<?php

namespace ImportEntriesGravityForms\Ajax;

use GFAPI;
use RGFormsModel;

class ImportEntriesGfTableData
{
	function __construct(){
		add_action( 'wp_ajax_import_entries_gf_table_data', array($this,'import_entries') );
	}
	function import_entries(){
		$verify   = wp_verify_nonce($_POST['import_entries_gf_table_data_wpnonce'],'import_entries_gf_table_data');
		if(!$verify){
			wp_send_json_error(
				array(
					"message" => __("You are not allowed to import entries!", "import-entries-for-gravity-forms")
				)
			);
		}
		$time_start = microtime( true );
		if(empty($_POST['data'])) {
			wp_send_json_error(
				array(
					"message" => __("Data is empty!", "import-entries-for-gravity-forms")
				)
			);
		}
		$data = json_decode(stripslashes($_POST['data']),true);
		foreach($data as $key => $value):
			$_POST[$key] = $value;
		endforeach;
		$offset             = (int)$data['offset'];
		$attach_id          = $data['attach_id'];
		$form_id            = $data['form_id'];
		$content_path       = get_attached_file($attach_id);
		$form               = RGFormsModel::get_form_meta( $form_id );
		$max_execution_time = apply_filters( 'gform_export_max_execution_time', 20, $form );
		$row = 0;
		if (($handle = fopen($content_path, "r")) !== FALSE) {
			while (($dataf = fgetcsv($handle)) !== FALSE) {
				$time_end       = microtime( true );
				$execution_time = ( $time_end - $time_start );
				if ( $execution_time >= $max_execution_time ) {
					fclose($handle);
					wp_send_json(
						array(
							'offset'           => $offset,
							'total_rows_found' => $data['total_rows_found'],
							'in_progress'      => true,
						)
					);
					exit();
				}
				if( $row  >= $offset ){
					$num = count($dataf);
					$input_values               = array();
					for ($c=0; $c < $num; $c++) {
						if(isset($data['fields'][$c]) && !empty($data['fields'][$c])){
							$input_values[$data['fields'][$c]] = $dataf[$c];
						}
					}
					$input_values['form_id'] = $form_id;
					GFAPI::add_entry($input_values);
					$offset++;
				}
				$row++;
			}
			fclose($handle);
			wp_delete_attachment($attach_id,true);
			wp_send_json(
				array( 'is_done'     => __("Import is successfully completed!","import-entries-for-gravity-forms") )
			);
			exit();
		}
		wp_send_json(
			array( 'is_done'     => __("Import is successfully completed!","import-entries-for-gravity-forms") )
		);
	}
}
