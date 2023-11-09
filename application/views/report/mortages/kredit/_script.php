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

    $('#area').select2({ placeholder: "Select a area", width: '100%' });
    $('#unit').select2({ placeholder: "Select a Unit", width: '100%' });
    $('#sbk').select2({ placeholder: "Select a No. SBK", width: '100%' });
    $('#status').select2({ placeholder: "Select a status", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area        = $('[name="area"]').val();
        var unit        = $('[name="id_unit"]').val();
        var sbk         = $('[name="sbk"]').val();
        var status      = $('[name="status"]').val();
		var dateStart   = $('[name="date-start"]').val();
		var dateEnd     = $('[name="date-end"]').val();
        console.log(unit);
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/mortages/getcredit"); ?>",
			dataType : "json",
			data:{area:area,unit:unit,sbk:sbk,status:status,dateStart:dateStart,dateEnd:dateEnd},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
					var no = 1;
					var amount = 0;
                    var admin = 0;
                    var totcicilan = 0;
                    var jumcicilan=0;
                    var saldocicilan=0;
					var status = "";
					var pelunasan = "";
					var description = "";
					$.each(response.data, function (index, data) {
						template += "<tr class='rowappend' bgcolor='#F7F9F9' >";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.unit_name+"</td>";
                        template += "<td class='text-left'>"+data.cust_name+"</td>";
                        template += "<td class='text-center'>"+data.no_sbk+"</td>";
						template += "<td class='text-center'>"+data.nic+"</td>";
						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
						template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(data.amount_loan)+"</td>";
                        template += "<td class='text-right'> - </td>";
                        if(data.status_transaction=='L'){status='Lunas';}else{status='Aktif';}
                        template += "<td class='text-center'>"+status+"</td>";
                        template += '</tr>'; 
                        if(data.payments!=""){
                            var amountMortages  =0;
                            var sewaMortages    =0;
                            var dendaMortages   =0;
                            $.each(data.payments, function (index, payments) {
                                if(payments.date_installment ==null || payments.date_installment =="1970-01-01"){ var datePayment=" Pelunasan"; }else{ var datePayment = moment(payments.date_installment).format('DD-MM-YYYY');}
                                template +="<tr class='rowappend'>";
                                template +="<td></td>";
                                template += "<td class='text-left'>"+data.unit_name+"</td>";
                                template += "<td class='text-left'>"+data.cust_name+"</td>";
                                template +="<td class='text-center'>"+payments.no_sbk+"</td>";
                                template +="<td class='text-center'>"+data.nic+"</td>";
                                template +="<td class='text-center'>"+moment(payments.date_kredit).format('DD-MM-YYYY')+"</td>";
                                template +="<td class='text-center'>"+datePayment+"</td>";
                                template +="<td class='text-center'></td>";
                                template +="<td class='text-right'>"+convertToRupiah(payments.amount)+"</td>";
                                template +="<td class='text-left'></td>";                               
                                template += '</tr>';
                                amountMortages +=parseInt(payments.amount);
                                sewaMortages +=parseInt(payments.capital_lease);
                                dendaMortages +=parseInt(payments.fine);
                            }); 
                        }                    
                        no++;                        
					});
					$('.kt-section__content .table').append(template);
				//}
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

$('[name="area"]').on('change',function(){
        var area = $('[name="area"]').val();
        var units =  $('[name="id_unit"]');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            console.log(response);
            if (status) {
                $("#unit").empty();
				var opt = document.createElement("option");
				opt.value = "0";
				opt.text = "All"
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

$('[name="id_unit"]').on('change',function(){
	var unit = $('[name="id_unit"]').val();
	var sbk =  $('[name="sbk"]');
	var url_data = $('#url_get_sbk').val() + '/' + unit;
	$.get(url_data, function (data, status) {
		var response = JSON.parse(data);
		if (status) {
			$("#sbk").empty();
			sbk.append('<option value="0">All</option>');
			for (var i = 0; i < response.data.length; i++) {
				var opt = document.createElement("option");
				opt.value = response.data[i].no_sbk;
				opt.text = response.data[i].no_sbk+' - '+ response.data[i].cust_name;
				sbk.append(opt);
			}
		}
	});
});

var typeunit = $('[name="id_unit"]').attr('type');
if(typeunit == 'hidden'){
	$('[name="id_unit"]').trigger('change');
}

jQuery(document).ready(function() {
    initCariForm();
});

</script>
