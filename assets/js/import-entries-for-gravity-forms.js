(function(){
	const __trigger = function (el, eventType) {
		if (typeof eventType === 'string' && typeof el[eventType] === 'function') {
			el[eventType]();
		} else {
			const event =
				typeof eventType === 'string'
					? new Event(eventType, {bubbles: true})
					: eventType;
			el.dispatchEvent(event);
		}
	}
	const __get = function (selectorName) {
		const fineElements = document.querySelectorAll(selectorName);
		if(fineElements.length > 1){
			return fineElements;
		}
		return fineElements[0];
	}
	const __on = function (eventName, elementSelector, eventHandler,preventDefault = false) {
		document.addEventListener(eventName, function (event){
			if (event.target.closest(elementSelector)) {
				let elem = event.target.closest(elementSelector);
				eventHandler.bind(event);
				eventHandler.call(event,elem);
				if(preventDefault){
					event.preventDefault();
				}
			}
		});
	};
	const __append = function (elem, html) {
		elem.insertAdjacentHTML('beforeend', html);
	}

	const updateProgressBar =  ( process = 1 ) => {
		__get('#progress_container').style.width = process+"%";
	}
	const runImportStep =  async ( import_data,offset = 0 ) => {
		if ( import_data.rows.length > 0 ) {
			import_data.offset = offset;
			const _wpnonce = __get('[name="import_entries_gf_table_data_wpnonce"]').value;
			const formData = new FormData();
			formData.append('data',JSON.stringify(import_data));
			formData.append('import_entries_gf_table_data_wpnonce',_wpnonce);
			__get('#submit_button_import').style.display = "none";
			updateProgressBar(1);
			const resp = await fetch(ajaxurl+'?action='+__get('[name="action_import"]').value,{
				method : "POST",
				body   : formData
			})
			const result = await resp.json();
			if(result.message){
				__get('#submit_button_import').style.display = "";
				alert(result.message);
			}
			if(result.in_progress){
				runImportStep(import_data,result.offset);
			}
			if(result.total_rows_found){
				let process = Math.floor(result.offset * 100 / result.total_rows_found);
				if(process < 1){
					process = 1;
				}
				updateProgressBar(process);
			}
			if(result.is_done){
				__get('#please_wait_container').style.display = "none";
				__get('#import_is_done').style.display = "";
				__get('#submit_button_import').style.display = "none";
				alert(result.is_done);
			}
		}

	}

	let MediaFrame = null;
	__on('click', '[href="#select-csv"]', function (elem) {
		if ( MediaFrame !== null ) {
			MediaFrame.open();
			return;
		}
		MediaFrame = wp.media({
			title: 'Select or Upload CSV file for import entries',
			button: {
				text: 'Use this CSV file'
			},
			multiple: false
		});
		MediaFrame.on( 'select', function() {
			// Get media attachment details from the frame state
			var attachment = MediaFrame.state().get('selection').first().toJSON();
			let input = __get('#csv_file');
			input.value = attachment.id;
			__trigger(input, 'change');
		});
		MediaFrame.open();
	},true)
	__on('click','#submit_button_import', async function(){
		__get('#import_submit_container').style.display = "";
		let import_data_area = __get('[name="import_data"]');
		let import_data = JSON.parse(import_data_area.value);
		let El      = __get('#import_form_list_row');
		import_data['fields'] = {};
		El.querySelectorAll('input, select, textarea').forEach((inp) => {
			import_data['fields'][inp.value] = inp.name;
		})
		await runImportStep(import_data,1);
	});
	__on('change','#csv_file', async function(){
		let submit_button  = __get('#submit_button');
		let csv_file       = __get("#csv_file");
		if(csv_file.value.length === 0){
			submit_button.style.display = "none";
		}else{
			submit_button.style.display = "block";
			// submit_button.click();
			__trigger(submit_button,'click');
		}
	});
	__on('click','#submit_button', async function(){
		let csv_file   = __get("#csv_file");
		if(csv_file.value.length === 0){
			return;
		}
		let import_csv = __get("#import_csv");
		let formData = new FormData(import_csv);
		const resp = await fetch(ajaxurl+'?action='+formData.get('action'),{
			method:"POST",
			body:formData
		})
		const result = await resp.json();
		if(result.message){
			alert(result.message);
		}
		if( result.rows ){
			const headrow = result.rows[0];
			const fields  = result.fields;
			let Elrow     = __get('#import_form_list_row');
			let El        = __get('#import_form_list');
			let ItemEl    = __get('#import_form_list_item');
			let options = '';
			for (let i = 0; i < headrow.length; i++) {
				options += '<option value="'+i+'" data-if-selected="'+i+'">'+headrow[i]+'</option>';
			}
			for (let i = 0; i < fields.length; i++) {
				let template = ItemEl.innerHTML;
				template = template
					.replace(new RegExp('{{fieldId}}','g'),fields[i][0])
					.replace(new RegExp('{{options}}','g'),options)
					.replace(new RegExp('data-if-selected="'+i+'"','g'),'selected')
					.replace(new RegExp('{{rowName}}','g'),fields[i][1]);
				__append(El,template);
			}
			Elrow.style.display = "";
			__get('[name="import_data"]').value = JSON.stringify(result);
			__get('#start_import_field_container').style.display = "";
			__get('#import_field_container').style.display       = "none";
		}
	});
	__on('change','#import_field_container_list',async function(){
		let El = __get('#import_field_container');
		let form_id = this.target.value;
		if(form_id !== ''){
			El.style.display="";
		}else{
			El.style.display="none";
		}
	})
})()
