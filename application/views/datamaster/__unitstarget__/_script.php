<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;


// function convertToRupiah(angka)
// {
// 	var rupiah = '';		
// 	var angkarev = angka.toString().split('').reverse().join('');
// 	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
// 	return rupiah.split('',rupiah.length-1).reverse().join('');
// }

function initDTEvents(){
    $(".btn_delete").on("click",function(){
        var targetId = $(this).data("id");
        //alert(targetId);
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it'
        }).then(function(result) {
            if (result.value) {
                KTApp.blockPage();
                $.ajax({
                    type : 'GET',
                    url : "<?php echo base_url("api/datamaster/unitstarget/delete"); ?>",
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
            url : "<?php echo base_url("api/datamaster/unitstarget/get_byid"); ?>",
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
                url: '<?php echo base_url("api/datamaster/unitstarget"); ?>',
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
                field: 'name',
                title: 'Unit',
                sortable: 'asc',
                textAlign: 'left',
            },
            {
                field: 'periode',
                title: 'Periode',
                sortable: 'asc',
                textAlign: 'left',
                template: function (row) {
                            var result = "<div class='date-td'>";
                            var month = moment(row.month).format('MMM');
                            result = result + '<div>' + month + ' - ' + row.year + '</div> ';
                            result = result + "</div>";
                            return result;
                        }
            }, 
            {
                field: 'amount_booking',
                title: 'Booking',
                sortable: 'asc',
                textAlign: 'right',
                template: function (row) {
                    var result ="";
                        result = row.amount_booking;
                    return result;
                } 
            },
            {
                field: 'amount_outstanding',
                title: 'Outstanding',
                sortable: 'asc',
                textAlign: 'right',
                template: function (row) {
                    var result ="";
                        result = row.amount_outstanding;
                        
                    return result;
                } 
            }, 
            {
                field: 'status',
                title: 'Status',
                sortable: 'asc',
                textAlign: 'center',
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
    var validator = $( "#form_add" ).validate({
        ignore:[],
        rules: {
            unit: {
                required: true,
            },
            month: {
                required: true,
            },
            year: {
                required: true,
            },
            amount: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });

    $('#add_unit').select2({
        placeholder: "Please select a Unit",
        width: '100%'
    });   

    $('#add_month').select2({
        placeholder: "Please select a Month",
        width: '100%'
    });

    $('#add_year').select2({
        placeholder: "Please select a Year",
        width: '100%'
    });
    
    //events
    $("#btn_add_submit").on("click",function(){
      var isValid = $( "#form_add" ).valid();
      if(isValid){
        KTApp.block('#modal_add .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/unitstarget/insert"); ?>",
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
    var validator = $( "#form_edit" ).validate({
        ignore:[],
        rules: {
            unit: {
                required: true,
            },
            month: {
                required: true,
            },
            year: {
                required: true,
            },
            amount: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });  

    $('#edit_unit').select2({
        placeholder: "Please select a Unit",
        width: '100%'
    });   

    $('#edit_month').select2({
        placeholder: "Please select a Month",
        width: '100%'
    });

    $('#edit_year').select2({
        placeholder: "Please select a Year",
        width: '100%'
    });
    //events
    $("#btn_edit_submit").on("click",function(){
      var isValid = $( "#form_edit" ).valid();
      if(isValid){
        KTApp.block('#modal_edit .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/unitstarget/update"); ?>",
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
        $("#edit_unitstarget_id").val(groupObject.id);
        $("#edit_unit").val(groupObject.id_unit);
        $("#edit_month").val(groupObject.month);
        $("#edit_year").val(groupObject.year);
        $("#edit_booking").val(groupObject.amount_booking);
        $("#edit_outstanding").val(groupObject.amount_outstanding);
        $("#edit_unit").trigger('change');
        $("#edit_month").trigger('change');
        $("#edit_year").trigger('change');
    }
    
    return {
        validator:validator,
        populateForm:populateForm
    }
}

jQuery(document).ready(function() { 
    
    initDataTable();
    //createForm = initCreateForm();
    editForm = initEditForm();
    initAlert();
    $('#unit').select2({
        placeholder: "Please select a Unit",
        width: '100%'
    });   
});

</script>