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
    $('#method').select2({ placeholder: "Select Method", width: '100%' });
    $('#status').select2({ placeholder: "Select a status", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
		var	area = $('[name="area"]').val();
        var cabang = $('[name="cabang"]').val();
        var unit = $('[name="id_unit"]').val();
		var date = $('[name="date-start"]').val();
		var method = $('[name="method"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/dashboards/pendapatan_daily"); ?>",
			dataType : "json",
			data:{area:area,unit:unit,cabang:cabang,date:date,method},
			success : function(response,status){
				KTApp.unblockPage();
				var body = '';
				var head = '';
				var int = 0;
				var summary = [];
				var foot = '';
				$.each(response.data, function (index, data) {
					if(index > 0){
						body += '<tr>';
						body += '<td>'+int+'</td>'
						body += '<td>'+data.name+'</td>'
						body += '<td>'+data.area+'</td>'
						$.each(data.dates, function (index, date) {
							body += '<td class="text-right">'+convertToRupiah(date)+'</td>';
							if(summary[index]){
								summary[index] = parseInt(summary[index]) + parseInt(date);
							}else{
								summary[index] = parseInt(date);
							}
						});
						body += '</tr>';
					}else{
						head += '<tr>';
						head += '<td>'+data.no+'</td>'
						head += '<td>'+data.unit+'</td>'
						head += '<td>'+data.area+'</td>'
						$.each(data.dates, function (index, date) {

							head += '<td class="text-right">'+date+'</td>';
						})
						head += '</tr>';
					}
					int++;
				});
			    foot += '<tr>';
				foot += '<td colspan="3" class="text-right">Total</td>'
				$.each(summary, function (index, date) {
					foot += '<td class="text-right">'+convertToRupiah(date)+'</td>';
				});
				foot += '</tr>';

				$('.table').find('tbody').find('tr').remove();
				$('.table').find('thead').find('tr').remove();
				$('.table').find('tfoot').find('tr').remove();
				$('.table').find('thead').html(head);
				$('.table').find('tbody').html(body);
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

const excel = ()=>{
	const id_unit = $('#unit').val() ? $('#unit').val() : '';
	const area = $('#area').val();
	const method = $('#method').val();
	const date = $('[name="date-start"]').val();
	window.location.href = `<?php echo base_url();?>/dashboards/pendapatan_excel?date-start=${date}&id_unit=${id_unit}&area=${area}&method=${method}`;
}

jQuery(document).ready(function() {
    initCariForm();
});

</script>
