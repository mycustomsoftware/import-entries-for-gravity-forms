<?php
if(!defined('ABSPATH')) {
	exit;
}
global $wpdb;
$forms = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gf_form");
?>
<div class="gform-settings-panel gform-settings-panel--full">
    <header class="gform-settings-panel__header">
        <legend class="gform-settings-panel__title">Import Entries</legend>
    </header>
    <form class="gform-settings-panel__content" id="import_csv" enctype="multipart/form-data">
        <div class="gform-settings-description">
            Select a form below to import entries. Once you have selected a form you may upload a CSV file and select the fields you would like to associate with the entries.
            When you click the upload button below, Gravity Forms will import entries to the selected form.
        </div>
        <table class="form-table">
            <tbody>
            <tr valign="top">
	            <?php if(!empty($forms)): ?>
                <th scope="row">
                    <label for="import_field_container_list">Select a Form</label>
                    <button onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_import_select_form" aria-label="<strong>Import Entries to selected Form</strong>Select the form you would like to import entry data. You may only import data from one form at a time.">
                        <i class="gform-icon gform-icon--question-mark" aria-hidden="true"></i>
                    </button>
                </th>
                <td>
                    <select id='import_field_container_list' name='form_id'>
                        <option value=''>Select form</option>
		                <?php foreach($forms as $form):
                            echo "<option value='{$form->id}'>{$form->title}</option>";
		                endforeach; ?>
                    </select>
                </td>
	            <?php else: ?>
                <th scope="row" colspan="2" style="text-align:center;">
                    <h2>No forms found!</h2> <a class="button gform-add-new-form primary add-new-h2" href="<?php echo admin_url('admin.php?page=gf_new_form'); ?>">Add New</a>
                </th>
                <?php endif; ?>
            </tr>
            <tr id="import_field_container" valign="top" style="display: none;">
                <th scope="row">
                    <label for="csv_file">Select File</label>
                    <button onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_import_select_fields" aria-label="Select CSV file to import">
                        <i class="gform-icon gform-icon--question-mark" aria-hidden="true"></i>
                    </button>
                </th>
                <td>
                    <input type="hidden" name="action_import" value="import_entries_gf_table_data">
	                <?php wp_nonce_field('import_entries_gf_table_data','import_entries_gf_table_data_wpnonce'); ?>
                    <input type="file" name="csv_file" id="csv_file">
                    <br><br>
                    <button id="submit_button" style="display:none;" type="button" class="button large primary">Import File</button>
                    <ul>
                        <li>
                            <ul id="imported_entries_list"></ul>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr valign="top" style="display: none;" id="import_form_list_row">
                <th scope="row">
                    <label>Select Form fields</label>
                    <button onclick="return false;" onkeypress="return false;" class="gf_tooltip tooltip tooltip_export_select_forms" aria-label="<strong>Select Form fields</strong> associate entries with a form fields">
                        <i class="gform-icon gform-icon--question-mark" aria-hidden="true"></i>
                    </button>
                </th>
                <td> <ul id="import_form_list"></ul> </td>
            </tr>
            <tr id="start_import_field_container" valign="top" style="display: none;">
                <th scope="row">
                    <label for="submit_button_import">Start import</label>
                </th>
                <td>
                    <textarea name="import_data" style="display:none;"></textarea>
                    <input type="hidden" name="action" value="import_gf_table_csv">
		            <?php wp_nonce_field('import_gf_table_csv','import_gf_table_csv_wpnonce'); ?>
                    <button id="back_button" type="button" class="button large white">Cancel</button>
                    <button id="submit_button_import" type="button" class="button large primary">Import data</button>
                    <ul>
                        <li id="import_submit_container" style="display:none; clear:both;">
                            <span id="please_wait_container" style="margin-left:15px;"><i class="gficon-gravityforms-spinner-icon gficon-spin"></i> Importing entries. Progress:<span id="progress_container">0%</span></span>
                        </li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<script type="text/html" id="import_form_list_item">
    <li style="display:flex;align-items:center;">
        <label for="{{fieldId}}">{{rowName}}</label>
        <select name="{{fieldId}}" id="{{fieldId}}">{{options}}</select>
    </li>
</script>
<style>
    #import_form_list label {
        flex: 0 0 30%;
    }
</style>
