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


function bobotBooking(angka, booking){

    var nilai = 0;
    if(angka > 0 && angka <= booking.percent_1){
        nilai = booking.bobot_1;
        return nilai;
    }else if(angka <= booking.percent_2 && angka > booking.percent_1){
        nilai = booking.bobot_2;
        return nilai;
    }else if(angka <= booking.percent_3 && angka > booking.percent_2){
        nilai = booking.bobot_3;
        return nilai;
    }else if(angka <= booking.percent_4 && angka > booking.percent_3){
        nilai = 0;
        return nilai;
    }else if(angka > booking.percent_5){
        nilai = booking.bobot_5;
        return nilai;
    }else{
        nilai = 0;
        return nilai;
    }
}

function bobotOutstanding(angka, outstanding){

    var nilai = 0;
    if(angka  > 0 && angka <= outstanding.percent_1){
        nilai = outstanding.bobot_1;
        return nilai;
    }else if(angka <= outstanding.percent_2 && angka > outstanding.percent_1){
        nilai = outstanding.bobot_2;
        return nilai;
    }else if(angka <= outstanding.percent_3 && angka > outstanding.percent_2){
        nilai = outstanding.bobot_3;
        return nilai;
    }else if(angka <= outstanding.percent_4 && angka > outstanding.percent_3){
        nilai = outstanding.bobot_4;
        return nilai;
    }else if(angka > outstanding.percent_5){
        nilai = outstanding.bobot_5;
        return nilai;
    }else{
        nilai = 0;
        return nilai;
    }
}

function bobotDpd(angka, dpd){
    var a = angka.toFixed(2);

    var nilai = 0;
    if(a > dpd.percent_5){
        nilai = dpd.bobot_5;
        return nilai;
    }else if(a <= dpd.percent_4 && a > dpd.percent_3){
        nilai = dpd.bobot_4;
        return nilai;
    }else if(a <= dpd.percent_3 && a > dpd.percent_2){
        nilai = dpd.bobot_3;
        return nilai;
    }else if(a <= dpd.percent_2 && a > dpd.percent_1){
        nilai = dpd.bobot_2;
        return nilai;
    }else if(a <= dpd.percent_1){
        nilai = dpd.bobot_1;
        return nilai;
    }
}

