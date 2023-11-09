<script>
	var area;
	var datatable;
	var createForm;
	var editForm;

	function formatRupiah(angka) {
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return rupiah;
	}

	function formatNumber(angka) {
		var clean = angka.replace(/\D/g, '');
		return clean;
	}
	function convertToRupiah(angka) {
    var rupiah = '';
    var angkarev = angka.toString().split('').reverse().join('');
    for (var i = 0; i < angkarev.length; i++)
        if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
    return rupiah.split('', rupiah.length - 1).reverse().join('');
}

	var harga = document.getElementById('harga');

	harga.addEventListener('keyup', function(e){
		convertNumber();
		convertRupiah();

	});

	function convertNumber(){
		var harga = document.getElementById('harga');
		harga.value = formatRupiah(harga.value);
	} 
	
	function convertRupiah(){
		var harga = $("#harga").val();
		harga = formatNumber(harga);
	}

	var harga = document.getElementById('edit_harga');

	harga.addEventListener('keyup', function(e){
		convertEditNumber();
		convertEditRupiah();

	});

	function convertEditNumber(){
		var harga = document.getElementById('edit_harga');
		harga.value = formatRupiah(harga.value);
	} 
	
	function convertEditRupiah(){
		var harga = $("#edit_harga").val();
		harga = formatNumber(harga);
	}
	
	function initAlert() {
		AlertUtil = {
			showSuccess: function(message, timeout) {
				$("#success_message").html(message);
				if (timeout != undefined) {
					setTimeout(function() {
						$("#success_alert_dismiss").trigger("click");
					}, timeout)
				}
				$("#success_alert").show();
				KTUtil.scrollTop();
			},
			hideSuccess: function() {
				$("#success_alert_dismiss").trigger("click");
			},
			showFailed: function(message, timeout) {
				$("#failed_message").html(message);
				if (timeout != undefined) {
					setTimeout(function() {
						$("#failed_alert_dismiss").trigger("click");
					}, timeout)
				}
				$("#failed_alert").show();
				KTUtil.scrollTop();
			},
			hideFailed: function() {
				$("#failed_alert_dismiss").trigger("click");
			},
			showFailedDialogAdd: function(message, timeout) {
				$("#failed_message_add").html(message);
				if (timeout != undefined) {
					setTimeout(function() {
						$("#failed_alert_dismiss_add").trigger("click");
					}, timeout)
				}
				$("#failed_alert_add").show();
			},
			hideSuccessDialogAdd: function() {
				$("#failed_alert_dismiss_add").trigger("click");
			},
			showFailedDialogEdit: function(message, timeout) {
				$("#failed_message_edit").html(message);
				if (timeout != undefined) {
					setTimeout(function() {
						$("#failed_alert_dismiss_edit").trigger("click");
					}, timeout)
				}
				$("#failed_alert_edit").show();
			},
			hideSuccessDialogAdd: function() {
				$("#failed_alert_dismiss_edit").trigger("click");
			}
		}
		$("#failed_alert_dismiss").on("click", function() {
			$("#failed_alert").hide();
		})
		$("#success_alert_dismiss").on("click", function() {
			$("#success_alert").hide();
		})
		$("#failed_alert_dismiss_add").on("click", function() {
			$("#failed_alert_add").hide();
		})
		$("#failed_alert_dismiss_edit").on("click", function() {
			$("#failed_alert_edit").hide();
		})
	}


	function initDTEvents() {
		$(".btn_delete").on("click", function() {
			var targetId = $(this).data("id");
			//alert(targetId);
			swal.fire({
				title: 'Anda Yakin?',
				text: "Akan menghapus data ini",
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, Hapus'
			}).then(function(result) {
				if (result.value) {
					KTApp.blockPage();
					$.ajax({
						type: 'GET',
						url: "<?php echo base_url("api/gcore/hps/delete"); ?>",
						data: {
							id: targetId
						},
						dataType: "json",
						success: function(data, status) {
							KTApp.unblockPage();
							if (data.status == true) {
								datatable.reload();
								AlertUtil.showSuccess(data.message, 5000);
							} else {
								AlertUtil.showFailed(data.message);
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							KTApp.unblockPage();
							AlertUtil.showFailed(
								"Cannot communicate with server please check your internet connection"
							);
						}
					});
				}
			});
		});

		$(".btn_edit").on("click", function() {
			var targetId = $(this).data("id");
			KTApp.blockPage();
			$.ajax({
				type: 'GET',
				url: "<?php echo base_url("api/gcore/hps/show"); ?>",
				data: {
					id: targetId
				},
				dataType: "json",
				success: function(response, status) {
					KTApp.unblockPage();
					console.log(response.data);
					if (response.status == true) {
						//populate form
						editForm.populateForm(response.data);
						$('#modal_edit').modal('show');
					} else {
						AlertUtil.showFailed(data.message);
						$('#modal_edit').modal('show');
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					KTApp.unblockPage();
					AlertUtil.showFailed(
						"Cannot communicate with server please check your internet connection");
				}
			});
		});
	}

	function initEditForm() {
    //validator
    //$('#edit_area').val("val", "").trigger('change.select2');
    //$('#edit_cabang').val("val", "").trigger('change.select2');
    var validator = $("#form_edit").validate({
        ignore: [],
        rules: {
            // area_name: {
            //     required: true,
            // }
        },
        invalidHandler: function(event, validator) {
            KTUtil.scrollTop();
        }
    });

    $('#edit_area').select2({
        placeholder: "Please select a Area",
        width: '100%'
    });

    $('#edit_cabang').select2({
        placeholder: "Please select a Cabang",
        width: '100%'
    });


    //events
    $("#btn_edit_submit").on("click", function() {
        var isValid = $("#form_edit").valid();
        if (isValid) {
            KTApp.block('#modal_edit .modal-content', {});
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url("api/gcore/hps/update"); ?>",
                data: $('#form_edit').serialize(),
                dataType: "json",
                success: function(data, status) {
                    KTApp.unblock('#modal_edit .modal-content');
                    if (data.status == true) {
                        datatable.reload();
                        $('#modal_edit').modal('hide');
                        AlertUtil.showSuccess(data.message, 5000);
                    } else {
                        AlertUtil.showFailed(data.message);
                    }
                },
                complete: function() {
                    clearForm()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    KTApp.unblock('#modal_edit .modal-content');
                    AlertUtil.showFailedDialogEdit(
                        "Cannot communicate with server please check your internet connection");
                }
            });
        }
    })

    $('#modal_edit').on('hidden.bs.modal', function() {
        validator.resetForm();

    })

    var populateForm = function(groupObject) {
      console.log(groupObject);
			console.log(groupObject.data.electronic_hps);
			const hps = groupObject.data.electronic_hps
        $("#edit_id").val(hps.id);
        $("#edit_region").val(hps.region_id);
        $("#edit_region").trigger('change');
        $("#edit_insurance_item_id").val(hps.insurance_item_id);
        $("#edit_insurance_item_id").trigger('change');
        $("#edit_merk").val(hps.merk);
        $("#edit_types").val(hps.types);
        $("#edit_series").val(hps.series);
				$("#edit_processor").val(hps.processor);
        $("#edit_ram").val(hps.ram);
				$("#edit_storage").val(hps.storages);
				$("#edit_type_storage").val(hps.type_storage);
        $("#edit_vga").val(hps.vga);
				$("#edit_year").val(hps.year);
				$("#edit_harga").val(convertToRupiah(parseInt(hps.estimation_price)));

				const selectInsurance = document.getElementById('edit_insurance_item_id');
				// Get the selected option
				const Option = selectInsurance.options[selectInsurance.selectedIndex];
				// Get the text content of the selected option
				const Text = Option.textContent;

				console.log('tetxt',Text)

    }

    const clearForm = function() {
			$("#edit_id").val('');
			$("#edit_region").val('');
			$("edit_insurance_item").val('');
			$("#edit_merk").val('');
			$("#edit_types").val('');
			$("#edit_series").val('');
			$("#edit_processor").val('');
			$("#edit_ram").val('');
			$("#edit_storage").val('');
			$("#edit_type_storage").val('');
			$("#edit_vga").val('');
			$("#edit_year").val('');
			$("#edit_harga").val('');
    }

    return {
        validator: validator,
        populateForm: populateForm
    }
}

	function initCreateForm() {
    //validator
    //$('#add_area').val("val", "").trigger('change.select2');
    //$('#add_cabang').val("val", "").trigger('change.select2');

		var validator = $("#form_add").validate({
			ignore: [],
			rules: {
				// area: {
				// 	required: true,
				// },
				// unit: {
				// 	required: true,
				// }
			},
			invalidHandler: function(event, validator) {
				KTUtil.scrollTop();
			}
		});

		// $('#add_area').select2({
		// 	placeholder: "Please select a Area",
		// 	width: '100%'
		// });

		$('#add_cabang').select2({
			placeholder: "Please select a Group",
			width: '100%'
		});


		//events
		$("#btn_add_submit").on("click", function() {
			var isValid = $("#form_add").valid();
			if (isValid) {
				KTApp.block('#modal_add .modal-content', {});
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url("api/gcore/hps/insert"); ?>",
					data: $('#form_add').serialize(),
					dataType: "json",
					success: function(data, status) {
						KTApp.unblock('#modal_add .modal-content');
						if (data.status == true) {
							datatable.reload();
							$('#modal_add').modal('hide');
							AlertUtil.showSuccess(data.message, 5000);
						} else {
							AlertUtil.showFailedDialogAdd(data.message);
						}
					},
					complete: function() {
						clearForm()
					},
					error: function(jqXHR, textStatus, errorThrown) {
						KTApp.unblock('#modal_add .modal-content');
						AlertUtil.showFailedDialogAdd(
							"Cannot communicate with server please check your internet connection");
					}
				});
			}
		})

		$('#modal_add').on('hidden.bs.modal', function() {
			validator.resetForm();
		})

		return {
			validator: validator
		}
	}

	function validateFile(input) {
		const fileSize = input.files[0].size; // ukuran file dalam bytes
		const maxSize = 2048 * 2048; // batas maksimum ukuran file dalam bytes (contohnya 1 MB)

		if (fileSize > maxSize) {
			$('#message').val('Ukuran file terlalu besar, maksimum ukuran file adalah 1 MB.');
			// alert('Ukuran file terlalu besar, maksimum ukuran file adalah 1 MB.');
			input.value = ''; // menghapus file yang dipilih
		}
	}

	$('.upload').on('click', function (e) {
		e.preventDefault();
		$('#modal-upload').modal('show');
	});
	
	$('#modal-form').on('click', function (e) {
		e.preventDefault();
		$(this).modal('show');
	});
	$('.form-input').on('submit', function (e) {
		e.preventDefault();
		var data = new FormData(this);
		console.log(data);
		$.ajax({
			url : '<?php echo base_url('api/gcore/hps/upload');?>',
			type : 'POST',
			data : data,
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function (){
                   $("#loading").show(1000).html("<img src='<?php echo base_url(); ?>assets/media/loading-buffering.gif' height='50'>");                   
                   },
			success : function(response) {
				// $("#loading").hide();
				location.reload();

			}
		});
	});

	// $(document).on('click','.btn-edit', function(e){
	// 	e.preventDefault();
	// 	$.ajax({
	// 		url : '<?php echo base_url('api/datamaster/customers/show/');?>'+$(this).data('id'),
	// 		dataType:'JSON',
	// 		success : function(response) {
	// 			var data = response.data;
	// 			$('[name="id"]').val(data.id);
	// 			$('[name="no_cif"]').val(data.no_cif);
	// 			$('[name="nik"]').val(data.nik);
	// 			$('[name="name"]').val(data.name);
	// 			$('[name="mobile"]').val(data.mobile);
	// 			$('[name="birth_date"]').val(data.birth_date);
	// 			$('[name="birth_place"]').val(data.birth_place);
	// 			$('[name="gender"]').val(data.gender);				
	// 			$('[name="marital"]').val(data.marital);
	// 			$('[name="marital"]').trigger('change');
	// 			$('[name="province"]').val(data.province);
	// 			$('[name="city"]').val(data.city);
	// 			$('[name="address"]').val(data.address);
	// 			$('[name="job"]').val(data.job);
	// 			$('[name="mother_name"]').val(data.mother_name);
	// 			$('[name="sibling_name"]').val(data.sibling_name);
	// 			$('[name="sibling_birth_date"]').val(data.sibling_birth_date);
	// 			$('[name="sibling_birth_place"]').val(data.sibling_birth_place);
	// 			$('[name="sibling_job"]').val(data.sibling_job);
	// 			$('[name="sibling_relation"]').val(data.sibling_relation);
	// 			$('[name="sibling_address_1"]').val(data.sibling_address_1);
	// 			$('[name="rt"]').val(data.rt);
	// 			$('[name="rw"]').val(data.rw);
	// 			$('[name="kelurahan"]').val(data.kelurahan);
	// 			$('[name="kecamatan"]').val(data.kecamatan);
	// 			$('[name="kodepos"]').val(data.kodepos);
	// 			$('#modal-form').trigger('click');
	// 		}
	// 	});

		

	// });

	// $('.btn-save').on('click', function (e) {
	// 	e.preventDefault();
	// 	$.ajax({
	// 		url : '<?php echo base_url('api/datamaster/customers/update');?>',
	// 		type : 'POST',
	// 		data : $('.form-modal').serialize(),
	// 		success : function(response) {
	// 			location.reload();
	// 		}
	// 	});
	// });

	// $('.btn-close').on('click', function (e) {
	// 	e.preventDefault();
	// 	// Coding
	// 	$('#modal-form').modal('toggle'); //or  $('#IDModal').modal('hide');
	// 	return false;
	// });

	// $('.close').on('click', function (e) {
	// 	e.preventDefault();
	// 	// Coding
	// 	$('#modal-form').modal('toggle'); //or  $('#IDModal').modal('hide');
	// 	return false;
	// });

	function initDataTable(){		
		var general = $('#generalSearch').val();
		var processor = $('#processor').val();
		var limit= $('#limit').val();
		var area = $('[name="area"]').val();
		var insurance = $('[name="insurance"]').val();
		var merk = $('#merk').val();
		var option = {
			data: {
				type: 'remote',
				source: {
					read: {
						url: `<?php echo base_url("api/gcore/hps"); ?>?area=${area}&merk=${merk}&insurance=${insurance}&processor=${processor}`,
						map: function(raw) {
							// sample data mapping
							var dataSet = raw;
							if (typeof raw.data !== 'undefined') {
								dataSet = raw.data;
							}
							console.log('dataset', dataSet)
							return dataSet;
						},
					},
				},
				serverPaging: true,
				serverFiltering: true,
				serverSorting: true,
				saveState : {cookie: false,webstorage: false},
			},
			sortable: true,
			pagination: true,
			search: {
				input: $('#generalSearch'),
			},
			columns: [
				{
					field: 'region',
					title: 'Wilayah',
					sortable: 'asc',
					width:60,
					textAlign: 'center',
				},
                {
					field: 'name',
					title: 'Deskripsi',
                    width: 350,
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'estimation_price',
					title: 'Harga',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = 'Rp ' + convertToRupiah(parseInt(row.estimation_price));
						result = result + "</div>";
						return result;
					}
				},
				{
					field: 'merk',
					title: 'Merk',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'types',
					title: 'Type',
					sortable: 'asc',
					textAlign: 'left',
				},
        {
					field: 'series',
					title: 'Series',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'processor',
					title: 'Processor',
					sortable: 'asc',
					textAlign: 'left',
				},
        {
					field: 'ram',
					title: 'RAM',
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'storages',
					title: 'Storage',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'type_storage',
					title: 'Tipe Storage',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'vga',
					title: 'VGA',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'year',
					title: 'Year',
					sortable: 'asc',
					textAlign: 'left',
				},
                
				{
					field: 'action',
					title: 'Action',
					sortable: false,
					width: 60,
					overflow: 'visible',
					textAlign: 'center',
					autoHide: false,
					template: function (row) {
						var result = "";
						result = result + '<span data-id="' + row.id +
							'" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></span>';
						result = result + '<span data-id="' + row.id +
							'" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_delete" title="Delete" ><i class="flaticon2-trash" style="cursor:pointer;"></i></span>';
						return result;
					}
				}
			],
			layout:{
				header:true
			}
		}

		datatable = $('#kt_datatable').KTDatatable(option);
		datatable.on("kt-datatable--on-layout-updated",function(){
			initDTEvents();
		});
	}

	$(document).ready(function () {
		initDataTable();
		createForm = initCreateForm();
		editForm = initEditForm();
		initAlert();

	});

	$('#processor').on('blur', function() {
		datatable.destroy();
		initDataTable();
	});
	$('#merk').on('change', function() {
			datatable.destroy();
		 	initDataTable();
			// datatable.search($(this).val().toLowerCase(), 'limit');
		});

		$('#area').on('change', function() {
			datatable.destroy();
		 	initDataTable();
		});

		$('#insurance').on('change', function() {
			const insuranceName = $(this).find('option:selected').text();
			$('[name="insurance_name"]').val(insuranceName);

			let insurance = $(this).val();
			$('[name="merk"]').empty();
			var data_merk =  $('[name="merk"]');
			var opt = document.createElement("option");
			opt.value = "0";
			opt.text = "All";
			data_merk.append(opt);

			let template = '';
			$.ajax({
					type: 'GET',
					url: "<?php echo base_url("api/gcore/hps/merk_hps"); ?>/" + insurance,
					dataType: "json",
					success: function(res) {
							
							res.data.forEach(res => {
									template += `<option value="${res.merk}">${res.merk}</option>`;
							})
							$('[name="merk"]').append(template);
					},
					error: function(e) {
							console.log(e);
					}
			});
			
			datatable.destroy();
		 	initDataTable();
			
		});

	var type = $('[name="area"]').attr('type');
	if(type == 'hidden'){
		$('[name="area"]').trigger('change');
	}
	var type = $('[name="insurance"]').attr('type');
	if(type == 'hidden'){
		$('[name="insurance"]').trigger('change');
	}
	var type = $('[name="merk"]').attr('type');
	if(type == 'hidden'){
		$('[name="merk"]').trigger('change');
	}
	var type = $('[name="usiasampai"]').attr('type');
	if(type == 'hidden'){
		$('[name="usiasampai"]').trigger('change');
	}
	
