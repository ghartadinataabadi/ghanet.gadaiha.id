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

function formatNumber(angka){
    var clean = angka.replace(/\D/g, '');
    return clean;
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
    var validator = $("#form_pendapatan").validate({
        ignore:[],
        rules: {
            area: {
                required: true,
            },
            // cabang: {
            //     required: true,
            // },
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
    $('#category').select2({ placeholder: "Select a Category", width: '100%' });
    

    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area        = $('[name="area"]').val();
        var cabang        = $('[name="cabang"]').val();
        var unit        = $('[name="id_unit"]').val();
        var month        = $('[name="month"]').val();
        var year        = $('[name="year"]').val();
        var period_month        = $('[name="period_month"]').val();
        var period_year        = $('[name="period_year"]').val();
        var percentage = $('[name="percentage"]').val();
        KTApp.block('#form_pendapatan .kt-portlet__body', {});
        var totalTenor = 0;
        var totalMontly = 0;
        var totalPayment = 0;
        var totalAmount = 0;
        $('#tblsm').find('tbody').find('tr').remove();
        $('#tblsm').find('tfoot').find('tr').remove();
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/unitsdailycash/coc"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit, month, year, percentage, period_month, period_year},
			success : function(response,status){
				KTApp.unblockPage();
                // $('.kt-section__content #tblsm').append(template);
                let html = '';
                let no = 1;
                response.data.forEach(data=>{
                  const { name, area,amount, description, id, date, tenor, coc_daily, coc_payment } = data;
                  totalPayment += parseInt(coc_payment);
                  totalAmount += parseInt(amount);
                  totalMontly +=parseInt (coc_daily);
                  totalTenor += parseInt(tenor);
                  html += '<tr>';

                  html += `<td>${no}</td>`;
                  html += `<td>${area}</td>`;
                  html += `<td>${name}</td>`;
                  html += `<td class="text-left">${description}</td>`;
                  html += `<td class="text-right">${date}</td>`;
                  html += `<td class="text-right">${convertToRupiah(amount)}</td>`;
                  html += `<td class="text-right">${tenor}</td>`;
                  html += `<td class="text-right">${convertToRupiah(coc_daily)}</td>`;
                  html += `<td class="text-right">${convertToRupiah(coc_payment)}</td>`;
                  html += '</tr>';
                  no++;    
                });
                foot = '<tr>';
                foot += `<th class="text-right" colspan="6></th>`;
                foot += `<th class="text-right">${convertToRupiah(totalAmount)}</th>`;
                foot += `<th class="text-right">${totalTenor}</th>`;
                foot += `<th class="text-right">${convertToRupiah(totalMontly)}</th>`;
                foot += `<th class="text-right">${convertToRupiah(totalPayment)}</th>`;
                foot += '</tr>';
                $('#tblsm').find('tbody').append(html);
                $('#tblsm').find('tfoot').append(foot);
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_pendapatan .kt-portlet__body', {});
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

jQuery(document).ready(function() {     
    initCariForm(); 
});

</script>
