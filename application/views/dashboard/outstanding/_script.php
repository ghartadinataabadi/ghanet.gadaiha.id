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
		var dateStart = $('[name="date"]').val();
		var lasttrans = "";
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/dashboards/outstanding"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,id_unit:unit,date:dateStart},
			success : function(response,status){
				KTApp.unblockPage();
				var html = '';
				var foot = '';
				var int = 1;

                let totalNoaYesterday = 0;
                let totalUpYesterday = 0;
                let totalNoaReg = 0;
                let totalUpReg = 0;
                let totalNoaMor = 0;
                let totalUpMor = 0;
                let totalOut = 0;
                let totalUpaOstYesterday = 0;
                let totalNoaOstYesterday = 0;
                let totalNoaOstToday  = 0;
                let totalUpOstToday  = 0;
                let totalRepaymentTodayUp  = 0;
                let totalRepaymentTodayNoa  = 0;
                let totalOstNoa  = 0;
                let totalOstUp  = 0;
                let totalNoaOstYesterdayMor  = 0;
                let totalUpaOstYesterdayMor  = 0;
                let totalNoaOstTodayMor  = 0;
                let totalUpOstTodayMor  = 0;
                let totalRepaymentTodayNoaMor  = 0;
                let totalOstNoaMor  = 0;
                let totalRepaymentTodayUpMor  = 0;
                let totalOstUpMor  = 0;
                let totalDisbureNoa  = 0;
                let totalOst  = 0;
                let totalDisbureUp  = 0;
                let totalDisbureTicket = 0;

                $('.today').text(response.message.today);
                $('.yesterday').text(response.message.yesterday);
          
			
				$.each(response.data, function (index, data) {
                    let totalUpReg = 0;
                    let totalNoaReg = 0;
                    let totalNoaMor = 0;
                    let totalUpMor = 0;
                    let totalOut = 0;

                    totalNoaReg = (parseInt(data.ost_yesterday.noa_os_reguler) + parseInt(data.ost_today.noa_reguler))-(parseInt(data.ost_today.noa_rep_reguler));
                    totalUpReg = (parseInt(data.ost_yesterday.os_reguler)+ parseInt(data.ost_today.up_reguler))-(parseInt(data.ost_today.up_rep_reguler));
                    totalNoaMor = (parseInt(data.ost_yesterday.noa_os_mortages) + parseInt(data.ost_today.noa_mortages))-(parseInt(data.ost_today.noa_rep_mortages));
                    totalUpMor = (parseInt(data.ost_yesterday.os_mortages)+ parseInt(data.ost_today.up_mortages))-parseInt(data.ost_today.up_rep_mortages);
                    totalOut = parseInt(totalUpReg) + parseInt(totalUpMor); 

                    totalNoaYesterday  += parseInt(data.ost_yesterday.noa_os_reguler);
                    totalUpYesterday   += parseInt(data.ost_yesterday.os_reguler);

                    totalNoaOstYesterday += parseInt(data.ost_yesterday.noa_os_reguler);
                    totalUpaOstYesterday += parseInt(data.ost_yesterday.os_reguler);
                    totalNoaOstToday += parseInt(data.ost_today.noa_reguler);
                    totalUpOstToday += parseInt(data.ost_today.up_reguler);
                    totalRepaymentTodayUp += parseInt(data.ost_today.up_rep_reguler);
                    totalRepaymentTodayNoa += parseInt(data.ost_today.noa_rep_reguler);
                    totalOstNoa += parseInt(totalNoaReg);
                    totalOstUp += parseInt(totalUpReg);

                    totalNoaOstYesterdayMor += parseInt(data.ost_yesterday.noa_os_mortages);
                    totalUpaOstYesterdayMor += parseInt(data.ost_yesterday.os_mortages);
                    totalNoaOstTodayMor += parseInt(data.ost_today.noa_mortages);
                    totalUpOstTodayMor += parseInt(data.ost_today.up_mortages);
                    totalRepaymentTodayUpMor += parseInt(data.ost_today.up_rep_mortages);
                    totalRepaymentTodayNoaMor += parseInt(data.ost_today.noa_rep_mortages);
                    totalOstNoaMor += parseInt(totalNoaMor);
                    totalOstUpMor += parseInt(totalUpMor);

                    totalOst +=  parseInt(totalUpReg+totalUpMor);

                    totalDisbureNoa += parseInt(data.total_disburse.noa);
                    totalDisbureUp += parseInt(data.total_disburse.credit);
                    totalDisbureTicket += parseInt(data.total_disburse.tiket);


					html += '<tr>'
					html += '<td class="text-center">'+ int +'</td>';
					html += '<td class="text-center">'+ data.name +'</td>';
					html += '<td class="text-center">'+ data.ost_yesterday.noa_os_reguler +'</td>';
					html += '<td class="text-center">'+ convertToRupiah(data.ost_yesterday.os_reguler) +'</td>';
					html += '<td class="text-center">'+ data.ost_today.noa_reguler +'</td>';
					html += '<td class="text-center">'+ convertToRupiah( data.ost_today.up_reguler) +'</td>';
					html += '<td class="text-center">'+ data.ost_today.noa_rep_reguler +'</td>';
					html += '<td class="text-center">'+ convertToRupiah( data.ost_today.up_rep_reguler) +'</td>';
					html += '<td class="text-center">'+ totalNoaReg +'</td>';
					html += '<td class="text-center">'+  convertToRupiah(totalUpReg) +'</td>';
					html += '<td class="text-center">'+ data.ost_yesterday.noa_os_mortages +'</td>';
					html += '<td class="text-center">'+  convertToRupiah(data.ost_yesterday.os_mortages) +'</td>';
					html += '<td class="text-center">'+ data.ost_today.noa_mortages +'</td>';
					html += '<td class="text-center">'+  convertToRupiah(data.ost_today.up_mortages) +'</td>';
					html += '<td class="text-center">'+ data.ost_today.noa_rep_mortages; +'</td>';
					html += '<td class="text-center">'+ convertToRupiah( data.ost_today.up_rep_mortages) +'</td>';
					html += '<td class="text-center">'+ totalNoaMor +'</td>';
					html += '<td class="text-center">'+  convertToRupiah(totalUpMor) +'</td>';
					html += '<td class="text-center">'+  convertToRupiah(totalOut) +'</td>';
					html += '<td class="text-center">'+ data.total_disburse.noa +'</td>';
					html += '<td class="text-center">'+  convertToRupiah(data.total_disburse.credit) +'</td>';
					html += '<td class="text-center">'+ data.total_disburse.tiket.toFixed(2) +'</td>';
					html += '</tr>'
                    int++;
            });

				$('.table').find('tbody').find('tr').remove();
				$('.table').find('tbody').html(html);
                foot += '<tr></tr>'
                foot += `<td colspan="2">Summary</td>`
                foot += `<td>${totalNoaOstYesterday}</td>`
                foot += `<td>${ convertToRupiah(totalUpaOstYesterday)}</td>`
                foot += `<td>${totalNoaOstToday}</td>`
                foot += `<td>${ convertToRupiah(totalUpOstToday)}</td>`
                foot += `<td>${totalRepaymentTodayNoa}</td>`
                foot += `<td>${ convertToRupiah(totalRepaymentTodayUp)}</td>`
                foot += `<td>${totalOstNoa}</td>`
                foot += `<td>${ convertToRupiah(totalOstUp)}</td>`
                foot += `<td>${totalNoaOstYesterdayMor}</td>`
                foot += `<td>${ convertToRupiah(totalUpaOstYesterdayMor)}</td>`
                foot += `<td>${totalNoaOstTodayMor}</td>`
                foot += `<td>${ convertToRupiah(totalUpOstTodayMor)}</td>`
                foot += `<td>${totalRepaymentTodayNoaMor}</td>`
                foot += `<td>${ convertToRupiah(totalRepaymentTodayUpMor)}</td>`
                foot += `<td>${totalOstNoaMor}</td>`
                foot += `<td>${ convertToRupiah(totalOstUpMor)}</td>`
                foot += `<td>${ convertToRupiah(totalOst)}</td>`
                foot += `<td>${totalDisbureNoa}</td>`
                foot += `<td>${ convertToRupiah(totalDisbureUp)}</td>`
                foot += `<td>${parseInt(totalDisbureTicket)/(parseInt(int)-1).toFixed(2)}</td>`
                foot += '<tr></tr>'
			
            	$('.table').find('tfoot').find('tr').remove();
				$('.table').find('tfoot').html(foot);

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
			$("#unit").empty();
			units.append('<option value="0">All</option>');
			for (var i = 0; i < response.data.length; i++) {
				var opt = document.createElement("option");
				opt.value = response.data[i].code;
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

jQuery(document).ready(function() {
    initCariForm();
});

</script>
