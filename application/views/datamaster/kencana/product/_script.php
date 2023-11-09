<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;


$(".currency").inputmask('decimal', {
    'alias': 'numeric',
    'groupSeparator': ',',
    'autoGroup': true,
    'digits': 2,
    'radixPoint': ".",
    'digitsOptional': false,
    'allowMinus': false,
    'placeholder': ''
});

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
                    url : "<?php echo base_url("api/kencana/product/delete/"); ?>"+targetId,
                    dataType : "json",
                    success : function(data,status){
                        KTApp.unblockPage();
                        if(data.data !== false){
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
            url : "<?php echo base_url("api/kencana/product/show/"); ?>/"+targetId,
            dataType : "json",
            success : function(response,status){
                KTApp.unblockPage();
                if(response.data !== false){
                    //populate form
                    editForm.populateForm(response.data);
                    $('#modal_add').modal('show');
                }else{
                    AlertUtil.showFailed(data.message);
                    $('#modal_add').modal('show');
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
                url: '<?php echo base_url("api/kencana/product"); ?>',
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
                field: 'image_url',
                title: 'image',
                textAlign: 'center',
                template:(e)=>{
                    return  e.image ? `<img src="${e.image_url}" class="img-fluid"/>` : '';
                }
            },
            {
                field: 'karatase',
                title: 'Karatase',
                textAlign: 'center',
            },
            {
                field: 'type',
                title: 'Jenis Barang',
                textAlign: 'center',
            },
            {
                field: 'price_base',
                title: 'Harga Pokok',
                textAlign: 'right',
                template: function(data){
                    return `Rp ${convertToRupiah(data.price_base)}`;
                }
            },
            {
                field: 'price_sale',
                title: 'Harga Jual',
                textAlign: 'right',
                template: function(data){
                    return `Rp ${convertToRupiah(data.price_sale)}`;
                }
            },
            {
                field: 'weight',
                title: 'Berat',
                textAlign: 'center',
                template: function(data){
                    return `${data.weight} gram`;
                }
            },
            {
                field: 'description',
                title: 'Deskripsi',
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
    
    //events
    $(document).on("submit",'.form',function(e){
	e.preventDefault();
      var isValid = $( "#form_add" ).valid();
      var id = $('[name="id"]').val();
	  var url = id ?  "<?php echo base_url("api/kencana/product/update"); ?>" :  "<?php echo base_url("api/kencana/product/insert"); ?>";
	  var formdata = new FormData(this);
      formdata.append('price_base', $('[name="price_base"]').val().split('.')[0].replaceAll(',','') );
      formdata.append('price_sale', $('[name="price_sale"]').val().split('.')[0].replaceAll(',','') );
      if(isValid){
        KTApp.block('#modal_add .modal-content', {});
        $.ajax({
            type : 'POST',
            url : url,
            data : formdata,
			cache: false,
			contentType: false,
			processData: false,
            dataType : "json",
            success : function(data,status){
                KTApp.unblock('#modal_add .modal-content');
				console.log(data.data);
                if(data.data !== false){
                    datatable.reload();
					$('#modal_add').modal('hide');
					$('#modal_edit').modal('hide');
                    AlertUtil.showSuccess(data.message,5000);
                }else{
                    AlertUtil.showFailedDialogAdd(data.message);
                }
				$('#modal_add').on('hidden.bs.modal', function () {
					validator.resetForm();
				})
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblock('#modal_add .modal-content');
                AlertUtil.showFailedDialogAdd("Cannot communicate with server please check your internet connection");
            }
        });  
      }
    })

    return {
        validator:validator
    }
}

function initEditForm(){
    var populateForm = function(groupObject){
        $('[name="id"]').val(groupObject.id);
		$('[name="type"]').val(groupObject.type);
		$('[name="price_sale"]').val(groupObject.price_sale);
		$('[name="price_base"]').val(groupObject.price_base);
		$('[name="weight"]').val(groupObject.weight);
        $('[name="description"]').val(groupObject.description);
		$('[name="karatase"]').val(groupObject.karatase);
    }
    
    return {
        populateForm:populateForm
    }
}

const modalShow = () => {
    clearForm();
    $('#modal_add').modal('show');
}

const clearForm = () => {
    $('[name="id"]').val('');
    $('[name="type"]').val('');
    $('[name="price_sale"]').val('');
    $('[name="price_base"]').val('');
    $('[name="weight"]').val('');
    $('[name="description"]').val('');
    $('[name="karatase"]').val('');
}

jQuery(document).ready(function() { 
    initDataTable();
    createForm = initCreateForm();
    editForm = initEditForm();
    initAlert();
});

</script>