$('[name="cabang"]').on('change',function(){
	var cabang = $('[name="cabang"]').val();
	var units =  $('[name="id_unit"]');
	var url_data = $('#url_get_units').val() + '/' + cabang;



	$.get(url_data, function (data, status) {
		var response = JSON.parse(data);
		if (status) {
			$("#unit").empty();
			units.append('<option value="0">All</option>');
			for (var i = 0; i < response.data.length; i++) {
				var opt = document.createElement("option");
				opt.value = response.data[i].id;
				opt.text = response.data[i].name;
				units.append(opt);
			}
		}
	});
});

$('[name="insurance_item_id"]').on('change',function(e){

	const selectedText = $(this).find('option:selected').text();
  
  if (selectedText == 'Handphone') {
		//hidden
		document.getElementById("processor_div").hidden = true; 
		document.getElementById("type_storage_div").hidden = true; 
		document.getElementById("vga_div").hidden = true; 
		document.getElementById("year_div").hidden = true; 

		document.getElementById("types_div").hidden = false; 

		//required
		document.getElementById("processor").required = false; 
		document.getElementById("type_storage").required = false; 
		document.getElementById("vga").required = false; 
		document.getElementById("year").required = false; 

		document.getElementById("types").require = true; 

	} else if (selectedText == 'Laptop'){
		document.getElementById("types_div").hidden = true; 

		document.getElementById("processor_div").hidden = false; 
		document.getElementById("type_storage_div").hidden = false; 
		document.getElementById("vga_div").hidden = false; 
		document.getElementById("year_div").hidden = false;

		//required
		document.getElementById("types").required = false; 

		document.getElementById("processor").required = true; 
		document.getElementById("type_storage").required = true; 
		document.getElementById("vga").required = true; 
		document.getElementById("year").required = true;
	}else{
		console.log('nothing')
	}

});

