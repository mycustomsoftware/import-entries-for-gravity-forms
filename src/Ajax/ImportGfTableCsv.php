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
					"message"=> __("You are not allowed to import files!")
				)
			);
			exit();
		}
		$content = null;
		$time_start = microtime( true );
		$tmp_name  = $_FILES['csv_file']['tmp_name'];
//		Check file format
		$file_data_info = wp_check_filetype($tmp_name);
		$filetype = $_FILES['csv_file']['type'];
		if( str_contains($filetype, 'csv')  == false){
			wp_send_json_error(array(
				'message'  => __("File format not supported! Import csv file only!", "import-entries-for-gravity-forms"),
				'fileinfo' => $_FILES['csv_file']
			));
			exit();
		}
		if(isset($_FILES['csv_file'])){
			$content    = file_get_contents($_FILES['csv_file']['tmp_name']);
			$file_info  = wp_upload_bits($time_start.'.csv', null, $content);
		}
//		check if file is empty
		if(empty($content)){
			wp_send_json_error(array(
				'message' => __("File can not be empty!", "import-entries-for-gravity-forms")
			));
			exit();
		}
		$form_id = $_POST['form_id'];
		$data_out = array(
			'fields'     => (new GfFields)->get(),
			'filename'   => (string)$time_start,
			'form_id'    => $form_id,
			'offset'     => 1,
			'total_rows_found' => 0,
			'rows'       => []
		);
		if(isset($file_info['file'])){
			$content_path      = $file_info['file'];
		}
		if(empty($content_path)){
			wp_send_json_error(array(
				'message' => __("File can not be empty!", "import-entries-for-gravity-forms")
			));
			exit();
		}
		$filename          = pathinfo($content_path)['basename'];
		$attachment = array(
			'post_mime_type' => $filetype,
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $content_path );
		$data_out['attach_id'] = $attach_id;
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $content_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );
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
