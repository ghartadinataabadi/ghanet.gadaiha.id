<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;


function initDTEvents(){
	$(".btn_delete").on("click",function(){
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
					type : 'GET',
					url : "<?php echo base_url("api/users/delete/"); ?>"+targetId,
					dataType : "json",
					success : function(data,status){
						KTApp.unblockPage();
						if(data.status == true){
							datatable.reload();
							AlertUtil.showSuccess(data.message,5000);
						}else{
							AlertUtil.showFailed(data.message);
						}
					},
					error: function (jqXHR, textStatus, errorThrown){
						KTApp.unblockPage();
						AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
					}
				});
			}
		});
	});

	$(".btn_edit").on("click",function(){
		var targetId = $(this).data("id");
		KTApp.blockPage();
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/users/show/"); ?>"+targetId,
			dataType : "json",
			success : function(response,status){
				KTApp.unblockPage();
				if(response.data){
					const {id, id_unit,id_cabang ,id_level,id_area, id_employee, email, username,fullname
					} = response.data;
					const modal = $('#modal_add');
					if(id_employee > 0){
						modal.find('[name="id_employee"]').append('<option value="'+id_employee+'">'+fullname+'</option>');
					}
					modal.find('[name="id"]').val(id);
					modal.find('[name="id_level"]').val(id_level);
					modal.find('[name="id_area"]').val(id_area);
					modal.find('[name="id_unit"]').val(id_unit);
					modal.find('[name="id_cabang"]').val(id_cabang);
					modal.find('[name="id_employee"]').val(id_employee);
					modal.find('[name="email"]').val(email);
					modal.find('[name="username"]').val(username);
					if(id_level >0){
						modal.find('[name="id_level"]').trigger('change');
						modal.find('[name="id_level"]').parents('.form-group').removeClass('d-none');
					}
					if(id_area > 0  && id_unit==0){
						modal.find('[name="id_area"]').trigger('change');
						modal.find('[name="id_area"]').parents('.form-group').removeClass('d-none');
					}
					if(id_unit > 0 && id_level > 0){
						modal.find('[name="id_unit"]').trigger('change');
						modal.find('[name="id_unit"]').parents('.form-group').removeClass('d-none');
					}
					if(id_cabang > 0){
						modal.find('[name="id_cabang"]').trigger('change');
						modal.find('[name="id_cabang"]').parents('.form-group').removeClass('d-none');
					}
					modal.modal('show');
					modal.find('[name="id_level"]').val(id_level);
					modal.find('[name="id_area"]').val(id_area);
					modal.find('[name="id_employee"]').val(id_employee);

					setTimeout(function(){  
                        //var cabangid =  $('#cabang_id').val(); 
						//$("#cabang").val(cabangid).trigger('change');						
                    }, 800);

				}else{
					AlertUtil.showFailed(response.data.message);
					$('#modal_add').modal('show');
				}
			},
			complete:function(xhr){
				const modal = $('.modal');
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
				AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
			}
		});
	});
}



