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

    $('#area').select2({ placeholder: "Please select a area", width: '100%' });
    $('#unit').select2({ placeholder: "Please select a Unit", width: '100%' });

    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/unitsdailycash/bukubank"); ?>",
			dataType : "json",
			data:{id_unit:unit,dateStart:dateStart,dateEnd:dateEnd,area:area},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
                    var currentSaldo = 0;
                    var TotSaldoIn = 0;
                    var TotSaldoOut = 0;
                    var no = 0;
					$.each(response.data, function (index, data) {
                        no++;
                    var date = moment(data.date).format('DD-MM-YYYY');
                    var month = moment(data.date).format('MMMM');
                    var year = moment(data.date).format('YYYY');
                    var cashin=0;
                    var cashout=0;
                    if(data.type=='CASH_IN'){cashin= data.amount; currentSaldo +=  parseInt(data.amount); TotSaldoIn +=  parseInt(data.amount);}
                    if(data.type=='CASH_OUT'){cashout= data.amount; currentSaldo -=  parseInt(data.amount); TotSaldoOut +=  parseInt(data.amount);}
                    
                    template += '<tr class="rowappend">';
                    template +='<td class="text-center">'+no+'</td>';
                    
                    template +='<td>'+date+'</td>';
                    template +='<td class="text-center">'+month+'</td>';
                    template +='<td class="text-center">'+year+'</td>';
                    template +='<td>'+data.description+'</td>';
                    template +='<td class="text-right">'+convertToRupiah(cashin)+'</td>';
                    template +='<td class="text-right">'+convertToRupiah(cashout)+'</td>';
                    template +='<td class="text-right">'+convertToRupiah(currentSaldo)+'</td>';
                    template +='</tr>';
					});

                    template += '<tr class="rowappend">';
                    template +='<td colspan="5" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(TotSaldoIn)+'</b></td>';
                    template +='<td class="text-right"><b>'+convertToRupiah(TotSaldoOut)+'</b></td>';
                    template +='<td class="text-right"><b>'+convertToRupiah(currentSaldo)+'</b></td>';
                    template +='</tr>';
					$('.kt-section__content #tblbukukas').append(template);
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

</script>
