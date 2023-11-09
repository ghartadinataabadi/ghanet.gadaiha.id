<script>
//globals
var cariForm;

function convertToRupiah(angka)
{
    if(angka === '') return 0;
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

    $('#area').select2({ placeholder: "Please select a area", width: '100%' });
    $('#unit').select2({ placeholder: "Please select a Unit", width: '100%' });
    $('#permit').select2({ placeholder: "Please select a Permit", width: '100%' });

    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var permit = $('[name="permit"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/customers/current"); ?>",
			dataType : "json",
			data:{permit:permit, area:area},
			success : function(response,status){
                $('#tblcurrent').find('tbody').find('tr').remove();
                const {details, transaction_bigger, transaction_smaller, customer_per_person} = response.data;
                let tr = '<tr>';
                tr += `<td class="text-center">1</td>`;
                tr += `<td class="text-center">${customer_per_person}</td>`;
                tr += `<td class="text-center">${transaction_bigger}</td>`;
                tr += `<td class="text-center">${transaction_smaller}</td>`;
                tr += '</tr>';
                $('#tblcurrent').find('tbody').append(tr);
            },
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
    })

    //events
    $('#btncarinasabah').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var permit = $('[name="permit"]').val();
        var date = $('[name="date"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/getcustomers"); ?>",
			dataType : "json",
            data:{permit, area, unit, date},
            
            success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template ="";
					var no =1;
                    let int = 0;
					$.each(response.data, function (index, data) {

						template += "<tr class='rowappend' bgcolor='#F7F9F9' >";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.unit_name+"</td>";
						template += "<td class='text-left'>"+data.date_sbk+"</td>";
						template += "<td class='text-left'>"+data.deadline+"</td>";
                        template += "<td class='text-center'>"+data.no_cif+"</td>";
                        template += "<td class='text-left'>"+data.ktp+"</td>";
                        template += "<td class='text-left'>"+data.customer+"</td>";
						template += "<td class='text-center'>"+data.no_sbk+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
                        template += "<td class='text-left'>"+data.job+"</td>";
                          template += "<td class='text-left'>"+data.capital_lease+"</td>";
                               template += "<td class='text-left'>"+data.repayment+"</td>";
                        template += "</tr>";

                        int += parseInt(data.amount);

                        // template += '</tr>'; 
                        // if(data.payments!=""){
                        //     var amountMortages  =0;
                        //     var sewaMortages    =0;
                        //     var dendaMortages   =0;
                        //     $.each(data.payments, function (index, payments) {
                        //         if(payments.date_installment ==null || payments.date_installment =="1970-01-01"){ var datePayment=" Pelunasan"; }else{ var datePayment = moment(payments.date_installment).format('DD-MM-YYYY');}
                        //         template +="<tr class='rowappend'>";
                        //         template +="<td></td>";
                        //         template += "<td class='text-left'>"+data.unit_name+"</td>";
                        //         template += "<td class='text-left'>"+data.cust_name+"</td>";
                        //         template +="<td class='text-center'>"+payments.no_sbk+"</td>";
                        //         template +="<td class='text-center'>"+data.nic+"</td>";
                        //         template +="<td class='text-center'>"+moment(payments.date_kredit).format('DD-MM-YYYY')+"</td>";
                        //         template +="<td class='text-center'>"+datePayment+"</td>";
                        //         template +="<td class='text-center'></td>";
                        //         template +="<td class='text-right'>"+convertToRupiah(payments.amount)+"</td>";
                        //         template +="<td class='text-left'></td>";                               
                        //         template += '</tr>';
                        //         amountMortages +=parseInt(payments.amount);
                        //         sewaMortages +=parseInt(payments.capital_lease);
                        //         dendaMortages +=parseInt(payments.fine);
                        //     }); 
                        // }                    
                        no++;                        
					});
                    template += "<tr class='rowappend' bgcolor='#F7F9F9' >";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-center'></td>";
                    template += "<td class='text-left'>"+convertToRupiah(int)+"</td>";
                    template += "<td class='text-left'></td></tr>";
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

jQuery(document).ready(function() {     
    initCariForm();  
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
				opt.value = "0";
				opt.text = "All";
				units.append(opt)
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

</script>
