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

    $('#area').select2({ placeholder: "Select Area", width: '100%' });
    $('#id_unit').select2({ placeholder: "Select Unit", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
		var area        = $('[name="area"]').val();
        var unit        = $('[name="id_unit"]').val();
		var dateStart   = $('[name="date-start"]').val();
		var dateEnd     = $('[name="date-end"]').val();
		var permit      = $('[name="permit"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawnssummary/getbjreguler"); ?>",
			dataType : "json",
			data:{area:area,unit:unit,dateStart:dateStart,dateEnd:dateEnd},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
					var no = 1;
                    var totestimation = 0;
                    var totamount = 0;
                    var totgramation = 0;
                    var totqty = 0;
					$.each(response.data, function (index, data) {
                        //console.log(data);
                        template += '<tr class="rowappend" bgcolor="#F5F8F8">';
                        template +='<td class="text-center">'+no+'</td>';
                        template +='<td class="text-left">'+data.area+'</td>';
                        template +='<td class="text-left">'+data.unit_name+'</td>';
                        template +='<td class="text-center">'+data.no_sbk+'</td>';
                        template +='<td class="text-center">'+data.date_sbk+'</td>';
                        template +='<td class="text-center">'+data.nic+'</td>';
                        template +='<td class="text-left">'+data.customer+'</td>';
                        template +='<td class="text-right">'+convertToRupiah(data.estimation)+'</td>';
                        template +='<td class="text-right">'+convertToRupiah(data.amount)+'</td>';
                        template +='<td class="text-right"></td>';
                        template +='<td class="text-right"></td>';
                        template +='<td class="text-right"></td>';
                        template +='<td class="text-right"></td>';
                    
                        $.each(data.items, function (index, item) {
                            template +='<tr class="rowappend">';
                            template +='<td class="text-right" colspan="9"></td>';
                            template +='<td class="text-right">'+item.type+'</td>';
                            template +='<td class="text-center">'+item.qty+'</td>';
                            template +='<td class="text-right">'+item.karatase+' (krt)</td>';
                            template +='<td class="text-right">'+item.net+' (gr)</td>';
                            template +='</tr>';
                            totgramation += parseFloat(item.net);
                            totqty += parseInt(item.qty);
                        });

                        template +='</tr>';
                        no++;
                        totestimation += parseInt(data.estimation);
                        totamount += parseInt(data.amount);
					});
                    template += "<tr class='rowappend'>";
					template += "<td colspan='7' class='text-right'>Total</td>";
					template += "<td class='text-right'>"+convertToRupiah(totestimation)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totamount)+"</td>";
					template += "<td class='text-right'>-</td>";
					template += "<td class='text-center'>"+convertToRupiah(totqty)+"</td>";
					template += "<td class='text-right'>-</td>";
					template += "<td class='text-right'>"+totgramation+" Gram</td>";
					template += '</tr>';
                    $('.kt-section__content #tblpenaksir').append(template);                    
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
			$("#id_unit").empty();
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

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}

jQuery(document).ready(function() {
    initCariForm();
});



</script>
