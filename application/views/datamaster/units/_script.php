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
                    url : "<?php echo base_url("api/datamaster/units/delete"); ?>",
                    data : {id:targetId},
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
            url : "<?php echo base_url("api/datamaster/units/get_byid"); ?>",
            data : {id:targetId},
            dataType : "json",
            success : function(response,status){
                KTApp.unblockPage();
                console.log(response.data);
                if(response.status == true){
                    //populate form
                    editForm.populateForm(response.data);
                    $('#modal_edit').modal('show');
                }else{
                    AlertUtil.showFailed(data.message);
                    $('#modal_edit').modal('show');
                }                
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
                url: '<?php echo base_url("api/datamaster/units"); ?>',
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
                field: 'area',
                title: 'Area',
                sortable: 'asc',
                textAlign: 'left',
            }, 
            {
                field: 'cabang',
                title: 'Cabang',
                sortable: 'asc',
                textAlign: 'left',
            },
            {
                field: 'code',
                title: 'Code',
                sortable: 'asc',
                textAlign: 'left',
            }, 
            {
                field: 'name',
                title: 'Unit',
                sortable: 'asc',
                textAlign: 'left',
            }, 
            {
                field: 'date_open',
                title: 'Tanggal Buka',
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

function initCreateForm(){
    //validator
    //$('#add_area').val("val", "").trigger('change.select2');
    //$('#add_cabang').val("val", "").trigger('change.select2');

    var validator = $( "#form_add" ).validate({
        ignore:[],
        rules: {
            area: {
                required: true,
            },
            unit: {
                required: true,
            }
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
    $("#btn_add_submit").on("click",function(){
      var isValid = $( "#form_add" ).valid();
      if(isValid){
        KTApp.block('#modal_add .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/units/insert"); ?>",
            data : $('#form_add').serialize(),
            dataType : "json",
            success : function(data,status){
                KTApp.unblock('#modal_add .modal-content');
                if(data.status == true){
                    datatable.reload();
                    $('#modal_add').modal('hide');
                    AlertUtil.showSuccess(data.message,5000);
                }else{
                    AlertUtil.showFailedDialogAdd(data.message);
                }                
            },
            complete: function(){
                clearForm()
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblock('#modal_add .modal-content');
                AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
            }
        });  
      }
    })

    $('#modal_add').on('hidden.bs.modal', function () {
       validator.resetForm();
    })

    return {
        validator:validator
    }
}

function initEditForm(){
    //validator
    //$('#edit_area').val("val", "").trigger('change.select2');
    //$('#edit_cabang').val("val", "").trigger('change.select2');
    var validator = $( "#form_edit" ).validate({
        ignore:[],
        rules: {
            area_name: {
                required: true,
            }
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
    $("#btn_edit_submit").on("click",function(){
      var isValid = $( "#form_edit" ).valid();
      if(isValid){
        KTApp.block('#modal_edit .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/units/update"); ?>",
            data : $('#form_edit').serialize(),
            dataType : "json",
            success : function(data,status){
                KTApp.unblock('#modal_edit .modal-content');
                if(data.status == true){
                    datatable.reload();
                    $('#modal_edit').modal('hide');
                    AlertUtil.showSuccess(data.message,5000);
                }else{
                    AlertUtil.showFailed(data.message);
                }                
            },
            complete: function(){
                clearForm()
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblock('#modal_edit .modal-content');
                AlertUtil.showFailedDialogEdit("Cannot communicate with server please check your internet connection");
            }
        });  
      }
    })

    $('#modal_edit').on('hidden.bs.modal', function () {
       validator.resetForm();
    
    })

    var populateForm = function(groupObject){
        $("#edit_unit_id").val(groupObject.id);
        $("#edit_area").val(groupObject.id_area);
        $("#edit_area").trigger('change');
        $("#cabangid").val(groupObject.id_cabang);
        $("#edit_unit_name").val(groupObject.name);
        $("#edit_code_unit").val(groupObject.code);
        $("[name='date_open']").val(groupObject.date_open);
        $("#edit_office_id").val(groupObject.office_id);
        $("#edit_region_id").val(groupObject.region_id);
        $("#edit_area_id").val(groupObject.area_id);
        $("#edit_branch_id").val(groupObject.branch_id);

    }

    const  clearForm = function(){
        $("#edit_unit_id").val('');
        $("#edit_unit_name").val('');
        $("#edit_code_unit").val('');
        $("#edit_area").val('');
        $("#date_open").val('');
    }
    
    return {
        validator:validator,
        populateForm:populateForm
    }
}

$('#add_area').on('change',function(){
    var area = $(this).val();
    //alert(area);
    var cabangs =  document.getElementById('add_cabang');
    var url_data = $('#url_get_unit').val() + '/' + area;
    $.get(url_data, function (data, status) {
        var response = JSON.parse(data);
        if (status) {
            $("#add_cabang").empty();
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

    var url_area = $('#url_area_by_id').val() + '/' + area;
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/datamaster/areas/get_byid"); ?>",
        data : {id:area},
        dataType : "json",
        success : function(response,status){
            $('#area_id').val(response.data.area_id);
        },
        error: function (jqXHR, textStatus, errorThrown){
            KTApp.unblockPage();
            AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
        }
    });
});

$('#add_cabang').on('change',function(){
    var cabang = $(this).val();
    
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/datamaster/cabang/get_byid"); ?>",
        data : {id:cabang},
        dataType : "json",
        success : function(response,status){
            console.log(response.data.branch_id)
            $('#branch_id').val(response.data.branch_id);
        },
        error: function (jqXHR, textStatus, errorThrown){
            KTApp.unblockPage();
            AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
        }
    });
});

$('#edit_cabang').on('change',function(){
    var cabang = $(this).val();
    
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/datamaster/cabang/get_byid"); ?>",
        data : {id:cabang},
        dataType : "json",
        success : function(response,status){
            console.log(response.data.branch_id)
            $('#edit_branch_id').val(response.data.branch_id);
        },
        error: function (jqXHR, textStatus, errorThrown){
            KTApp.unblockPage();
            AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
        }
    });
});

$('#edit_area').on('change',function(){
        var area = $('#edit_area').val();
        //alert(area);
        var cabangs =  document.getElementById('edit_cabang');
              
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#edit_cabang").empty();
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
        var url_area = $('#url_area_by_id').val() + '/' + area;
        $.ajax({
            type : 'GET',
            url : "<?php echo base_url("api/datamaster/areas/get_byid"); ?>",
            data : {id:area},
            dataType : "json",
            success : function(response,status){
                $('#edit_area_id').val(response.data.area_id);
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblockPage();
                AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
            }
        });
        setTimeout(function(){  
            var valueToSet =  $('#cabangid').val(); 
            $("#edit_cabang").val(valueToSet).trigger('change');
            //var objSelect = document.getElementById("edit_cabang");
            //setSelectedIndex(objSelect,valueToSet);
        }, 800);
});

function setSelectedIndex(objSelect, valueToSet) {
    for ( var i = 0; i < objSelect.options.length; i++ ) {
        if ( objSelect.options[i].value == valueToSet ) {
            objSelect.options[i].selected = true;
            objSelect.onchange();
            return;
        }
    }
}

jQuery(document).ready(function() { 
    initDataTable();
    createForm = initCreateForm();
    editForm = initEditForm();
    initAlert();
});

</script>