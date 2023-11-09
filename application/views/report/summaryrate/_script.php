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
        //var category    = $('#category').val();
		// var dateStart   = $('[name="date-start"]').val();
		// var dateEnd     = $('[name="date-end"]').val();
        KTApp.block('#form_pendapatan .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/summaryrate"); ?>",
			dataType : "json",
			data:{area:area,unit:unit},
			success : function(response,status){
				KTApp.unblockPage();
				//if(response.status == true){
					var template = '';
                    var total=0;
                    var up=0;
                    var totup=0;
                    var rate=0;
                    var totrate=0;
                    var totnoa=0;
                    var percentage=0;
                    var totpercentage=0;
                    var sewamodal=0;
                    var totsewamodal=0;
                    var rateaverage=0;
                    var no = 1;
					$.each(response.data, function (index, data) {  
                    if(data.summaryUP.up!=null){
                        template +='<tr class="rowappend" bgcolor="#F9F6F6">';
                    template +='<td class="text-center"><b>'+no+'</b></td>';
                    template +='<td class="text-left"><b>'+data.area+'</b></td>';
                    template +='<td class="text-left"><b>'+data.name+'</b></td>';
                    if(data.summaryUP.up){
                        up= convertToRupiah(data.summaryUP.up);
                    }else{
                        up=0;
                    }
                    template +='<td class="text-right"><b>'+up+'</b></td>';
                    template +='<td class="text-right"><b></b></td>';
                    template +='<td class="text-right"><b></b></td>';
                    template +='</tr>';
                    template +='<tr class="rowappend" bgcolor="#FCFBFB">';
                    template +='<td class="text-right"></td>';
                    template +='<td class="text-right"><b>Rate</b></td>';
                    template +='<td class="text-right"><b>Noa</b></td>';
                    template +='<td class="text-right"><b>UP</b></td>';
                    template +='<td class="text-right"><b>Sewa Modal</b></td>';
                    template +='<td class="text-right"><b>%</b></td>';
                    template +='</tr>';
                        for (let i = 0; i < data.summaryRate.length; i++) {
                            template +='<tr class="rowappend">';
                            template +='<td></td>';
                            rate = data.summaryRate[i].rate*100;
                            template +='<td class="text-right">'+rate.toFixed(2)+'</td>';
                            template +='<td class="text-right">'+data.summaryRate[i].noa+'</td>';
                            template +='<td class="text-right">'+convertToRupiah(data.summaryRate[i].up)+'</td>';
                            percentage = (data.summaryRate[i].up/data.summaryUP.up)*100;
                            sewamodal = (data.summaryRate[i].up*(rate.toFixed(2)))/100;
                            ratesewa = sewamodal/data.summaryRate[i].up;
                            template +='<td class="text-right">'+convertToRupiah(sewamodal)+'</td>';
                            template +='<td class="text-right">'+percentage.toFixed(2)+'</td>';
                            template +='</tr>';
                            totsewamodal += parseInt(sewamodal);
                            totrate += parseFloat(rate);
                            totnoa += parseInt(data.summaryRate[i].noa);
                            totup += parseInt(data.summaryRate[i].up);
                        }
                        rateaverage = (totsewamodal/totup)*100;
                        console.log(rateaverage);
                        template +='<tr class="rowappend" bgcolor="#FCFBFB">';
                        template +='<td class="text-right">Total</td>';
                        template +='<td class="text-right">'+totrate.toFixed(2)+'</td>';
                        template +='<td class="text-right">'+convertToRupiah(totnoa)+'</td>';
                        template +='<td class="text-right">'+convertToRupiah(totup)+'</td>';
                        template +='<td class="text-right">'+convertToRupiah(totsewamodal)+'</td>';
                        template +='<td class="text-right"><b> Average rate : '+rateaverage.toFixed(2)+'</b></td>';
                        template +='</tr>';
                    no++;
                        
                    }               
					});
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
});

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}
</script>
