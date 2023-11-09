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
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var unit_name = $('[name="unit_id"]').val();
        var transaction_status = $('[name="transaction_status"]').val();
        var date_start = $('[name="date_start"]').val();
        var date_end = $('[name="date_end"]').val();
        var page = $('[name="page"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/yogadai/transactions"); ?>",
			dataType : "json",
			data:{unit_name, transaction_status, date_start, date_end, page},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == 200){
					var template = '';
					var no = 1;
					var amount = 0;
					var admin = 0;
                    var status="";
                  $('[data-item="page-cloned"]').remove();
                    console.log(response.data.data);
                    if(response.data.data.length){
                        
                        var totalPagination = response.data.pagination.total_pages;
                        $('[data-item="page-cloned"]').remove();
                        for(let i =1; i <= totalPagination; i++){
                            let template = $('[data-item="page"]').clone();
                            template.attr('data-item','page-cloned');
                            template.attr('onclick',`pagination(${i})`);
                            template.find('a').text(i);
                            template.removeClass('d-none');
                            $(".pagination").append(template);
                        }
    					$.each(response.data.data, function (index, data) {
    						template += "<tr class='rowappend'>";
    						template += "<td class='text-center'>"+no+"</td>";
    						template += "<td class='text-center'>"+data.unit_name+"</td>";
    						template += "<td class='text-center'>"+data.sbg_number+"</td>";
    						template += "<td class='text-center'>"+data.sbk_date+"</td>";
    						template += "<td class='text-center'>"+data.due_date+"</td>";
    						template += "<td class='text-center'>"+data.payment_date+"</td>";
    						template += "<td class='text-center'>"+data.customer_name+"</td>";
    						template += "<td class='text-center'>"+data.deposit_rate+"</td>";
    						template += "<td class='text-center'>"+data.foreacast+"</td>";
    						template += "<td class='text-center'>"+data.admin_fee+"</td>";
    						template += "<td class='text-center'>"+data.up_value+"</td>";
    						template += "<td class='text-center'>"+data.status_text+"</td>";
    						template += "<td class='text-center'>"+data.description+"</td>";
                            template += '</tr>'
    					});
                    }
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

const initArea = ()=>{
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/yogadai/datamaster/areas"); ?>",
        dataType : "json",
        success:function(res){
            let template = '<option value="">All</option>';
            res.data.forEach(res=>{
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="area_id"]').append(template);
        },
        error:function(e){
            console.log(e);
        }
    });
}

const pagination = (id)=>{
    $('[name="page"]').val(id);
     $('#btncari').trigger('click');
}

$('[name="area_id"]').on('change', function(){
    let area_id = $(this).val();
    $('[name="branch_id"]').empty();
    $('[name="unit_id"]').empty();
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/yogadai/datamaster/branchies"); ?>/"+area_id,
        dataType : "json",
        success:function(res){
            let template = '<option value="">All</option>';
            res.data.forEach(res=>{
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="branch_id"]').append(template);
        },
        error:function(e){
            console.log(e);
        }
    });
})


$('[name="branch_id"]').on('change', function(){
    let branch_id = $(this).val();
    $('[name="unit_id"]').empty();
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/yogadai/datamaster/units"); ?>/"+branch_id,
        dataType : "json",
        success:function(res){
            let template = '<option value="">All</option>';
            res.data.forEach(res=>{
                template += `<option value="${res.name}">${res.name}</option>`;
            })
            $('[name="unit_id"]').append(template);
        },
        error:function(e){
            console.log(e);
        }
    });
})

jQuery(document).ready(function() {
    initCariForm();
    initArea();
});


</script>
