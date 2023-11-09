<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;

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
                    url : "<?php echo base_url("api/datamaster/employees/delete/"); ?>"+targetId,
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
            url : "<?php echo base_url("api/datamaster/employees/show/"); ?>"+targetId,
            dataType : "json",
            success : function(response,status){
                KTApp.unblockPage();
                if(response.status == true){
                	const {id, fullname,id_cabang,id_area,id_unit, nik,birth_date, birth_place,
                	        gender, mobile, marital,blood_group, address,position, masa_kerja,
							no_rek, last_education, bpjs_kesehatan, bpjs_tk,
							no_employment,join_date
                	} = response.data;
                	const modal = $('#modal_add');
                	if(id_unit){
						modal.find('[name="id_unit"]').parents('.form-group').removeClass('d-none');
                    }
                    //alert(employee_id);
					modal.find('[name="fullname"]').val(fullname);
					modal.find('[name="id"]').val(id);
					modal.find('[name="id_area"]').val(id_area).trigger('change');
					modal.find('[name="unit_id"]').val(id_unit);
					modal.find('[name="cabang_id"]').val(id_cabang);
					modal.find('[name="nik"]').val(nik);
					modal.find('[name="birth_date"]').val(birth_date);
					modal.find('[name="birth_place"]').val(birth_place);
					modal.find('[name="gender"]').val(gender);
					modal.find('[name="mobile"]').val(mobile);
					modal.find('[name="marital"]').val(marital);
					modal.find('[name="blood_group"]').val(blood_group);
					modal.find('[name="address"]').val(address);
					modal.find('[name="position"]').val(position);
					modal.find('[name="masa_kerja"]').val(masa_kerja);
					modal.find('[name="no_rek"]').val(no_rek);
					modal.find('[name="last_education"]').val(last_education);
					modal.find('[name="bpjs_kesehatan"]').val(bpjs_kesehatan);
					modal.find('[name="bpjs_tk"]').val(bpjs_tk);
					modal.find('[name="no_employment"]').val(no_employment);
					modal.find('[name="join_date"]').val(join_date);
                    modal.modal('show');
                }else{
                    AlertUtil.showFailed(data.message);
                    $('#modal_add').modal('show');
                }
            },
			complete:function(xhr){
				const modal = $('.modal');
				modal.find('#gender').trigger('change');
				modal.find('#marital').trigger('change');
				modal.find('#blood_group').trigger('change');
                modal.find('#id_level').select2('refresh');
                setTimeout(function(){  
                        var cabangid =  $('#cabang_id').val(); 
                        $("#cabang").val(cabangid).trigger('change');
                    }, 800);
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
                url: '<?php echo base_url("api/datamaster/employees"); ?>',
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
				field: 'name',
				title: 'Unit',
				sortable: 'asc',
				textAlign: 'left',
			},
            {
                field: 'nik',
                title: 'Nik',
                sortable: 'asc',
                textAlign: 'left',
            },
            {
                field: 'fullname',
                title: 'Nama',
                sortable: 'asc',
                textAlign: 'left',
            },
			{
				field: 'birthday',
				title: 'Tempat Tanggal Lahir',
				sortable: 'asc',
				textAlign: 'left',
				template:function (row) {
					return row.birth_place+', '+row.birth_place
				}
			},
			{
				field: 'gender',
				title: 'Jenis Kelamin',
				sortable: 'asc',
				textAlign: 'left',
			},
			{
				field: 'mobile',
				title: 'Mobile',
				sortable: 'asc',
				textAlign: 'left',
			},
			{
				field: 'marital',
				title: 'Status Kawin',
				sortable: 'asc',
				textAlign: 'left',
			},
			{
				field: 'blood_group',
				title: 'Golongan Darah',
				sortable: 'asc',
				textAlign: 'left',
			},
			{
				field: 'address',
				title: 'Alamat',
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
				  field: 'masa_kerja',
				  title: 'Masa Kerja',
				  sortable: 'asc',
				  textAlign: 'left',
			  },
			  {
				  field: 'no_rek',
				  title: 'No Rekening',
				  sortable: 'asc',
				  textAlign: 'left',
			  },
			  {
				  field: 'join_date',
				  title: 'Join Date',
				  sortable: 'asc',
				  textAlign: 'left',
			  },
			  {
				  field: 'no_employment',
				  title: 'No Karyawam',
				  sortable: 'asc',
				  textAlign: 'left',
			  },
			  {
				  field: 'bpjs_tk',
				  title: 'BPJS TK',
				  sortable: 'asc',
				  textAlign: 'left',
			  },
			  {
				  field: 'bpjs_kesehatan',
				  title: 'BPJS Kesehatan',
				  sortable: 'asc',
				  textAlign: 'left',
			  },
			  {
				  field: 'last_education',
				  title: 'Pendidikan Terakhir',
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

function initCreate(){
     InitClear();
     $('#area').select2({ placeholder: "Please select a Area",width: '100%'});
    //  $('#cabang').select2({ placeholder: "Please select a Cabang",width: '100%'});
    //  $('#id_unit').select2({ placeholder: "Please select a Unit",width: '100%'});
    //  $('#gender').select2({ placeholder: "Please select a Gender",width: '100%'});
    // $('#marital').select2({ placeholder: "Please select a Status",width: '100%'});
    // $('#blood_group').select2({ placeholder: "Please select a Blood Group",width: '100%'});
     //events
     $("#input-form").on("submit",function(e){
    	e.preventDefault();
    	var id = $('[name="id"]').val();
    	var url;
    	if(id){
    		url = "<?php echo base_url("api/datamaster/employees/update"); ?>";
		}else{
    		url = "<?php echo base_url("api/datamaster/employees/insert"); ?>";
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
                if(data.status == 200){
                    datatable.reload();
                    $('#modal_add').modal('hide');
                    AlertUtil.showSuccess(data.message,5000);
                }else{
                    AlertUtil.showFailedDialogAdd(data.message);
                }
                getMenu();
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblock('#modal_add .modal-content');
                AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
            }
        });
    });

}
   