function bobotRate(angka, rate){
    console.log('test Rate');
    var a = angka.toFixed(2);
    console.log(a);
    
    var nilai = 0;
    if(a <= rate.percent_1){
        nilai = rate.bobot_1;
        return nilai;
    }else if(a <= rate.percent_2 && a > rate.percent_1){
        nilai = rate.bobot_2;
        return nilai;
    }else if(a <= rate.percent_3 && a > rate.percent_2){
        nilai = rate.bobot_3;
        return nilai;
    }else if(a <= rate.percent_4 && a > rate.percent_3){
        nilai = rate.bobot_4;
        return nilai;
    }else if(a > rate.percent_5){
        nilai = rate.bobot_5;
        return nilai;
    }
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
    $('#cabang').select2({ placeholder: "Select cabang", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
		var area = $('[name="area"]').val();
        var cabang = $('[name="id_cabang"]').val();
        var unit = $('[name="id_unit"]').val();
		var dateStart = $('[name="date-start"]').val();
        console.log('test');
        // console.log(dateStart);
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/kpi/Kpii/"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit,dateStart:dateStart},
			success : function(response,status){
                console.log(response);
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;
                    
					$.each(response.data, function (index, data) {
                        console.log(response.booking.percentase)
                            console.log('tes1');
                            console.log(data);
                            var avarage_rate = 0;
                            if(data.cbg.length != 0){
                                $.each(data.cbg, function (index, cbg) {
                                    console.log(response.booking.percentase)
                                    console.log('tes2');
                                    console.log(data.cbg);
                                    if(cbg.kpi.length != 0){
                                        $.each(cbg.kpi, function (index, kpi) {
                                            console.log(response.booking.percentase)
                                            console.log('tes3');
                                            console.log(cbg.kpi);
                                            template +='<tr class="rowappend">';
                                            template +='<td class="text-left">'+no+'</td>';
                                            template +='<td class="text-left">'+kpi.unit+'</td>';
                                            template +='<td class="text-left">'+cbg.area+'</td>';
                                            template +='<td class="text-left">'+kpi.code_unit+'</td>';
                                            template +='<td class="text-left">'+kpi.month+"/"+kpi.year+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.booking)+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.target_booking)+'</td>';
                                            template +='<td class="text-left">'+kpi.percent_booking+"%"+'</td>';
                                            template +='<td class="text-left">'+kpi.noa_os+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.outstanding)+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.target_os)+'</td>';
                                            template +='<td class="text-left">'+kpi.percent_os+"%"+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.dpd)+'</td>';
                                            template +='<td class="text-left">'+kpi.percent_dpd+"%"+'</td>';
                                            template +='<td class="text-left">'+kpi.avarage_rate+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.profit_unit)+'</td>';
                                            template +='<td class="text-left">'+kpi.bobot_os+'</td>';
                                            template +='<td class="text-left">'+kpi.bobot_booking+'</td>';
                                            template +='<td class="text-left">'+kpi.bobot_dpd+'</td>';
                                            template +='<td class="text-left">'+kpi.bobot_rate+'</td>';
                                            template +='<td class="text-left">'+kpi.score+"%"+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.pendapatan_admin)+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.percen_admin)+'</td>';
                                            template +='<td class="text-left">'+convertToRupiah(kpi.insentif_unit)+'</td>';
                                            template +='</tr>';
                                            no++;
                                        });
                                    }

                                    var percent_book = 0;

                                    if((cbg.booking == 0 && cbg.target_booking == 0) || (cbg.booking != 0 && cbg.target_booking == 0)){
                                        percent_book = 0;
                                    }
                                    else{
                                        percent_book = (cbg.booking)/(cbg.target_booking)*100;
                                    }

                                    var percent_outstanding = 0;
                                    if((cbg.outstanding == 0 && cbg.target_os == 0) || (cbg.outstanding != 0 && cbg.target_os == 0)){
                                        percent_outstanding = 0;
                                    }
                                    else{
                                        percent_outstanding = (cbg.booking)/(cbg.target_booking)*100;
                                    }

                                    var percent_Dpd = 0;

                                    if((cbg.dpd == 0 && cbg.outstanding == 0) || (cbg.dpd != 0 && cbg.outstanding == 0)){
                                        percent_Dpd = 0;
                                    }
                                    else{
                                        percent_Dpd = (cbg.booking)/(cbg.outstanding)*100;
                                    }

                                    template +='<tr class="rowappend" bgcolor="#98FB98">';
                                    template +='<td class="text-center" colspan="5"><b>'+cbg.cabang+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.booking)+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.target_booking)+'</b></td>';
                                    template +='<td class="text-left"><b>'+percent_book.toFixed(2)+"%"+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.noa_os)+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.outstanding)+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.target_os)+'</b></td>';
                                    template +='<td class="text-left"><b>'+percent_outstanding.toFixed(2)+"%"+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.dpd)+'</b></td>';
                                    template +='<td class="text-left"><b>'+percent_Dpd.toFixed(2)+"%"+'</b></td>';
                                    template +='<td class="text-left"><b>'+((cbg.avarage_rate)/cbg.kpi.length).toFixed(2)+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.profit_unit)+'</b></td>';
                                    template +='<td class="text-left"><b>'+bobotOutstanding(percent_outstanding, response.outstanding)+'</b></td>';
                                    template +='<td class="text-left"><b>'+bobotBooking(percent_book, response.booking)+'</b></td>';
                                    template +='<td class="text-left"><b>'+bobotDpd(percent_Dpd, response.dpd)+'</b></td>';
                                    template +='<td class="text-left"><b>'+bobotRate(((cbg.avarage_rate)/cbg.kpi.length), response.rate)+'</b></td>';
                                    template +='<td class="text-left"><b>'+(((bobotOutstanding(percent_outstanding, response.outstanding)*(response.outstanding.percentase))+(bobotBooking(percent_book, response.booking)*(response.booking.percentase))+(bobotDpd(percent_Dpd, response.dpd)*(response.dpd.percentase))+(bobotRate(((cbg.avarage_rate)/cbg.kpi.length), response.rate)*(response.rate.percentase)))/10)+"%"+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(cbg.pendapatan_admin)+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah((((response.bobotcabang.percentase)*cbg.pendapatan_admin)/100).toFixed(0))+'</b></td>';
                                    template +='<td class="text-left"><b>'+convertToRupiah(((((response.bobotcabang.percentase)*cbg.pendapatan_admin)/100)*( (((bobotOutstanding(percent_outstanding, response.outstanding)*(response.outstanding.percentase))+(bobotBooking(percent_book, response.booking)*(response.booking.percentase))+(bobotDpd(percent_Dpd, response.dpd)*(response.dpd.percentase))+(bobotRate(((cbg.avarage_rate)/cbg.kpi.length), response.rate)*(response.rate.percentase)) )/10)/100) ).toFixed(0))+'</b></td>';
                                    template +='</tr>';
                                    template += '</tr>';
                                    avarage_rate += ((cbg.avarage_rate)/cbg.kpi.length); 
                                });
                            }
                            var percent_book2 = 0;

                            if((data.booking == 0 && data.target_booking == 0) || (data.booking != 0 && data.target_booking == 0)){
                                percent_book2 = 0;
                            }
                            else{
                                percent_book2 = (data.booking)/(data.target_booking)*100;
                            }

                            var percent_outstanding2 = 0;
                            if((data.outstanding == 0 && data.target_os == 0) || (data.outstanding != 0 && data.target_os == 0)){
                                percent_outstanding2 = 0;
                            }
                            else{
                                percent_outstanding2 = (data.booking)/(data.target_booking)*100;
                            }

                            var percent_Dpd2 = 0;

                            if((data.dpd == 0 && data.outstanding == 0) || (data.dpd != 0 && data.outstanding == 0)){
                                percent_Dpd2 = 0;
                            }
                            else{
                                percent_Dpd2 = (data.booking)/(data.outstanding)*100;
                            }

                            
                            template +='<tr class="rowappend" bgcolor="#B0C4DE" >';
                            template +='<td class="text-center" colspan="5"><b>'+data.area+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.booking)+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.target_booking)+'</b></td>';
                            template +='<td class="text-left"><b>'+percent_book2.toFixed(2)+"%"+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.noa_os)+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.outstanding)+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.target_os)+'</b></td>';
                            template +='<td class="text-left"><b>'+percent_outstanding2.toFixed(2)+"%"+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.dpd)+'</b></td>';
                            template +='<td class="text-left"><b>'+percent_Dpd2.toFixed(2)+"%"+'</b></td>';
                            template +='<td class="text-left"><b>'+((avarage_rate)/(data.cbg.length)).toFixed(2)+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.profit_unit)+'</b></td>';
                            template +='<td class="text-left"><b>'+bobotOutstanding(percent_outstanding2,response.outstanding)+'</b></td>';
                            template +='<td class="text-left"><b>'+bobotBooking(percent_book2, response.booking)+'</b></td>';
                            template +='<td class="text-left"><b>'+bobotDpd(percent_Dpd2, response.dpd)+'</b></td>';
                            template +='<td class="text-left"><b>'+bobotRate(((avarage_rate)/(data.cbg.length)), response.rate)+'</b></td>';
                            template +='<td class="text-left"><b>'+(((bobotBooking(percent_book2, response.booking)*(response.booking.percentase))+(bobotOutstanding(percent_outstanding2,response.outstanding)*(response.outstanding.percentase))+(bobotDpd(percent_Dpd2, response.dpd)*(response.dpd.percentase))+(bobotRate(((avarage_rate)/(data.cbg.length)), response.rate)*(response.rate.percentase)))/10)+"%"+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah(data.pendapatan_admin)+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah((((response.area.percentase)*data.pendapatan_admin)/100).toFixed(0))+'</b></td>';
                            template +='<td class="text-left"><b>'+convertToRupiah((( (((response.area.percentase)*data.pendapatan_admin)/100)*((((bobotBooking(percent_book2, response.booking)*(response.booking.percentase))+(bobotOutstanding(percent_outstanding2,response.outstanding)*(response.outstanding.percentase))+(bobotDpd(percent_Dpd2, response.dpd)*(response.dpd.percentase))+(bobotRate(((avarage_rate)/(data.cbg.length)), response.rate)*(response.rate.percentase)))/10)/100))).toFixed(0))+'</b></td>';
                            template +='</tr>';    
					});

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
    var area = $('[name="area"]').val();
    var cabang =  $('[name="id_cabang"]');
    var cabang =  document.getElementById('cabang');
    var url_data = $('#url_get_cabang').val() + '/' + area;
    console.log(url_data);
        $.get(url_data, function (data, status) {
            var response = JSON.parse(data);
            console.log(response);
            if (status) {
                $("#cabang").empty();
				var opt = document.createElement("option");
				opt.value = "0";
				opt.text = "All";
				cabang.append(opt);
                for (var i = 0; i < response.data.length; i++) {
                    var opt = document.createElement("option");
                    opt.value = response.data[i].id;
                    opt.text = response.data[i].cabang;
                    cabang.append(opt);
                }
            }
        });
});

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
	$('[name="area"]').trigger('change');
}