$('#edit_insurance_item_id').on('change', function() {
	const selectedText = $(this).find('option:selected').text();
  
  if (selectedText == 'Handphone') {
		//hidden
		document.getElementById("edit_processor_div").hidden = true; 
		document.getElementById("edit_type_storage_div").hidden = true; 
		document.getElementById("edit_vga_div").hidden = true; 
		document.getElementById("edit_year_div").hidden = true; 

		document.getElementById("edit_types_div").hidden = false; 

		//required
		document.getElementById("edit_processor").required = false; 
		document.getElementById("edit_type_storage").required = false; 
		document.getElementById("edit_vga").required = false; 
		document.getElementById("edit_year").required = false; 

		document.getElementById("edit_types").require = true; 

	} else if (selectedText == 'Laptop'){
		document.getElementById("edit_types_div").hidden = true; 

		document.getElementById("edit_processor_div").hidden = false; 
		document.getElementById("edit_type_storage_div").hidden = false; 
		document.getElementById("edit_vga_div").hidden = false; 
		document.getElementById("edit_year_div").hidden = false;

		//required
		document.getElementById("edit_types").required = false; 

		document.getElementById("edit_processor").required = true; 
		document.getElementById("edit_type_storage").required = true; 
		document.getElementById("edit_vga").required = true; 
		document.getElementById("edit_year").required = true;
	}else{
		console.log('nothing')
	}
});

$('#area').select2({ placeholder: "Select Regional", width: '100%' });
$('#insurance').select2({ placeholder: "Select Jenis Brang Jaminan", width: '100%' });
$('#merk').select2({ placeholder: "Select Merk", width: '100%' });
$('#usiadari').select2({ placeholder: "Select usiadari", width: '100%' });
$('#usiasampai').select2({ placeholder: "Select usiasampai", width: '100%' });
</script>