function initDataTable(){
    var option = {
        data: {
            type: 'remote',
            source: {
              read: {
                url: '<?php echo base_url("api/datamaster/employees/get_user"); ?>',
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
                title: 'ID',
                sortable: 'asc',
                width:60,
                textAlign: 'center',
            },
            {
                field: 'fullname',
                title: 'Fullname',
                sortable: 'asc',
                textAlign: 'left',
            },
            {
                field: 'username',
                title: 'Username',
                sortable: 'asc',
                textAlign: 'left',
            },
			{
				field: 'id_level',
				title: 'level',
				sortable: 'asc',
				textAlign: 'left',
                template: function (row) {
                    var level ="";
                    if(row.id_level==1){
                        level = 'Administrator';
                    } else if(row.id_level==2){
                        level = 'Pusat';
                    }else if(row.id_level==3){
                        level = 'Unit';
                    }else if(row.id_level==4){
                        level = 'Area';
                    }else if(row.id_level==6){
                        level = 'Cabang';
                    }else if(row.id_level==7){
                        level = 'Penaksir';
                    }else if(row.id_level==8){
                        level = 'Kasir';
                    }
                    return level;
                }
			},          
			
			{
				field: 'name',
				title: 'Unit',
				sortable: 'asc',
				textAlign: 'left',
			},
            {
				field: 'position',
				title: 'Jabatan',
				sortable: 'asc',
				textAlign: 'left',
			},
			{
				field: 'status',
				title: 'Status',
				sortable: 'asc',
				textAlign: 'left',
			},
			  {
				  field: 'action',
				  title: 'Action',
				  sortable: false,
				  width: 100,
				  overflow: 'visible',
				  textAlign: 'center',
				  autoHide: false,
				  template: function (row) {
				  	console.log(row);
					  var result ="";
					  result = result + '<span data-id="' + row.id + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></span>';
					  result = result + '<span data-id="' + row.id + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_delete" title="Delete" ><i class="flaticon2-trash" style="cursor:pointer;"></i></span>';
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
    })
}

function initCreate(){
	$('#id_employee').select2({ placeholder: "Please select a Employee", width: '100%'});
	$('#id_level').select2({ placeholder: "Please select a Level", width: '100%'});
	$('#area').select2({ placeholder: "Please select a Area", width: '100%'});
	$('#id_unit').select2({ placeholder: "Please select a Unit", width: '100%'});
	$('#id_cabang').select2({ placeholder: "Please select a Cabang", width: '100%'});
	
	InitClear();
	//events
	$("#input-form").on("submit",function(e){
		e.preventDefault();
		var id = $('[name="id"]').val();
		var url;
		if(id){
			url = "<?php echo base_url("api/users/update"); ?>";
		}else{
			url = "<?php echo base_url("api/users/insert"); ?>";
		}
		KTApp.block('#modal_add .modal-content', {});
		$.ajax({
			type : 'POST',
			url : url,
			data : $(this).serialize(),
			dataType : "json",
			success : function(data,status){
				$('#modal_add').find('[name="id"]').val("");
				$('#modal_add').find('[name="name"]').val("");
				KTApp.unblock('#modal_add .modal-content');
				console.log(data);
				if(data.status == 200){
					// datatable.reload();
					$('#modal_add').modal('hide');
					AlertUtil.showSuccess(data.message,5000);
				}else{
					AlertUtil.showFailedDialogAdd(data.message);
				}
				//getMenu();
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblock('#modal_add .modal-content');
				AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
			}
		});
	});

}


function InitClear(){
	$('.modal').on('hidden.bs.modal', function(){
		$("#id_unit").val([]).trigger("change");
		$("#gender").val([]).trigger("change");
		$("#blood_group").val([]).trigger("change");
		$("#marital").val([]).trigger("change");
		$("#id_level").val([]).trigger("change");
		$(this).find('form')[0].reset();
	});
}

function initAlert(){
    AlertUtil = {
        showSuccess : function(message,timeout){
            $("#success_message").html(message);
            if(timeout != undefined){
                setTimeout(function(){
                    $("#success_alert_dismiss").trigger("click");
                },timeout)
            }
            $("#success_alert").show();
            KTUtil.scrollTop();
        },
        hideSuccess : function(){
            $("#success_alert_dismiss").trigger("click");
        },
        showFailed : function(message,timeout){
            $("#failed_message").html(message);
            if(timeout != undefined){
                setTimeout(function(){
                    $("#failed_alert_dismiss").trigger("click");
                },timeout)
            }
            $("#failed_alert").show();
            KTUtil.scrollTop();
        },
        hideFailed : function(){
            $("#failed_alert_dismiss").trigger("click");
        },
        showFailedDialogAdd : function(message,timeout){
            $("#failed_message_add").html(message);
            if(timeout != undefined){
                setTimeout(function(){
                    $("#failed_alert_dismiss_add").trigger("click");
                },timeout)
            }
            $("#failed_alert_add").show();
        },
        hideSuccessDialogAdd : function(){
            $("#failed_alert_dismiss_add").trigger("click");
        },
        showFailedDialogEdit : function(message,timeout){
            $("#failed_message_edit").html(message);
            if(timeout != undefined){
                setTimeout(function(){
                    $("#failed_alert_dismiss_edit").trigger("click");
                },timeout)
            }
            $("#failed_alert_edit").show();
        },
        hideSuccessDialogAdd : function(){
            $("#failed_alert_dismiss_edit").trigger("click");
        }
    }
    $("#failed_alert_dismiss").on("click",function(){
        $("#failed_alert").hide();
    })
    $("#success_alert_dismiss").on("click",function(){
        $("#success_alert").hide();
    })
    $("#failed_alert_dismiss_add").on("click",function(){
        $("#failed_alert_add").hide();
    })
    $("#failed_alert_dismiss_edit").on("click",function(){
        $("#failed_alert_edit").hide();
    })
}

jQuery(document).ready(function() { 
    initDataTable();
    initAlert();
    initCreate();
});


$('[name="id_level"]').on('change', function(){
	console.log($(this).find(':selected').text());
	if($(this).find(':selected').text() == 'area'){
		$('[name="id_area"]').parents('.form-group').removeClass('d-none');
		$('[name="id_unit"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_cabang"]').val('').parents('.form-group').addClass('d-none');
	}else if($(this).find(':selected').text() == 'unit'){
		$('[name="id_unit"]').parents('.form-group').removeClass('d-none');
		$('[name="id_area"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_cabang"]').val('').parents('.form-group').addClass('d-none');
	}else if($(this).find(':selected').text() == 'penaksir'){
		$('[name="id_unit"]').parents('.form-group').removeClass('d-none');
		$('[name="id_area"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_cabang"]').val('').parents('.form-group').addClass('d-none');
	}else if($(this).find(':selected').text() == 'kasir'){
		$('[name="id_unit"]').parents('.form-group').removeClass('d-none');
		$('[name="id_area"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_cabang"]').val('').parents('.form-group').addClass('d-none');
	}else if($(this).find(':selected').text() == 'cabang'){
		$('[name="id_cabang"]').parents('.form-group').removeClass('d-none');
		$('[name="id_area"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_unit"]').val('').parents('.form-group').addClass('d-none');
	}else{
		$('[name="id_unit"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_area"]').val('').parents('.form-group').addClass('d-none');
		$('[name="id_cabang"]').val('').parents('.form-group').addClass('d-none');
	}
});
var options = '';

// $(document).on('change', '[name="id_area"]', function(){
// 	var id_area = $(this).val();
// 	if($('[name="id_level"]').find(':selected').text() == 'unit'){
// 		$('[name="id_unit"]').append(options);
// 		$.each($('[name="id_unit"]').find('option'), function(index, element){
// 			if(id_area != $(this).data('area')){
// 				options += '<option value="'+$(this).val()+'" data-area="'+$(this).data('area')+'">'+$(this).text()+'</option>';
// 				$(this).remove();
// 			}
// 		});
// 		$('[name="id_unit"]').parents('.form-group').removeClass('d-none');
// 	};
// });


</script>
