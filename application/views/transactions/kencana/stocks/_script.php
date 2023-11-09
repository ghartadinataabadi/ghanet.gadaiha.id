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
                    url : "<?php echo base_url("api/kencana/stocks/delete/"); ?>"+targetId,
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
            url : "<?php echo base_url("api/kencana/stocks/show/"); ?>/"+targetId,
            dataType : "json",
            success : async function(response,status){
                clearForm();
                await initKencanaProduct();
                await initUnit();
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
                url: '<?php echo base_url("api/kencana/stocks"); ?>',
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
                field: 'unit',
                title: 'Unit',
                textAlign: 'center',
            },
            {
                field: 'image_url',
                title: 'image',
                textAlign: 'center',
                template:(e)=>{
                    return  e.image ? `<img src="${e.image_url}" class="img-fluid"/>` : '';
                }
            },
            {
                field: 'emaskencana',
                title: 'Emas Kencana',
                textAlign: 'center',
            },
		
            {
                field: 'price',
                title: 'Harga perpicis',
                textAlign: 'right',
                template: function(data){
                    return `Rp ${convertToRupiah(data.price)}`;
                }
            },
            {
                field: 'amount',
                title: 'Jumlah',
                textAlign: 'center',
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
	  var url = id ?  "<?php echo base_url("api/kencana/stocks/update"); ?>" :  "<?php echo base_url("api/kencana/stocks/insert"); ?>";
	  var formdata = new FormData(this);
      formdata.append('price', $('[name="price"]').val().split('.')[0].replaceAll(',','') );
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
		$('[name="id_unit"]').val(groupObject.id_unit);
		$('[name="id_kencana_product"]').val(groupObject.id_kencana_product);
		$('[name="price"]').val(groupObject.price);
		$('[name="amount"]').val(groupObject.amount);
        $('[name="description"]').val(groupObject.description);
		$('[name="reference_id"]').val(groupObject.reference_id);
		$('[name="date"]').val(groupObject.date);
    }
    
    return {
        populateForm:populateForm
    }
}

const modalShow = async () => {
    return new Promise(async (resolve, reject) => {
        clearForm();
        await initKencanaProduct();
        await initUnit();
        $('#modal_add').modal('show');
    })
}


const initUnit = () => {
    return new Promise( async (resolve, reject) => {
        const {status, data} = await  $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/datamaster/units"); ?>",
            cache: false,
            contentType: false,
            processData: false,
            dataType : "json",
        });  
        if(status){
            $('[name="id_unit"]').find('option').remove();
            const option = data.reduce((prev, curr) => 
            prev + `<option value="${curr.id}">${curr.name}</option>` 
             , '<option value="">Pilih Unit</option>');
            $('[name="id_unit"]').append(option);
            resolve(data)
        }else{
            reject('Something error');
        }
    });
}

const initKencanaProduct = () => {
    return new Promise( async (resolve, reject) => {
        const {status, data} = await  $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/kencana/product"); ?>",
            cache: false,
            contentType: false,
            processData: false,
            dataType : "json",
        });  
        if(status === 200){
            $('[name="id_kencana_product"]').find('option').remove();
            const option = data.reduce((prev, curr) => 
            prev + `<option value="${curr.id}">${curr.type}  -  ${curr.description || ''} dengan karatase ${curr.karatase} berat ${curr.weight} gram</option>` 
             , '<option value="">Pilih Emas Kencana</option>');
            $('[name="id_kencana_product"]').append(option);
            resolve(data)
        }else{
            reject('Something error');
        }
    });
}

const clearForm = () => {
    $('[name="id"]').val('');
    $('[name="id_kencana_product"]').val('');
    $('[name="id_unit"]').val('');
    $('[name="price"]').val('');
    $('[name="amount"]').val('');
    $('[name="description"]').val('');
    $('[name="reference_id"]').val('');
    $('[name="date"]').val('');
}

jQuery(document).ready(function() { 
    initDataTable();
    createForm = initCreateForm();
    editForm = initEditForm();
    initAlert();
});

</script>
