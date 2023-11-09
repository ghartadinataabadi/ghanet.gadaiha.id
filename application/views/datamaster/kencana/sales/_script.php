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
                    url : "<?php echo base_url("api/kencana/sales/delete/"); ?>"+targetId,
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
            url : "<?php echo base_url("api/kencana/sales/show/"); ?>/"+targetId,
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
                url: '<?php echo base_url("api/kencana/sales/calculate"); ?>',
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
                field: 'area',
                title: 'Area',
                textAlign: 'center',
            },
            {
                field: 'reference_code',
                title: 'Kode Reference',
            },
            {
                field: 'date',
                title: 'Tanggal',
                textAlign: 'center',
            },
            {
                field: 'total_quantity',
                title: 'Total Barang',
                textAlign: 'center',
            },
            {
                field: 'total_price',
                title: 'Total Harga',
                textAlign: 'right',
                template: function(row){
                    return convertToRupiah(row.total_price);
                }
            },   
            {
                field: 'description',
                title: 'Deskripsi Barang',
                textAlign: 'center',
            },   
          ],
          layout:{
            header:true
          }
    }
    datatable = $('#kt_datatable').KTDatatable(option);
    $('#id_area').on('change', function() {
        datatable.search($(this).val().toLowerCase(), 'id_area');
    });
    $('#id_unit').on('change', function() {
        datatable.search($(this).val().toLowerCase(), 'id_unit');
    });
    $('#date_start').on('change', function() {
        datatable.search($(this).val().toLowerCase(), 'date_start');
    });
    $('#date_end').on('change', function() {
        datatable.search($(this).val().toLowerCase(), 'date_end');
    });
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
	  var url = id ?  "<?php echo base_url("api/kencana/sales/update"); ?>" :  "<?php echo base_url("api/kencana/sales/insert"); ?>";
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
            prev + `<option value="${curr.id}">${curr.type} dengan karatase ${curr.karatase} berat ${curr.weight} gram ${curr.description || ''}</option>` 
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

const excelHandler = (event) => {
    event.preventDefault();
    const id_unit = $('#id_unit').val();
    const id_area = $('#id_area').val();
    const date_start = $('#date_start').val();
    const date_end = $('#date_end').val();
    window.location.href = `<?php echo base_url('datamaster/kencana/sales_excel');?>?id_unit=${id_unit}&id_area=${id_area}&date_start=${date_start}&date_end=${date_end}`
}

$('[name="id_area"]').on('change',function(){
        var area = $('[name="id_area"]').val();
        var units =  $('[name="id_unit"]');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#id_unit").empty();
				var opt = document.createElement("option");
				opt.value = "0";
				opt.text = "All";
				units.append(opt);
                for (var i = 0; i < response.data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = response.data[i].id;
                    opt.text = response.data[i].name;
                    units.append(opt);
                }
            }
        });
});

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

$('#id_area').select2({ placeholder: "Select Area", width: '100%' });
$('#id_unit').select2({ placeholder: "Select Unit", width: '100%' });
var type = $('[name="id_area"]').attr('type');
if(type == 'hidden'){
    $('[name="id_area"]').trigger('change');
}
var typecabang = $('[name="cabang"]').attr('type');
if(typecabang == 'hidden'){
	$('[name="cabang"]').trigger('change');
}

const initArea = () => {
    $.ajax({
        url: "<?php echo base_url('api/datamaster/areas');?>",
        type: 'GET',
        dataType: 'JSON',
        success: function(res){
            const { data } = res; 
            const options = data.reduce((prev, curr) => prev + `<option value="${curr.id}">${curr.area}</option>`, `<option value="">All</option>`)
            $('#id_area').append(options);
        }
    })
}

jQuery(document).ready(function() { 
    initDataTable();
    createForm = initCreateForm();
    editForm = initEditForm();
    initAlert();
    initArea();
});

</script>