function popView(el)
{
    $('.rowappend_kertas').remove();
    $('.rowappend_logam').remove();
    var id = $(el).attr('data-id');
    //console.log(id);  
    $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/kpi/Kpii/getKpi"); ?>",
			dataType : "json",
			data:{id:id},
			success : function(response,status){
				if(response.status == true){
                    //console.log(response.data.name);
                    // $('#units').val(response.data.name);
                    // $('#kasir').val(response.data.kasir);
                    // $('#date').val(response.data.date);
                    $('#bookingFinal').val(formatRupiah(response.data.booking));
                    $('#targetBookingFinal').val(formatRupiah(response.data.target_booking));
                    $('#percentBookingFinal').val(response.data.percent_booking);
                    $('#osFinal').val(formatRupiah(response.data.outstanding));
                    $('#targetOsFinal').val(formatRupiah(response.data.target_os));
                    $('#percentOsFinal').val(formatRupiah(response.data.percent_os));
                    $('#dpdUpFinal').val(formatRupiah(response.data.dpd));        
                    $('#percentDpdFinal').val(formatRupiah(response.data.percent_dpd));
                    $('#avarageRateFinal').val(formatRupiah(response.data.avarage_rate));           
                    $('#nominalProfitFinal').val(formatRupiah(response.data.nominal_profit));    
                    $('#bobotBookingFinal').val(response.data.bobot_booking);
                    $('#bobotOsFinal').val(response.data.bobot_os);
                    $('#bobotDpdFinal').val(response.data.bobot_dpd);
                    $('#bobotRateFinal').val(response.data.bobot_rate);               
                    $('#score').val(response.data.score);                   
                    
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				//KTApp.unblockPage();
			},
			complete:function () {
				//KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});

    
}


$('[name="id_cabang"]').on('change',function(){
   
	var cabang = $('[name="id_cabang"]').val();
	var units =  $('[name="id_unit"]');
	var url_data = $('#url_get_units').val() + '/' + cabang;
	$.get(url_data, function (data, status) {
		var response = JSON.parse(data);
        console.log(response);
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
var typecabang = $('[name="id_cabang"]').attr('type');
if(typecabang == 'hidden'){
	$('[name="id_cabang"]').trigger('change');
}

$('#btnexport_monthly').on('click', function(e){
    var area = $('[name="area"]').val() ? $('[name="area"]').val() : '';
    var cabang = $('[name="id_cabang"]').val() ? $('[name="id_cabang"]').val() : '';
    var unit = $('[name="id_unit"]').val() ? $('[name="id_unit"]').val() : '';
    var dateStart = $('[name="date-start"]').val() ? $('[name="date-start"]').val() : '';
    window.location.href = `<?php echo base_url('kpi/Kpii/export');?>?area=${area}&cabang=${cabang}&unit=${unit}&dateStart=${dateStart}`;
});

$('#btnexport_pdf').on('click', function(e){
    var area = $('[name="area"]').val() ? $('[name="area"]').val() : '';
    var cabang = $('[name="id_cabang"]').val() ? $('[name="id_cabang"]').val() : '';
    var unit = $('[name="id_unit"]').val() ? $('[name="id_unit"]').val() : '';
    var dateStart = $('[name="date-start"]').val() ? $('[name="date-start"]').val() : '';
   window.location.href = `<?php echo base_url('kpi/Kpii/preview');?>?area=${area}&cabang=${cabang}&unit=${unit}&dateStart=${dateStart}`;
});



jQuery(document).ready(function() {
    initCariForm();
    //initGetUnit();

    $(document).on("click", ".viewBtn", function () {
                var el = $(this);
                popView(el);
    });
});


</script>
