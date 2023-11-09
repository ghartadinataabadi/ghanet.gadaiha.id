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
	function buildTenor(tenor,method){
		if(method !== 'INSTALLMENT'){
			return method;
		}
		return `installment ${tenor}x`
	}
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
			url : "<?php echo base_url("api/lm/transactions"); ?>?type_transaction=SALE",
			dataType : "json",
			data:{area:area,id_unit:unit},
			success : function(response,status){
				var html = '';
				const logs = [
					{item:"APPROVED_BY_AREA",value:"Approved"},
					{item:"DECLINED",value:"Declined"},
					{item:"ON_PROGRESS",value:"On Progress"}
				];
				response.data.forEach(data=>{
					const { id, name, date, unit, grams, last_log, method,tenor, total,code, total_buyback } = data;
					html += `<tr>`;
					html += `<td>${date}</td>`;
					html += `<td>${name}</td>`;
					html += `<td>${unit}</td>`;
					html += `<td>${buildTenor(tenor, method)}</td>`;
					grams.forEach(weight=>html += `<td>${weight}</td>`);
					html += `<td class="text-right">${convertToRupiah(total)}</td>`;
					html += `<td class="text-right">${convertToRupiah(total_buyback)}</td>`;
					<?php if($this->session->userdata('user')->level === 'pusat' || $this->session->userdata('user')->level === 'administrator'):?>
                        html += `<td>
                        <a href="<?php echo base_url();?>/lm/sales/form/${id}"><i class="flaticon-edit-1"></i></a>
                        <a onclick="deleteHander(${id})"><i class="fas fa-trash"></i></a>
                        </td>`;   
				
                    <?php else:?>    
                     html += `<td><a href="<?php echo base_url();?>/lm/sales/form/${id}"><i class="flaticon-edit-1"></i></a></td>`;   
					<?php endif;?>
                	html += `</tr>`;
				})
				$('tbody').find('tr').remove();
				$('[data-append="item"]').append(html);
				KTApp.unblockPage();
			},
			error: function (jqXHR, textStatus, errorThrown){
			    console.log(jqXHR, textStatus, errorThrown);
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


const deleteHander = (id)=>{
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
                    url : "<?php echo base_url("api/lm/transactions/delete"); ?>/"+id,
                    dataType : "json",
                    success : function(data,status){
                        $('#btncari').trigger('click');
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
