<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var uploadForm;
var editForm;

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
                    url : "<?php echo base_url("api/transactions/repaymentmortage/delete"); ?>",
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
            url : "<?php echo base_url("api/datamaster/areas/get_byid"); ?>",
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
                url: '<?php echo base_url("api/transactions/repaymentmortage"); ?>',
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
                field: 'no_sbk',
                title: 'NO. SBK',
                sortable: 'asc',
                //width:60,
                textAlign: 'center',
            }, 
            {
                field: 'date_kredit',
                title: 'Tanggal Kredit',
                sortable: 'asc',
                textAlign: 'center',
                template: function (row) {
                            var result = "<div class='date-td'>";
                            var new_date = moment(row.date_kredit).format('MMM DD, YYYY');
                            result = result + '<div>' + new_date + '</div> ';
                            result = result + "</div>";
                            return result;
                        }
            }, 
            {
                field: 'date_installment',
                title: 'Tanggal Angsuran',
                sortable: 'asc',
                textAlign: 'center',
                template: function (row) {
                            var result = "<div class='date-td'>";
                            var new_date = moment(row.date_installment).format('MMM DD, YYYY');
                            result = result + '<div>' + new_date + '</div> ';
                            result = result + "</div>";
                            return result;
                        }
            }, 
            {
                field: 'amount',
                title: 'Amount',
                sortable: 'asc',
                textAlign: 'right',
            },
            {
                field: 'capital_lease',
                title: 'Sewa',
                sortable: 'asc',
                textAlign: 'right',
            },
            {
                field: 'fine',
                title: 'Denda',
                sortable: 'asc',
                textAlign: 'center',
            },
            {
                field: 'saldo',
                title: 'Saldo',
                sortable: 'asc',
                textAlign: 'left',
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

function initUploadForm(){
    //validator
    var validator = $( "#form_upload" ).validate({
        ignore:[],
        rules: {
            unit: {
                required: true,
            },            
            file: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });   
    
    $('#unit').select2({
        placeholder: "Please select a Unit",
        width: '100%'
    });
    $('#kodetrans').select2({
        placeholder: "Please select a Transaction Code",
        width: '100%'
    });
    //events
    $("#btn_add_submit").on("click",function(){
      var isValid = $("#form_upload").valid();
      if(isValid){
        KTApp.block('#modal_upload .modal-content', {});
        //alert('test');
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/transactions/repayment/upload"); ?>",
            data : $('#form_upload').serialize(),
            async: true,
			cache: false,
			contentType: false,
			processData: false,
            dataType : "json",
            success : function(data,status){
                KTApp.unblock('#modal_upload .modal-content');
                if(data.status == true){
                    datatable.reload();
                    $('#modal_upload').modal('hide');
                    AlertUtil.showSuccess(data.message,5000);
                }else{
                    AlertUtil.showFailedDialogAdd(data.message);
                }                
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblock('#modal_upload .modal-content');
                AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
            }
        });  
      }
    })
    $('#modal_upload').on('hidden.bs.modal', function () {
       validator.resetForm();
    })

    return {
        validator:validator
    }
}

function initCreateForm(){
    //validator
    var validator = $( "#form_add" ).validate({
        ignore:[],
        rules: {
            area: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });   
    
    //events
    $("#btn_add_submit").on("click",function(){
      var isValid = $( "#form_add" ).valid();
      if(isValid){
        KTApp.block('#modal_add .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/areas/insert"); ?>",
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
            area_name: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });   

    //events
    $("#btn_edit_submit").on("click",function(){
      var isValid = $( "#form_edit" ).valid();
      if(isValid){
        KTApp.block('#modal_edit .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/areas/update"); ?>",
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
        $("#edit_area_id").val(groupObject.id);
        $("#edit_area_name").val(groupObject.area);
        //$("#edit_group_level").val(groupObject.group_level);
        //$("#edit_group_level").trigger('change');
    }
    
    return {
        validator:validator,
        populateForm:populateForm
    }
}

jQuery(document).ready(function() { 
    initDataTable();
    UploadForm = initUploadForm();
    //createForm = initCreateForm();    
    //editForm = initEditForm();
    initAlert();
});

</script>
