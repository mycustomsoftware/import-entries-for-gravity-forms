<?php

namespace ImportEntriesGravityForms;

use GFCommon;
use GFExport;
use RGFormsModel;

class GfFields
{
	private $fields = array();
	function __construct(){
		$this->init();
	}
	public function get(){
		return $this->fields;
	}

	private function init()
	{
		$form_id = intval( $_POST['form_id'] );
		if( $form_id == 0 ) {
			return;
		}
		$form    = RGFormsModel::get_form_meta( $form_id );
		if(empty($form)){
			return;
		}
		$form    = gf_apply_filters( array( 'gform_form_export_page', $form_id ), $form );
		$form    = GFExport::add_default_export_fields( $form );
		if ( is_array( $form['fields'] )  && !empty($form['fields'])) {
			foreach ( $form['fields'] as $field ) {
				$inputs = $field->get_entry_inputs();
				if ( empty( $inputs ) ) {
					continue;
				}
				if ( is_array( $inputs ) ) {
					foreach ( $inputs as $input ) {
						$this->fields[] = array( $input['id'], GFCommon::get_label( $field, $input['id'] ) );
					}
				} else if ( ! $field->displayOnly ) {
					$this->fields[] = array( $field->id, GFCommon::get_label( $field ) );
				}
			}
		}
	}
}