function getMenu(){
		$.ajax({
			type : 'POST',
			url : '<?php echo base_url('api/datamaster/employees');?>',
			data : $(this).serialize(),
			dataType : "json",
			success : function(response,status){
				$('[name="id_parent"]').find('option').remove();
				$('[name="id_parent"]').append('<option value="0">--Sebagai Menu Utama--</option>');
				$.each(response.data, function (index, data) {
					$('[name="id_parent"]').append('<option value="'+data.id+'">'+data.name+'</option>')
				});
			},
		});
}

function getSelect2(){
    $('#cabang').select2({ placeholder: "Please select a cabang",width: '100%'});
    $('#id_unit').select2({ placeholder: "Please select a Unit",width: '100%'});
    $('#gender').select2({ placeholder: "Please select a Gender",width: '100%'});
    $('#marital').select2({ placeholder: "Please select a Status",width: '100%'});
    $('#blood_group').select2({ placeholder: "Please select a Blood Group",width: '100%'});
    $('#id_level').select2({ placeholder: "Please select a Level",width: '100%'});
}

var options = '';
// $(document).on('change', '[name="id_area"]', function(){
//     var id_area = $(this).val();
//     $('[name="id_unit"]').append(options);
//     $.each($('[name="id_unit"]').find('option'), function(index, element){
//         if(id_area != $(this).data('area')){
//             options += '<option value="'+$(this).val()+'" data-area="'+$(this).data('area')+'">'+$(this).text()+'</option>';
//             $(this).remove();
//         }
//     });
//     $('[name="id_unit"]').parents('.form-group').removeClass('d-none');
// });

$(document).on('change', '[name="id_area"]', function(){
    var id = $('#area').val();
    var cabangs =  document.getElementById('cabang');
    var url_data = $('#url_get_cabang').val() + '/' + id;
    $.get(url_data, function (data, status) {
        var response = JSON.parse(data);
        if (status) {
            $("#cabang").empty();
            var opt = document.createElement("option");
            opt.value = "0";
            opt.text = "All";
            cabangs.appendChild(opt);
            for (var i = 0; i < response.data.length; i++) {
                var opt = document.createElement("option");
                opt.value = response.data[i].id;
                opt.text = response.data[i].cabang;
                cabangs.appendChild(opt);
            }
        }
    });
});

$(document).on('change', '[name="id_cabang"]', function(){
    var id = $('#cabang').val();
    var units =  document.getElementById('id_unit');
    var url_data = $('#url_get_unit').val() + '/' + id;
    $.get(url_data, function (data, status) {
        var response = JSON.parse(data);
        if (status) {
            $("#id_unit").empty();
            var opt = document.createElement("option");
            opt.value = "0";
            opt.text = "All";
            units.appendChild(opt);
            for (var i = 0; i < response.data.length; i++) {
                var opt = document.createElement("option");
                opt.value = response.data[i].id;
                opt.text = response.data[i].name;
                units.appendChild(opt);
            }
        }
    });
    setTimeout(function(){  
        var unitid =  $('#unit_id').val(); 
        $("#id_unit").val(unitid).trigger('change');
        //alert(unitid);
    }, 800);
});

jQuery(document).ready(function() {
    initDataTable();
    getSelect2();
    initAlert();
    initCreate();
    getMenu();
});

</script>
