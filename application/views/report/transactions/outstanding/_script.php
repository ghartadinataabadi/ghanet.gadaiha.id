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
		var area        = $('[name="area"]').val();
        var cabang      = $('[name="cabang"]').val();
        var unit        = $('[name="id_unit"]').val();
		var dateStart   = $('[name="dateStart"]').val();
		var dateEnd = $('[name="date-end"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/outstanding"); ?>",
			dataType : "json",
			data:{area:area,cabang:cabang,unit:unit,dateStart:dateStart},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					var no = 1;                   
                    var os = 0;
                    var noa = 0;
                    var up_reg = 0;
                    var up_rep = 0;
                    var noa_reg = 0;
                    var noa_rep = 0;
                    var totos = 0;
                    var totnoa = 0;
					$.each(response.data, function (index, data) {
						 template += "<tr class='rowappend'>";
						 template += "<td class='text-center'>"+no+"</td>";
						 template += "<td class='text-left'>"+data.area+"</td>";
						 template += "<td class='text-left'>"+data.name+"</td>";
						 template += "<td class='text-left'>"+dateStart+"</td>";
                         //calculation OS
                        if(data.outstanding.up!=null){up_reg=data.outstanding.up;}else{up_reg=0;}
                        if(data.repayments.up!=null){up_rep=data.repayments.up;}else{up_rep=0;}
                        os = parseInt(up_reg)+parseInt(up_rep);
                         //calculation NOA
                        if(data.outstanding.noa!=null){noa_reg=data.outstanding.noa;}else{noa_reg=0;}
                        if(data.repayments.noa!=null){noa_rep=data.repayments.noa;}else{noa_rep=0;}
                        noa = parseInt(noa_reg) + parseInt(noa_rep);
                        
                        template += "<td class='text-center'>"+convertToRupiah(noa)+"</td>";
                        template += "<td class='text-right'>"+convertToRupiah(os)+"</td>";
                        template += '<td class="text-right"><a href="<?php echo base_url();?>report/transactions/preview/'+unit+'/'+dateStart+'" target="_blank" title="Cetak Rincian Penerimaan" ><i class="flaticon2-print" style="cursor:pointer;"></i></a></td>';
                        template += '</tr>';
                        //console.log(data);                        
                        totos += os;
                        totnoa += noa;
                        no++;                        
					});
                    $('.kt-section__content table').find('tfoot').find('.totnoa').text(convertToRupiah(parseInt(totnoa)));
                    $('.kt-section__content table').find('tfoot').find('.totos').text(convertToRupiah(parseInt(totos)));
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
			url : "<?php echo base_url("api/datamaster/bookcash/getBookCash"); ?>",
			dataType : "json",
			data:{id:id},
			success : function(response,status){
				if(response.status == true){
                    //console.log(response.data.name);
                    $('#units').val(response.data.name);
                    $('#kasir').val(response.data.kasir);
                    $('#date').val(response.data.date);
                    $('#saldoawal').val(formatRupiah(response.data.amount_balance_final));
                    $('#penerimaan').val(formatRupiah(response.data.amount_in));
                    $('#pengeluaran').val(formatRupiah(response.data.amount_out));
                    $('#mutasi').val(formatRupiah(response.data.amount_mutation));
                    $('#saldoakhir').val(formatRupiah(response.data.amount_balance_final));
                    $('#selisih').val(formatRupiah(response.data.amount_gap));                   
                    $('#os_unit').val((parseInt(response.data.os_unit)+parseInt(response.data.os_cicilan)));                   
                    $('#note').val(response.data.note);                   
                    
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				//KTApp.unblockPage();
			},
			complete:function () {
				//KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});

        $.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/datamaster/bookcash/getDetailBookCash"); ?>",
			dataType : "json",
			data:{id:id},
			success : function(response,status){
				if(response.status == true){
                    var templateKertas = '';
                    var templateLogam = '';
					var no = 1;
					var k_totpecahan = 0;
					var l_totpecahan = 0;
                    var totalkertas = 0;
                    var totallogam  = 0;
                    var total  = 0;
                    $.each(response.data, function (index, data) {
                        if(data.type=="KERTAS"){
                            templateKertas += "<tr class='rowappend_kertas'>";
                            templateKertas += "<td>"+convertToRupiah(data.amount)+"</td>";
                            templateKertas += "<td>"+data.summary+"</td>";
                            k_totpecahan = parseInt(data.amount) * parseInt(data.summary);
                            templateKertas += "<td class='text-right'>"+convertToRupiah(k_totpecahan)+"</td>";
                            templateKertas += '</tr>';
                            totalkertas +=k_totpecahan;
                            no++;
                        }
                        if(data.type=="LOGAM"){
                            templateLogam += "<tr class='rowappend_logam'>";
                            templateLogam += "<td>"+convertToRupiah(data.amount)+"</td>";
                            templateLogam += "<td>"+data.summary+"</td>";
                            l_totpecahan = parseInt(data.amount) * parseInt(data.summary);
                            templateLogam += "<td class='text-right'>"+convertToRupiah(l_totpecahan)+"</td>";
                            templateLogam += '</tr>';
                            totallogam +=l_totpecahan;
                            no++;
                        }						
					});
                    templateKertas += '<tr class="rowappend_kertas">';
                    templateKertas +='<td colspan="2" class="text-right"></td>';                    
                    templateKertas +='<td class="text-right"><b>'+convertToRupiah(totalkertas)+'</b></td>';                    
                    templateKertas +='</tr>';

                    templateLogam += '<tr class="rowappend_logam">';
                    templateLogam +='<td colspan="2" class="text-right"></td>';                    
                    templateLogam +='<td class="text-right"><b>'+convertToRupiah(totallogam)+'</b></td>';                    
                    templateLogam +='</tr>';
                    total = parseInt(totalkertas) + parseInt(totallogam);
					$('#kertas').append(templateKertas);
					$('#logam').append(templateLogam);
                    $('#total').val(convertToRupiah(total));
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
    //initGetUnit();

    $(document).on("click", ".viewBtn", function () {
                var el = $(this);
                popView(el);
    });
});


</script>
