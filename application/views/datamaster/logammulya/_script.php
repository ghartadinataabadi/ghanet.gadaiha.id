<script>
//globals
var cariForm;

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
    $('#status').select2({ placeholder: "Select a Status", width: '100%' });
    $('#nasabah').select2({ placeholder: "Select a Nasabah", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('[name="area"]').val();
        var unit = $('[name="id_unit"]').val();
        var nasabah = $('#nasabah').val();
        var statusrpt = $('#status').val();
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
		var permit = $('[name="permit"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/lm/transactions"); ?>",
			dataType : "json",
			data:{area:area,id_unit:unit,statusrpt:statusrpt,nasabah:nasabah,dateStart:dateStart,dateEnd:dateEnd,permit:permit, type_transaction:'ORDER'},
			success : function(response,status){
				var html = '';
				const logs = [
					{item:"ON_PROGRESS",value:"On Proses"},
					{item:"READY_ON_DELIVERY",value:"Siap di kirim"},
					{item:"STOCK_READY",value:"Stock ready"},
				];
				response.data.forEach(data=>{
					const {id, name, unit, position,grams, last_log, method,tenor, total,code } = data;
					html += `<tr>`;
					html += `<td>${unit}</td>`;
					html += `<td>${buildTenor(tenor, method)}</td>`;
					grams.forEach(weight=>html += `<td>${weight}</td>`);
					html += `<td>${convertToRupiah(total)}</td>`
					html += `<td><a href="<?php echo base_url('datamaster/logammulya/invoice');?>/${id}"><i class="flaticon-eye"></i> ${code}</a></td>`
					html += `<td><select name="change-status" data-code="${code}" class="form-control" onchange="change(this)">`;
					logs.forEach(log=>{
						const {item,value}	= log;
						if(item == last_log){
							html += `<option value="${item}" selected >${value}</option>`;
						}else{
							html += `<option value="${item}">${value}</option>`;
						}
					})
					html +=		`</select></td>`;
					html += `<td>
<button type="button" onclick="deleted(${id})" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_delete"><i class="flaticon2-trash" style="cursor:pointer;"></button></td>`;
					html += `</tr>`;
				})
				$('tbody').find('tr').remove();
				$('tbody').append(html);
				KTApp.unblockPage();
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

function buildTenor(tenor,method){
	if(method !== 'INSTALLMENT'){
		return method;
	}
	return `installment ${tenor}x`
}

function initGetNasabah(){
    $("#unit").on('change',function(){
        var area = $('#area').val();
        var unit = $('#unit').val();
        var customers =  document.getElementById('nasabah');
        //alert(unit);
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/units/get_customers_gr_byunit"); ?>",
			dataType : "json",
			data:{unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
                    $("#nasabah").empty();
                    var option = document.createElement("option");
                    option.value = "all";
                    option.text = "All";
                    customers.appendChild(option);
					$.each(response.data, function (index, data) {
                        //console.log(data);
                        var opt = document.createElement("option");
                        opt.value = data.nik;
                        opt.text = data.name;
                        customers.appendChild(opt);
					});

				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});

    });
}

function initUnitNasabah(){
        var unit=$('[name="id_unit"]').val();
        var customers =  document.getElementById('nasabah');
        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/units/get_customers_gr_byunit"); ?>",
			dataType : "json",
			data:{unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
                    $("#nasabah").empty();
                    var option = document.createElement("option");
                    option.value = "all";
                    option.text = "All";
                    customers.appendChild(option);
					$.each(response.data, function (index, data) {
                        //console.log(data);
                        var opt = document.createElement("option");
                        opt.value = data.nik;
                        opt.text = data.name;
                        customers.appendChild(opt);
					});
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
}


jQuery(document).ready(function() {
    initCariForm();
    initGetNasabah();
    initUnitNasabah();
    initAlert();

	$('#btncari').trigger('click');
});

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}

function change(element) {
	const data = {
		status:element.value,
		code:element.getAttribute('data-code')
	}
	$.ajax({
		type : 'POST',
		url : "<?php echo base_url("api/lm/transactions/change"); ?>",
		dataType : "json",
		data:data,
		success : function(response,status){
			AlertUtil.showSuccess(response.message,5000);
		},
	});
}
function deleted(id) {
	var targetId = id;
	swal.fire({
		title: 'Anda Yakin?',
		text: "Akan menghapus data ini",
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Ya, Hapus'
	}).then(function(result) {
		if (result.value) {
			KTApp.blockPage();
			$.ajax({
				type : 'GET',
				url : "<?php echo base_url('api/lm/transactions/delete');?>/"+targetId,
				dataType : "json",
				success : function(data,status){
					KTApp.unblockPage();
					if(data.status == true){
						AlertUtil.showSuccess(data.message,5000);
						$('#btncari').trigger('click');
					}else{
						AlertUtil.showFailed(data.message);
					}
				},
				error: function (jqXHR, textStatus, errorThrown){
					KTApp.unblockPage();
					AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
				}
			});
		}
	});
}
</script>
