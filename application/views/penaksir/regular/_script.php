<script>

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
                    url : "<?php echo base_url("api/datamaster/areas/delete"); ?>",
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

        $('.rowspand').remove();
        $('.rowappend_ref').remove(); 
        $("#status").val("").trigger( "change" );     
        var targetId = $(this).data("id");
		//alert(targetId);
        KTApp.blockPage();
        $.ajax({
            type : 'GET',
			url : "<?php echo base_url("api/transactions/barangjaminan/get_byid"); ?>",
            data : {id:targetId},
            dataType : "json",
            success : function(response,status){
                KTApp.unblockPage();
                //console.log(response.data);
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

function btnreload(){
    //alert('test');
    datatable.reload();
}

function initEditForm(){
    //remove class 
    
    //validator
    var validator = $( "#form_edit" ).validate({
        ignore:[],
        rules: {
            status: {
                required: true,
            }
        },
        invalidHandler: function(event, validator) {   
            KTUtil.scrollTop();
        }
    });  
    
    $('#no_referensi').select2({ placeholder: "Pilih Nomor BTE", width: '100%' });
    $('#status').select2({ placeholder: "Pilih Status", width: '100%' });    

    //events
    $("#btn_edit_submit").on("click",function(){
      var isValid = $( "#form_edit" ).valid();
      if(isValid){
        KTApp.block('#modal_edit .modal-content', {});
        $.ajax({
            type : 'POST',
            url : "<?php echo base_url("api/transactions/regularpawnssummary/insert"); ?>",
            data : $('#form_edit').serialize(),
            dataType : "json",
            success : function(data,status){
                KTApp.unblock('#modal_edit .modal-content');
                if(data.status == true){
                    datatable.reload();
                    $('#modal_edit').modal('hide');
                    AlertUtil.showSuccess(data.message,5000);
                }else{
                    //AlertUtil.showFailed(data.message);
                    AlertUtil.showFailedDialogEdit(data.message);
                }                
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblock('#modal_edit .modal-content');
                AlertUtil.showFailedDialogEdit("Cannot communicate with server please check your internet connection");
            }
        });  
      }
    })

    $('#modal_edit').on('hidden.bs.modal', function () { validator.resetForm(); })

    var populateForm = function(groupObject){
        $("#no_sbk").val(groupObject.no_sbk);
        //$("#id_unit").val(groupObject.id_unit);
        $("#nic").val(groupObject.nic);
        $("#permit").val(groupObject.permit);        
        $("#id_customer").val(groupObject.id_customer);
        $("#customer").val(groupObject.customer);
        $("#amount").val(groupObject.amount);
        $("#estimation").val(groupObject.estimation);

        //get customers
        //customersList(groupObject.id_unit,groupObject.id_customer);
		//table description
		$('.rowappend_mdl').remove();
		var template = '';
		var type = '';
		var permit = '';
		template += "<tr class='rowappend_mdl'>";
		template += "<td class='text-center'>"+groupObject.no_sbk+"</td>";
		template += "<td class='text-center'>"+groupObject.customer+"</td>";
		template += "<td class='text-center'>"+groupObject.date_sbk+"</td>";
		template += "<td class='text-center'>"+groupObject.deadline+"</td>";
		template += "<td class='text-center'>"+convertToRupiah(groupObject.estimation)+"</td>";
		template += "<td class='text-center'>"+convertToRupiah(groupObject.amount)+"</td>";
		if(groupObject.type_item == 'P'){ type = 'Perhiasan'; }else{type = 'Latakan';}
		if(groupObject.permit == 'OJK-1'){ permit = 'New OJK'; }else{permit = groupObject.permit;}
		template += "<td class='text-right'>"+type+"</td>";
        template += "<td class='text-right'>"+permit+"</td>";
		template += "<td class='text-right'>";
		if(groupObject.description_1!=null){template += "- " + groupObject.description_1;}
		if(groupObject.description_2!=null){template += "<br>- " + groupObject.description_2;}
		if(groupObject.description_3!=null){template += "<br>- " + groupObject.description_3;}
		if(groupObject.description_4!=null){template += "<br>- " + groupObject.description_4;}
		template += "</td>";
		template += '</tr>';
		$('.kt-portlet__body #mdl_vwcicilan').append(template);

    }
    
    return {
        validator:validator,
        populateForm:populateForm
    }
}

function initDataTable(){
	var option = {
		data: {
			type: 'remote',
			source: {
				read: {
					url: '<?php echo base_url("api/transactions/regularpawnssummary/get_transaction"); ?>',
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
				title: 'NO SBK',
				sortable: 'asc',
				width:60,
				textAlign: 'center',
			},
			{
				field: 'nic',
				title: 'NO Cif',
				sortable: 'asc',
				width:60,
				textAlign: 'left',
			},
			{
				field: 'name',
				title: 'Unit',
				width:120,
				textAlign: 'left',
			},				
			{
				field: 'customer',
				title: 'Customers',
				sortable: 'asc',
				width:200,
				textAlign: 'left',
			},
			{
				field: 'date_sbk',
				title: 'Date SBK',
				sortable: 'asc',
				width:80,
				textAlign: 'left',
			},
// 			{
// 				field: 'deadline',
// 				title: 'Deadline',
// 				sortable: 'asc',
// 				width:80,
// 				textAlign: 'left',
// 			},
			// {
			// 	field: 'date_auction',
			// 	title: 'Lelang',
			// 	sortable: 'asc',
			// 	width:60,
			// 	textAlign: 'left',
			// },
// 			{
// 				field: 'estimation',
// 				title: 'Taksiran',
// 				sortable: 'asc',
// 				width:60,
// 				textAlign: 'left',
// 			},
			{
				field: 'amount',
				title: 'UP',
				sortable: 'asc',
				width:60,
				textAlign: 'left',
			},
			// {
			// 	field: 'admin',
			// 	title: 'Admin',
			// 	sortable: 'asc',
			// 	width:60,
			// 	textAlign: 'left',
			// },				
			// {
			// 	field: 'capital_lease',
			// 	title: 'SEWAMODAL',
			// 	sortable: 'asc',
			// 	width:60,
			// 	textAlign: 'left',
			// },				
			{
				field: 'type_item',
				title: 'Jenis BJ',
				sortable: 'asc',
				width:80,
				textAlign: 'left',
				template: function (row) {
					var result;
					if(row.type_item == 'P'){
						result = 'Perhiasan';
					}else{
						result = 'Latakan';
					}
					return result;
				}
			},{
				field: 'permit',
				title: 'Status',
				sortable: 'asc',
				width:60,
				textAlign: 'left',
				template: function (row) {
					var result;
					if(row.permit == 'OJK-1'){
						result = 'New OJK';
					}else{
						result = row.permit;
					}
					return result;
				}
			},{
				field: 'description_1',
				title: 'Description',
				sortable: 'asc',
				width:200,
				textAlign: 'left',
			},
			{
				field: 'action',
				title: 'Aksi',
				sortable: false,
				width: 100,
				overflow: 'visible',
				textAlign: 'center',
				autoHide: false,
				template: function (row) {
					var result ="";
					result = result + '<span data-id="' + row.id + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></span>';
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

function filterSelectOptions(selectElement, attributeName, attributeValue) {
    if (selectElement.data("currentFilter") != attributeValue) {
        selectElement.data("currentFilter", attributeValue);
        var originalHTML = selectElement.data("originalHTML");
        if (originalHTML)
            selectElement.html(originalHTML)
        else {
            var clone = selectElement.clone();
            clone.children("option[selected]").removeAttr("selected");
            selectElement.data("originalHTML", clone.html());
        }
        if (attributeValue) {
            selectElement.children("option:not([" + attributeName + "='" + attributeValue + "'],:not([" + attributeName + "]))").remove();
        }
    }
}

$('[name="jenis"]').on('change',function(){
	var jenis = $('[name="jenis"]').val();
	var tipe =  document.getElementById('tipe');     
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/types/get_byjenis"); ?>",
			dataType : "json",
			data:{jenis:jenis},
			success : function(response,status){
				if(response.status == true){
                    $("#tipe").empty();
                    var option = document.createElement("option");
                    option.value = "";
                    option.text = "All";
                    tipe.appendChild(option);
					$.each(response.data, function (index, data) {
                        var opt = document.createElement("option");
                        opt.value = data.type;
                        opt.text = data.type;
                        tipe.appendChild(opt);
					});					
				}
			}
		});
		filterSelectOptions($("#karatase"), "data-attribute", $(this).val());
});

$('[name="no_referensi"]').on('change',function(){
	var no_sbk = $('[name="no_referensi"]').val();
    var idunit = $('[name="id_unit"]').val();
    //console.log(no_sbk,idunit);		
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawnssummary/get_regularpawns"); ?>",
			dataType : "json",
			data:{idunit:idunit,no_sbk:no_sbk},
			success : function(response,status){
				if(response.status == true){
                    //console.log(response.data);
                    $('.rowappend_ref').remove();
                    var template = '';
                    var type = '';
                    //$.each(response.data, function (index, data) {
                        template += "<tr class='rowappend_ref' bgcolor='#EAFAF1'>";
                        template += "<td class='text-center'>"+response.data.no_sbk+"</td>";
                        template += "<td class='text-center'>"+response.data.name+"</td>";
                        template += "<td class='text-center'>"+response.data.date_sbk+"</td>";
                        template += "<td class='text-center'>"+response.data.deadline+"</td>";
                        template += "<td class='text-center'>"+convertToRupiah(response.data.estimation)+"</td>";
                        template += "<td class='text-center'>"+convertToRupiah(response.data.amount)+"</td>";
                        if(response.data.type_item == 'P'){ type = 'Perhiasan'; }else{type = 'Latakan';}
                        template += "<td class='text-right'>"+type+"</td>";
                        template += "<td class='text-right'>"+response.data.permit+"</td>";
                        template += "<td class='text-right'>";
                        if(response.data.description_1!=null){template += "- " + response.data.description_1;}
                        if(response.data.description_2!=null){template += "<br>- " + response.data.description_2;}
                        if(response.data.description_3!=null){template += "<br>- " + response.data.description_3;}
                        if(response.data.description_4!=null){template += "<br>- " + response.data.description_4;}
                        template += "</td>";
                        template += '</tr>';
                    //});
                    $('.kt-portlet__body #mdl_vwcicilan').append(template);   
                    //editItems(response.data.summary);    
                    //getType();                   
				}
			}
        });
});

$('[name="status"]').on('change',function(){
    var idunit = $('[name="id_unit"]').val();
    var customer = $('[name="id_customer"]').val(); 
    var status = $('[name="status"]').val(); 
    if(status==="PERPANJANGAN"){
        customersList(idunit,customer);
    }else{
        $("#no_referensi").empty(); 
        $('.rowspand').remove();
        $('.rowappend_ref').remove();
        //alert('test');
    }
});

const editItems=(data)=>{
    $('.rowspand').remove();    
    $.each(data, function (index, data) {
        const template = document.querySelector('#tblpenaksir').querySelector('[data-template="item"]').cloneNode(true);
        template.classList.remove('d-none');
        template.setAttribute('data-template','item-cloned');
        template.setAttribute('class','rowspand');
        template.querySelector('.jenis').setAttribute('name', 'jenis[]');
        template.querySelector('.jenis').value = data.model;       
         template.querySelector('.tipe').setAttribute('name', 'tipe[]');
        template.querySelector('.tipe').value = data.type;
        template.querySelector('.karatase').setAttribute('name', 'karatase[]');
        template.querySelector('.karatase').value = data.karatase;
        template.querySelector('.qty').setAttribute('name', 'qty[]');
        template.querySelector('.qty').value = data.qty;
        template.querySelector('.net').setAttribute('name', 'net[]');
        template.querySelector('.net').value = data.net;
        template.querySelector('.bruto').setAttribute('name', 'bruto[]');
        template.querySelector('.bruto').value = data.bruto;
        template.querySelector('.description').setAttribute('name', 'description[]');
        template.querySelector('.description').value = data.description;       
        document.querySelector('#tblpenaksir').querySelector('tbody').appendChild(template);
    });
}

const addItem = (event) => {
    const template = document.querySelector('#tblpenaksir').querySelector('[data-template="item"]').cloneNode(true);
    template.classList.remove('d-none');
    template.setAttribute('data-template','item-cloned');
    template.setAttribute('class','rowspand');
    template.querySelector('.jenis').setAttribute('name', 'jenis[]');
    template.querySelector('.jenis').setAttribute('required', true);
    template.querySelector('.tipe').setAttribute('name', 'tipe[]');
    template.querySelector('.tipe').setAttribute('required', true);
    template.querySelector('.karatase').setAttribute('name', 'karatase[]');
    template.querySelector('.karatase').setAttribute('required', true);
    template.querySelector('.qty').setAttribute('name', 'qty[]');
    template.querySelector('.qty').setAttribute('required', true);
    template.querySelector('.net').setAttribute('name', 'net[]');
    template.querySelector('.net').setAttribute('required', true);
    template.querySelector('.bruto').setAttribute('name', 'bruto[]');
    template.querySelector('.bruto').setAttribute('required', true);
    // template.querySelector('.stle').setAttribute('name', 'stle[]');
    // template.querySelector('.stle').setAttribute('required', true);
    template.querySelector('.description').setAttribute('name', 'description[]');
    document.querySelector('#tblpenaksir').querySelector('tbody').appendChild(template);
}

$(document).on('change', '.jenis', function(){
    var thisElement = $(this);
    var jenis = thisElement.parents('tr').find('.jenis').val();
    //filtering karatase
    filterSelectOptions($(thisElement.parents('tr').find('.karatase')), "data-attribute", jenis);

    //get filter type
    var tipe = thisElement.parents('tr').find('.tipe');
    var option = document.createElement("option");
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/datamaster/types/get_byjenis"); ?>",
        dataType : "json",
        data:{jenis:jenis},
        success : function(response,status){
            if(response.status == true){
                tipe.empty();
                option.value = "";
                option.text = "All";
                tipe.append(option);
                $.each(response.data, function (index, data) {
                    var opt = document.createElement("option");
                    opt.value = data.type;
                    opt.text = data.type;
                    tipe.append(opt);
                });					
            }
        }
    });
});

const deleteItem = (event) => {
    event.target.closest('tr').remove();
}

const customersList =(idunit,customer) => {
    //get filter type
    var no_referensi =  $('[name="no_referensi"]');
    var option = document.createElement("option");
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawnssummary/get_customers"); ?>",
			dataType : "json",
			data:{idunit:idunit,customer:customer},
			success : function(response,status){
				if(response.status == true){
                    no_referensi.empty();
                    option.value = "";
                    option.text = "All";
                    no_referensi.append(option);
					$.each(response.data, function (index, data) {
                        var opt = document.createElement("option");
                        opt.value = data.no_sbk;
                        opt.text = data.no_sbk +" - " + data.customer;
                        no_referensi.append(opt);
					});					
				}
			}
		});
}

jQuery(document).ready(function() { 
    initDataTable();
    editForm = initEditForm();
    initAlert();
});

</script>
