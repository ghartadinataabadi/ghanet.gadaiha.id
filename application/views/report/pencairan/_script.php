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
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var cabang = $('[name="cabang"]').val();
        var unit = $('[name="id_unit"]').val();
		var date = $('[name="date"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/dashboards/pencairandashboard"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit,date:date},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
					var no = 1;
					var totNoa = 0;
					var total = 0;
					$.each(response.data, function (index, data) {
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.name+"</td>";
						template += "<td class='text-left'>"+data.area+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
						template += '</tr>';
						no++;
                        total +=data.amount;
                        //totNoa +=data.noa;
					});
                    template += '<tr class="rowappend">';
                    template +='<td colspan="3" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(total)+'</b></td>';                    
                    template +='</tr>';

					$('.kt-section__content table').append(template);
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

    $('#btncari_monthly').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="unit"]').val();
		var date = $('[name="date"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/report/pencairan/monthly"); ?>",
			dataType : "json",
			data:{area:area,unit:unit,date:date},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
					var no = 1;
					var total = 0;
					var totalall = 0;
					var totregular = 0;
					var totmortage = 0;
					$.each(response.data, function (index, data) {
                        var month = moment(date).format('MMMM');
                        var year = moment(date).format('Y');
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
                        template += "<td class='text-left'>"+data.area+"</td>";
						template += "<td class='text-left'>"+data.name+"</td>";
						template += "<td class='text-center'>"+month+"</td>";
						template += "<td class='text-center'>"+year+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.summary.upRegular)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.summary.upMortage)+"</td>";
                        total = parseInt(data.summary.upRegular) + parseInt(data.summary.upMortage);
						template += "<td class='text-right'>"+convertToRupiah(total)+"</td>";
						template += '</tr>';
						no++;
                        totalall +=total;
                        totregular +=parseInt(data.summary.upRegular);
                        totmortage +=parseInt(data.summary.upMortage);
					});
                    totalall = totalall;
                    totregular = totregular;
                    totmortage = totmortage;
                    template += '<tr class="rowappend">';
                    template +='<td colspan="5" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(totregular)+'</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(totmortage)+'</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(totalall)+'</b></td>';                    
                    template +='</tr>';

					$('.kt-section__content table').append(template);
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
        var area = $(this).val();
        var units =  document.getElementById('unit');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#unit").empty();
                var opt = document.createElement("option");
                    opt.value = "all";
                    opt.text ="All";
                    units.appendChild(opt);
                for (var i = 0; i < response.data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = response.data[i].id;
                    opt.text = response.data[i].name;
                    units.appendChild(opt);
                }
            }
        });
    });

jQuery(document).ready(function() {
    initCariForm();
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
