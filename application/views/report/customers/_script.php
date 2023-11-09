<script>
//globals
var cariForm;

function convertToRupiah(angka)
{
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return rupiah.split('',rupiah.length-1).reverse().join('');
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

function initCariForm(){
    //validator
    var validator = $("#form_bukukas").validate({
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

    $('#area').select2({ placeholder: "Select area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a status", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var cabang = $('[name="cabang"]').val();
        var unit = $('[name="id_unit"]').val();
        var statusrpt = $('#status').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/reportcustomers"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit,statusrpt:statusrpt},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var totamount = 0;
					var totadmin = 0;
                    var status="";

					$.each(response.data, function (index, data) {
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.unit_name+"</td>";
						template += "<td class='text-center'>"+data.no_sbk+"</td>";
						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
                        template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
                        if(data.date_repayment!=null){ var DateRepayment = moment(data.date_repayment).format('DD-MM-YYYY');}else{ var DateRepayment = "-";}
						template += "<td class='text-center'>"+DateRepayment+"</td>";
						template += "<td class='text-center'>"+data.capital_lease+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.estimation)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.admin)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
                        if(data.status_transaction=="L"){ status="Lunas";}
                        else if(data.status_transaction=="N"){ status="Aktif";}
                        template += "<td class='text-center'>"+status+"</td>";
                        template += '<td class="text-center"><span data-id="' + data.id + '" data-unit="' + data.id_unit + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></span></td>';
						template += '</tr>';
                        totamount +=parseInt(data.amount);
                        totadmin +=parseInt(data.admin);
						no++;						
					});
                    template += "<tr class='rowappend'>";
                    template += "<td colspan='8' class='text-center'></td>";
                    template += "<td class='text-right'>"+convertToRupiah(totadmin)+"</td>";
                    template += "<td class='text-right'>"+convertToRupiah(totamount)+"</td>";
                    template += "<td class='text-center'></td>";
					template += '</tr>';
					$('#tbl1').append(template);
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
    })

    return {
        validator:validator
    }
}

function initEditForm(){
    //alert('test');   

    //events
    $("#btn_edit_submit").on("click",function(){
      var isValid = $( "#modal_edit" ).valid();
      if(isValid){
        KTApp.block('#modal_edit .modal-content', {});
        // $.ajax({
        //     type : 'POST',
        //     url : "<?php echo base_url("api/datamaster/areas/update"); ?>",
        //     data : $('#form_edit').serialize(),
        //     dataType : "json",
        //     success : function(data,status){
        //         KTApp.unblock('#modal_edit .modal-content');
        //         if(data.status == true){
        //             datatable.reload();
        //             $('#modal_edit').modal('hide');
        //             AlertUtil.showSuccess(data.message,5000);
        //         }else{
        //             AlertUtil.showFailed(data.message);
        //         }                
        //     },
        //     error: function (jqXHR, textStatus, errorThrown){
        //         KTApp.unblock('#modal_edit .modal-content');
        //         AlertUtil.showFailedDialogEdit("Cannot communicate with server please check your internet connection");
        //     }
        // });  
      }
    })

    $('#modal_edit').on('hidden.bs.modal', function () {
       validator.resetForm();
    
    })

    // var populateForm = function(groupObject){
    //     $("#edit_area_id").val(groupObject.id);
    //     $("#edit_area_name").val(groupObject.area);
    //     //$("#edit_group_level").val(groupObject.group_level);
    //     //$("#edit_group_level").trigger('change');
    // }
    
    return {
        //validator:validator,
        //populateForm:populateForm
        
    }
}

function popEdit(el) {
    //popClear();
    $('.rowappend2').remove();
    var id = $(el).attr('data-id');
    var unit = $(el).attr('data-unit');   
    var template = '';
    //alert(unit);
    $('#customers').select2({
        placeholder: "Please select a Customers",
        width: '100%'
    });

    KTApp.block('#form_bukukas .kt-portlet__body', {});
    $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/showbyid"); ?>",
			dataType : "json",
			data:{id:id,unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				$('#customers').empty();
				$('#customers').append(`<option value="">Pilih Nasbah</option>`);
				if(response.data.options){
				    response.data.options.forEach(d=>{
				        	$('#customers').append(`<option value="${d.id}">${d.name}</option>`);
				    })
				}
                $('[name="id"]').val(response.data.id);
                $('[name="no_sbk"]').val(response.data.no_sbk);
                $('[name="ktp"]').val(response.data.ktp);
                $('[name="id_unit"]').val(response.data.id_unit);
                template += "<tr class='rowappend2'>";
                template += "<td class='text-center'>"+moment(response.data.date_sbk).format('DD-MM-YYYY')+"</td>";
                template += "<td class='text-center'>"+moment(response.data.deadline).format('DD-MM-YYYY')+"</td>";
                if(response.data.date_repayment!=null){ var DateRepayment = moment(response.data.date_repayment).format('DD-MM-YYYY');}else{ var DateRepayment = "-";}
                template += "<td class='text-center'>"+DateRepayment+"</td>";
                template += "<td class='text-center'>"+response.data.capital_lease+"</td>";
                template += "<td class='text-right'>"+convertToRupiah(response.data.estimation)+"</td>";
                template += "<td class='text-right'>"+convertToRupiah(response.data.admin)+"</td>";
                template += "<td class='text-right'>"+convertToRupiah(response.data.amount)+"</td>";
                if(response.data.status_transaction=="L"){ status="Lunas";}
                else if(response.data.status_transaction=="N"){ status="Aktif";}
                template += "<td class='text-center'>"+status+"</td>";
                template += '</tr>';
                $('#tbl2').append(template);
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
                KTApp.unblock('#form_bukukas .kt-portlet__body', {});
                $("#modal_edit").modal("show");
			}
        });
        
        $("#btn_edit_submit").on("click",function(){
                KTApp.block('#modal_edit .modal-content', {});
                $.ajax({
                    type : 'POST',
                    url : "<?php echo base_url("api/transactions/regularpawns/updatecustomers"); ?>",
                    data : $('#form_edit').serialize(),
                    dataType : "json",
                    success : function(data,status){
                        KTApp.unblock('#modal_edit .modal-content');
                        if(data.status == true){
                            //datatable.reload();
                            $('.rowappend').remove();
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
        });
}

// $(document).on('click','.btn-edit', function(e){
//     //alert('test');
// });


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

var typecabang = $('[name="cabang"]').attr('type');
if(typecabang == 'hidden'){
	$('[name="cabang"]').trigger('change');
}

jQuery(document).ready(function() {
    initCariForm();
    //editForm = initEditForm();
    $(document).on("click", ".btn_edit", function () {
                var el = $(this);
                popEdit(el);
            });
    initAlert();
});

$('[name="area"]').on('change',function(){
        var area = $('[name="area"]').val();
        var units =  $('[name="id_unit"]');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#unit").empty();
                var opt = document.createElement("option");
                    opt.value = "all";
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
var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}

</script>
