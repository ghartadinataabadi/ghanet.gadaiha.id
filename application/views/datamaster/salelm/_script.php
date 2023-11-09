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
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
        const id_unit = $('#unit').val();
        const id_area = $('[name="area"]').val();
        const date_start = $('[name="date_start"]').val();
        const date_end = $('[name="date_end"]').val();
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/lm/transactions/sales"); ?>",
            data:{id_unit,id_area, date_start,date_end},
			dataType : "json",
			success : function(response,status){
				var html = '';
				response.data.forEach(data=>{
					html += '<tr>'
                    html += `<td>${data.date}</td>`
                    html += `<td>${data.unit}</td>`
                    html += `<td>${data.pembeli}</td>`
                    html += `<td>${data.series}</td>`
                    html += `<td>${data.amount}</td>`
                    html += `<td>${data.price_perpcs}</td>`
                    html += `<td>${data.price_buyback_perpcs}</td>`
                    html += `<td>${data.total}</td>`
                    html += `<td>${parseInt(data.amount) * parseInt(data.price_buyback_perpcs)}</td>`
					html += '/<tr>'
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






jQuery(document).ready(function() {
    initCariForm();
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

const export_xls = () =>{
    const date_end = $('[name="date_end"]').val();
    const date_start = $('[name="date_start"]').val();
    const id_unit = $('[name="id_unit"]').val();
    const id_area = $('[name="area"]').val();
    return window.location.href = `<?php echo base_url();?>datamaster/salelm/export_excel?id_unit=${id_unit}&id_area=${id_area}&date_start=${date_start}&date_end=${date_end}`;
}
</script>
