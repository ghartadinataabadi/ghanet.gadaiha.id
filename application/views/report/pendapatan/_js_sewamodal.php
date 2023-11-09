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
    var validator = $("#form_pendapatan").validate({
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

    $('#area').select2({ placeholder: "Select a area", width: '100%' });
    $('#unit').select2({ placeholder: "Select a Unit", width: '100%' });
    $('#category').select2({ placeholder: "Select a Category", width: '100%' });
    

    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area        = $('[name="area"]').val();
        var unit        = $('[name="id_unit"]').val();
        var category    = $('#category').val();
		var dateStart   = $('[name="date-start"]').val();
		var dateEnd     = $('[name="date-end"]').val();
        KTApp.block('#form_pendapatan .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/unitsdailycash/summarycashin"); ?>",
			dataType : "json",
			data:{area:area,unit:unit,perk:category,dateStart:dateStart,dateEnd:dateEnd},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
                    var total=0;
                    var totaverage=0;
                    var average=0;
                    var no = 0;
					$.each(response.data, function (index, data) {
                    no++;
                    template +='<tr class="rowappend">';
                    template +='<td class="text-center">'+no+'</td>';
                    template +='<td class="text-left">'+data.area+'</td>';
                    template +='<td class="text-left">'+data.name+'</td>';
                    template +='<td class="text-right">'+convertToRupiah(data.summary.amount)+'</td>';
                    average = data.summary.amount/data.summary.total;
                    if(isNaN(average)){average=0;}else{average=average;}
                    template +='<td class="text-right">'+average.toFixed(2)+'</td>';
                    template +='</tr>';
                    total +=  parseInt(data.summary.amount);
                    totaverage +=  parseInt(average);
					});
                    template += '<tr class="rowappend">';
                    template +='<td colspan="3" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>'+convertToRupiah(total)+'</b></td>';
                    template +='<td class="text-right"><b>'+convertToRupiah(totaverage)+'</b></td>';
                    template +='</tr>';
					$('.kt-section__content #tblsm').append(template);
				//}
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
				opt.text = "all";
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
});

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}
</script>
