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
    $("#btnexport_nasabah").on('click', function(){
        var url = `<?php echo base_url("report/nasabah/excel_performances");?>`;
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var permit = $('[name="permit"]').val();
        var date = $('[name="date"]').val();
        var dateStart, dateEnd;
        var year = $('[name="year"]').val();
        var triwulan = parseInt($('[name="triwulan"]').val());
        if(triwulan == 1){
            dateStart = `${year}-01-01`;
            dateEnd = `${year}-03-31`;
        }
        if(triwulan == 2) {
            dateStart = `${year}-04-01`;
            dateEnd = `${year}-06-30`;
        }
        if(triwulan == 3){
            dateStart = `${year}-07-01`;
            dateEnd = `${year}-09-30`;
        }
        if(triwulan == 4){
            dateStart = `${year}-10-01`;
            dateEnd = `${year}-12-31`;
        }
        url += `?area=${area}&unit=${unit}&dateStart=${dateStart}&dateEnd=${dateEnd}&permit=${permit}`;
        location.href = url;
    })
    //events
    $('#btncarinasabah').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var permit = $('[name="permit"]').val();
        var date = $('[name="date"]').val();
        var dateStart, dateEnd;
        var year = $('[name="year"]').val();
        var triwulan = parseInt($('[name="triwulan"]').val());
        if(triwulan == 1){
            dateStart = `${year}-01-01`;
            dateEnd = `${year}-03-31`;
        }
        if(triwulan == 2) {
            dateStart = `${year}-04-01`;
            dateEnd = `${year}-06-30`;
        }
        if(triwulan == 3){
            dateStart = `${year}-07-01`;
            dateEnd = `${year}-09-30`;
        }
        if(triwulan == 4){
            dateStart = `${year}-10-01`;
            dateEnd = `${year}-12-31`;
        }
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/customers/performances"); ?>",
			dataType : "json",
            data:{permit, area, unit, dateStart, dateEnd},
            
            success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template ="";
					var no =1;
                    let int = 0;
                    $('.kt-section__content .table tbody tr').remove();
					$.each(response.data, function (index, data) {

						template += "<tr class='rowappend' bgcolor='#F7F9F9' >";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-center'>"+data.area+"</td>";
						template += "<td class='text-center'>"+data.unit+"</td>";
						template += "<td class='text-center'>"+data.kode_nasabah+"</td>";
						template += "<td class='text-center'>"+data.name+"</td>";
						template += "<td class='text-center'>"+data.birth_date+"</td>";
                        template += "<td class='text-center'>"+data.birth_place+"</td>";
                        template += "<td class='text-center'>"+data.address+"</td>";
                        template += "<td class='text-center'>"+data.nik+"</td>";
                        template += "<td class='text-center'>-</td>";
						template += "<td class='text-center'>"+data.no_cif+"</td>";
                        template += "<td class='text-center'>-</td>";
                        template += "<td class='text-center'>"+data.mobile+"</td>";
                        template += "</tr>";
                        no++;                        
					});
					$('.kt-section__content .table tbody').append(template);
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
