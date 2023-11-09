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

function formatRupiah(angka){
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split   		= number_string.split(','),
    sisa     		= split[0].length % 3,
    rupiah     		= split[0].substr(0, sisa),
    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
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

    $('#area').select2({ placeholder: "Select Area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
		var unit = $('[name="id_unit"]').val();
		var cabang = $('[name="cabang"]').val();
		var area = $('[name="area"]').val();
		var date = $('[name="date-start"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/gcore/konversi"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit,date:date},
			success : function(response,status){
				KTApp.unblockPage();
                //console.log('test');
				if(response.status == true){
                    //console.log('test');
					var template = '';
					var no = 1;
					
                    var selisihReg = 0;
					var selisihCicil = 0;
					var selisihBook = 0;
					var selisihDpd = 0;

					var tot_os_reg_bap = 0;
					var tot_os_regular = 0;
					var tot_os_cicilan_bap = 0;
					var tot_os_cicilan = 0;
					var tot_booking_bap = 0;
                    var tot_booking = 0;
					var tot_dpd_bap = 0;
					var tot_dpd = 0;
					
                    var tot_pendapatan = 0;
                    var tot_pendapatan_admin = 0;
                    var tot_pengeluaran = 0;
                    
                    var totselisihReg = 0;
					var totselisihCicil = 0;
					var totselisihBook = 0;
					var totselisihDpd = 0;

					$.each(response.data, function (index, data) {
                        //console.log(data.area);
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.area+"</td>";
						template += "<td class='text-left'>"+data.name+"</td>";
						                       
						template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.os_regular) +"</td>";
                        selisihReg = data.bapkas.os_regular - data.bapkas.os_reg_bap;

						template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.os_cicilan) +"</td>";
						// template += "<td class='text-right'>"+convertToRupiah(data.bapkas.os)+"</td>";
                        selisihCicil = data.bapkas.os_cicilan_bap - data.bapkas.os_cicilan;

						template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.booking)+"</td>";
						// template += "<td class='text-right'>"+convertToRupiah(data.bapkas.os)+"</td>";
                        selisihBook = data.bapkas.booking_bap - data.bapkas.booking;

						template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.dpd) +"</td>";
						// template += "<td class='text-right'>"+convertToRupiah(data.bapkas.os)+"</td>";
                        selisihDpd = data.bapkas.dpd_bap - data.bapkas.dpd;
                        
                        template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.pendapatan) +"</td>";
                        template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.pendapatan_admin) +"</td>";
                        template += "<td class='text-right'>"+ convertToRupiah(data.bapkas.pengeluaran) +"</td>";

						template += '</tr>';
                        tot_os_reg_bap += data.bapkas.os_reg_bap;
                        tot_os_regular += data.bapkas.os_regular;
                        totselisihReg += selisihReg;

                        tot_os_cicilan_bap += data.bapkas.os_cicilan_bap;
                        tot_os_cicilan += data.bapkas.os_cicilan;
                        totselisihCicil += selisihCicil;

                        tot_booking_bap += data.bapkas.booking_bap;
                        tot_booking += data.bapkas.booking;
                        totselisihBook += selisihBook;

                        tot_dpd_bap += data.bapkas.dpd_bap;
                        tot_dpd += data.bapkas.dpd;
                        totselisihDpd += selisihDpd;
                        
                        tot_pendapatan += data.bapkas.pendapatan;
                        tot_pendapatan_admin += data.bapkas.pendapatan_admin;
                        tot_pengeluaran += data.bapkas.pengeluaran;
                        
						no++;
					});                   
					    template += "<tr class='rowappend'>";
						template += "<td class='text-center' colspan='3'>Total</td>";

						template += "<td class='text-right'>"+convertToRupiah(tot_os_regular)+"</td>";

						template += "<td class='text-right'>"+convertToRupiah(tot_os_cicilan)+"</td>";

						template += "<td class='text-right'>"+convertToRupiah(tot_booking)+"</td>";

						template += "<td class='text-right'>"+convertToRupiah(tot_dpd)+"</td>";
						
						template += "<td class='text-right'>"+convertToRupiah(tot_pendapatan)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(tot_pendapatan_admin)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(tot_pengeluaran)+"</td>";

						template += '</tr>';
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


$('[name="area"]').on('change',function(){
        var area = $(this).val();
        var units =  document.getElementById('unit');
        var url_data = $('#url_get_unit').val() + '/' + area;
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            if (status) {
                $("#unit").empty();
                var opt = document.createElement("option");
                    opt.value = "all";
                    opt.text ="All";
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

