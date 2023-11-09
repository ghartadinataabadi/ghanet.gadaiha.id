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

// function convertToRupiah(angka){
//    var reverse = angka.toString().split('').reverse().join(''),
//    ribuan = reverse.match(/\d{1,3}/g);
//    ribuan = ribuan.join('.').split('').reverse().join('');
//    return ribuan;
//  }

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
    $('#target').select2({ placeholder: "Select a Target", width: '100%' });
    $('#permit').select2({ placeholder: "Select a Ijin", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area = $('#area').val();
        var unit = $('#unit').val();
        var target = $('#target').val();
		var date = $('[name="date"]').val();
		//var dateEnd = $('[name="date-end"]').val();
		var permit = $('[name="permit"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/unitstarget/tergetrealisasi"); ?>",
			dataType : "json",
			data:{area:area,unit:unit,date:date},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
					var target = 0;
					var realisasi = 0;
					var PercentRealisasi = 0;
					var PercentSelisih = 0;
					var Selisih = 0;
                    var status="";
                    console.log(response.id);
					$.each(response.data, function (index, data) {
                        var month = moment(date).format('MMMM');
                        var year = moment(date).format('Y');
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td>"+data.area+"</td>";
						template += "<td>"+data.name+"</td>";                        
                        // if(target=="Booking"){ tar_amount = data.amount_booking;}else if(target=="Outstanding"){tar_amount = data.amount_outstanding;}
                        // PercentRealisasi = (data.amount/tar_amount)*100;
                        // Selisih = tar_amount - data.amount;
                        // PercentSelisih = (Selisih/tar_amount)*100;
                        // if(PercentRealisasi < 100){status="<span class='kt-widget4__number kt-font-danger kt-font-bold'> Dibawah Target</span>";}
                        // else if(PercentRealisasi > 100){status="<span class='kt-widget4__number kt-font-primary kt-font-bold'> Melebihi Target</span>";}
                        // else if(PercentRealisasi == 100){status="<span class='kt-widget4__number kt-font-success kt-font-bold'> Sesuai Target</span>";}
						//template += "<td class='text-right'>"+convertToRupiah(tar_amount)+"</td>";
                        //template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
						//template += "<td class='text-center'>"+PercentRealisasi.toFixed(2)+"</td>";
						//template += "<td class='text-right'>"+convertToRupiah(Selisih) +"</td>";
						//template += "<td class='text-center'>"+PercentSelisih.toFixed(2)+"</td>";
						template += "<td class='text-center'>"+month+"</td>";
						template += "<td class='text-center'>"+year+"</td>";
						template += "<td class='text-right'>0</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.targetreal.realisasi)+"</td>";
                        template += '</tr>';
                        realisasi +=  parseInt(data.targetreal.realisasi);
						no++;
                    });

                    template += '<tr class="rowappend">';
                    template +='<td colspan="5" class="text-right"><b>Total</b></td>';                    
                    template +='<td class="text-right"><b>0</b></td>';
                    template +='<td class="text-right"><b>'+convertToRupiah(realisasi)+'</b></td>';
                    template +='</tr>';
					$('.kt-section__content #tbltarget').append(template);
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

function initGetUnit(){
    $("#area").on('change',function(){
        var area = $('#area').val();
        var units =  document.getElementById('unit');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#unit").empty();
                var opt = document.createElement("option");
				opt.value = "0";
				opt.text = "All";
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
}

jQuery(document).ready(function() {
    initCariForm();
    initGetUnit();
});

</script>
