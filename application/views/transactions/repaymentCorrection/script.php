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

	

	function convertNumber(){
		var harga = document.getElementById('harga');
		harga.value = formatRupiah(harga.value);
	} 
	
	function convertRupiah(){
		var harga = $("#harga").val();
		harga = formatNumber(harga);
	}


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

		$('#edit_sge').select2({
        placeholder: "Please select a SGE",
        width: '100%'
    });

    $('#edit_cabang').select2({
        placeholder: "Please select a Cabang",
        width: '100%'
    });


    //events
    $("#btn_edit_submit").on("click", function() {
      var isValid = $("#form_edit").valid();
			var is_correction = $("#is_correction").prop("checked");
			var correction_at = $("#correction_at").val();
			var edit_sge = $("#edit_sge").val();
			var formDataArray = $('#form_add').serializeArray();
			
			formDataArray.push(
				{ name: 'is_correction', value: is_correction },
				{ name: 'correction_at', value : correction_at},
				{ name: 'edit_sge', value : edit_sge}
			);
			var serializedData = $.param(formDataArray);

            // Log the serialized data (optional)
        if (isValid) {
            KTApp.block('#modal_edit .modal-content', {});
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url("api/transactions/repaymentCorrection/update"); ?>",
                data: formDataArray,
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
		const hps = groupObject.data.electronic_hps
        $("#edit_id").val(hps.id);
        $("#edit_region").val(hps.region_id);
        $("#edit_region").trigger('change');
        $("#edit_merk").val(hps.merk);
        $("#edit_types").val(hps.types);
        $("#edit_series").val(hps.series);
        $("#edit_ram").val(hps.ram);
		$("#edit_storage").val(hps.storages);
		$("#edit_harga").val(convertToRupiah(parseInt(hps.estimation_price)));

    }

    const clearForm = function() {
        $("#edit_id").val('');
        $("#edit_region").val('');
        $("#edit_merk").val('');
        $("#edit_types").val('');
        $("#edit_series").val('');
        $("#edit_ram").val('');
		$("#edit_storage").val('');
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

		$('#add_area').select2({
			placeholder: "Please select a Area",
			width: '100%'
		});

		$('#add_cabang').select2({
			placeholder: "Please select a Group",
			width: '100%'
		});

		//events
		$("#btn_add_submit").on("click", function() {
			var isValid = $("#form_add").valid();
			var is_correction = $("#is_correction").prop("checked");
			var formDataArray = $('#form_add').serialize();
			formDataArray.push({ name: 'is_correction_b', value: is_correction });

			if (isValid) {
				KTApp.block('#modal_add .modal-content', {});
				$.ajax({
					type: 'POST',
					url: "<?php echo base_url("api/gcore/hps/insert"); ?>",
					data: formDataArray,
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

	function initDataTable(){		
		var general = $('#generalSearch').val();
		var limit= $('#limit').val();
		var area = $('[name="area"]').val();
		var branch = $('[name="branch"]').val();
		var unit = $('[name="unit"]').val();
		var merk = $('#merk').val();
		
		var option = {
			data: {
				type: 'remote',
				source: {
					read: {
						url: `<?php echo base_url("api/transactions/RepaymentCorrection"); ?>?area=${area}&branch=${branch}&unit=${unit}`,
						map: function(raw) {
							// sample data mapping
							var dataSet = raw;
							if (typeof raw.data !== 'undefined') {
								dataSet = raw.data;
							}
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
					field: 'id',
					title: 'No',
					sortable: 'asc',
					width:30,
					textAlign: 'center',
				},
                {
					field: 'office_name',
					title: 'Unit',
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'product_name',
					title: 'Produk',
					sortable: 'asc',
					textAlign: 'left',
					
				},
				{
					field: 'cif_number',
					title: 'CIF',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'customer',
					title: 'Nasabah',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sge',
					title: 'SGE',
					sortable: 'asc',
					textAlign: 'left',
				},
        {
					field: 'contract_date',
					title: 'Tgl. Kredit',
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'due_date',
					title: 'Tgl. Jatuh Tempo',
					sortable: 'asc',
					textAlign: 'left',
				},
        {
					field: 'auction_date',
					title: 'Tgl. Lelang',
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'correction_at',
					title: 'Tgl. Lunas',
					sortable: 'asc',
					textAlign: 'left',
					template: function (row) {
						var result = "";
						var result = moment(row.correction_at).format('YYYY-MM-DD');

						return result;
					}
				},
                {
					field: 'estimated_value',
					title: 'Taksiran',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = 'Rp ' + convertToRupiah(parseInt(row.estimated_value));
						result = result + "</div>";
						return result;
					}
				},
                {
					field: 'loan_amount',
					title: 'Pinjaman',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = 'Rp ' + convertToRupiah(parseInt(row.loan_amount));
						result = result + "</div>";
						return result;
					}
				},

                {
					field: 'admin_fee',
					title: 'Admin',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = 'Rp ' + convertToRupiah(parseInt(row.admin_fee));
						result = result + "</div>";
						return result;
					}
				},
        {
					field: 'monthly_fee',
					title: 'Sewa/bln',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = 'Rp ' + convertToRupiah(parseInt(row.monthly_fee));
						result = result + "</div>";
						return result;
					}
				},

        {
					field: 'interest_rate',
					title: 'Rate',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = row.interest_rate + '%';
						result = result + "</div>";
						return result;
					}
				},
				{
					field: 'ltv',
					title: 'LTV',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row) {
						var result = "<div class='date-td'>";
						result = row.ltv + '%';
						result = result + "</div>";
						return result;
					}
				},
        {
					field: 'insurance_item_name',
					title: 'Jenis BJ',
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'created_by',
					title: 'Created By',
					sortable: 'asc',
					textAlign: 'left',
				},
                {
					field: 'approved_by',
					title: 'Approved By',
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

		$('#area').on('change', function() {
			let area_id = $(this).val();
			$('[name="branch"]').empty();
			$('[name="unit"]').empty();
			$.ajax({
					type : 'GET',
					url : "<?php echo base_url("api/transactions/repaymentCorrection/getBranch"); ?>/"+area_id,
					dataType : "json",
					success:function(res){

							let template = '<option value="all">All</option>';
							res.data.forEach(res=>{
								template += `<option value="${res.id}">${res.name}</option>`;
							})
							$('[name="branch"]').append(template);
					},
					error:function(e){
							console.log(e);
					}
			});

			datatable.destroy();
		 	initDataTable();

		});

		$('#branch').on('change', function() {
			let branch = $(this).val();
			$('[name="unit"]').empty();
			// initFillUnit();
			$.ajax({
					type : 'GET',
					url : "<?php echo base_url("api/transactions/repaymentCorrection/getUnit"); ?>/"+branch,
					dataType : "json",
					success:function(res){

							let template = '<option value="all">All</option>';
							res.data.forEach(res=>{
								template += `<option value="${res.id}">${res.name}</option>`;
							})
							$('[name="unit"]').append(template);
					},
					error:function(e){
							console.log(e);
					}
			});

			datatable.destroy();
		 	initDataTable();
		});

		$('#unit').on('change', function() {
			datatable.destroy();
		 	initDataTable();
		});
		
	}
	

	$(document).ready(function () {
		initDataTable();
		createForm = initCreateForm();
		editForm = initEditForm();
		initAlert();

	});

	var type = $('[name="area"]').attr('type');
	if(type == 'hidden'){
		$('[name="area"]').trigger('change');
	}
	var type = $('[name="branch"]').attr('type');
	if(type == 'hidden'){
		$('[name="branch"]').trigger('change');
	}
	var type = $('[name="unit"]').attr('type');
	if(type == 'hidden'){
		$('[name="unit"]').trigger('change');
	}
	
$(document).ready(function() {
		$("#is_correction").on("change", function() {
				if ($(this).prop("checked")) {
					var is_correction = $("#is_correction").prop("checked");
					var isChecked = $("#correction_at").val(is_correction);
						console.log("Checkbox di-check (onchecked) ", is_correction);
				} else {
					var is_correction = $("#is_correction").prop("checked");
					var isChecked = $("#correction_at").val(is_correction);
						console.log("Checkbox tidak di-check ", is_correction);
				}
		});
});

$('#area').select2({ placeholder: "Select Regional", width: '100%' });
$('#branch').select2({ placeholder: "Select Branch", width: '100%' });
$('#unit').select2({ placeholder: "Select Unit", width: '100%' });
$('#edit_sge').select2({ placeholder: "Select SGE", width: '100%' });

</script>
