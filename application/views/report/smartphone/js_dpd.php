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

// function convertToRupiah(angka){
//    var reverse = angka.toString().split('').reverse().join(''),
//    ribuan = reverse.match(/\d{1,3}/g);
//    ribuan = ribuan.join('.').split('').reverse().join('');
//    return ribuan;
//  }

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
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
        var dateLast = $('[name="date-last"]').val();
		var permit = $('[name="permit"]').val();
        var packet = $('[name="packet"]').val();
        var datenow = $('[name="date-now"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/dpd"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit,dateStart:dateStart,dateEnd:dateEnd,permit:permit, packet},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var amount = 0;
					var admin = 0;
					var totalDPD = 0;
					var totalDenda = 0;
					var totalPelunasan = 0;
					var totalTafsiran = 0;
                    var status="";

					$.each(response.data, function (index, data) {
                        
						// if(dateEnd > dateLast){
						// var dpd = parseInt(date_between(`${data.deadline}`, datenow)) - 1;
                        // }else{
 						var dpd = parseInt(date_between(`${data.deadline}`, datenow)) - 1;                           
                        // }
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-center'>"+data.name+"</td>";
						template += "<td class='text-center'>"+data.no_sbk+"</td>";
						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
                        template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
                        if(data.date_repayment!=null){ var DateRepayment = moment(data.date_repayment).format('DD-MM-YYYY');}else{ var DateRepayment = "-";}
						template += "<td class='text-center'>"+DateRepayment+"</td>";
						template += "<td>"+data.customer_name+"</td>";
						template += "<td>"+data.address+"</td>";
						template += "<td>"+data.mobile+"</td>";
						template += "<td class='text-center'>"+data.capital_lease+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.estimation)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.admin)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
                        if(data.status_transaction=="L"){ status="Lunas";}
                        else if(data.status_transaction=="N"){ status="Aktif";}
						template += "<td class='text-center'>"+dpd+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.tafsiran_sewa)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(calculateDenda(data.amount, dpd))+"</td>";
						var up = parseInt(calculateDenda(data.amount, dpd));
						var calcup = up + parseInt(data.tafsiran_sewa) +parseInt(data.amount);
						template += "<td class='text-right'>"+convertToRupiah(calcup)+"</td>";
						template += '</tr>';
						no++;
						totalDenda += calculateDenda(data.amount, dpd);
						amount += parseInt(data.amount);
						admin += parseInt(data.admin);
						totalTafsiran += parseInt(data.tafsiran_sewa);
						totalPelunasan += parseInt(calcup);
						totalDPD += dpd;

					});
					template += "<tr class='rowappend'>";
					template += "<td colspan='11' class='text-right'>Total</td>";
					template += "<td class='text-right'>"+convertToRupiah(admin)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(amount)+"</td>";
					template += "<td class='text-right'>"+totalDPD+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totalTafsiran)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totalDenda)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totalPelunasan)+"</td>";
					template += "<td class='text-right'></td>";
					template += '</tr>';
					$('.kt-section__content table').append(template);
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
				opt.value = 0;
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

function date_between(start, end){
    var date1 = new Date(`${start}`); 
    var date2 = new Date(`${end}`); 
    
    // To calculate the time difference of two dates 
    var Difference_In_Time = date2.getTime() - date1.getTime(); 
    
    // To calculate the no. of days between two dates 
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24); 
    return Difference_In_Days+1;
}

$(document).ready(function(){
    $('#btncari').trigger('click');
})


</script>
