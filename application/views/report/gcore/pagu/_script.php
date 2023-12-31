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

function exportpdf(){
    //alert('test');
    var sdate = $('[name="date-start"]').val();
    var area = $('[name="area_id"]').val();
    var cabang = $('[name="branch_id"]').val();
    var unit = $('[name="unit_id"]').val();
    //alert(date);
    window.location.href = "<?php echo base_url();?>/gcore/pagukas/reportpdf?date="+sdate+"&area="+area+"&cabang="+cabang+"&unit="+unit;
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
		var area_id = $('[name="area_id"]').val();
        var branch_id = $('[name="branch_id"]').val();
        var unit_id = $('[name="unit_id"]').val();
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/gcore/pagukas/getpgkas"); ?>",
			dataType : "json",
			data:{area_id:area_id,branch_id:branch_id,unit_id:unit_id,dateStart:dateStart,dateEnd:dateEnd},
			success : function(response,status){
				KTApp.unblockPage();
                var template = '';
                var selisih =0;
                var no =1;
                var alltotkas =0;
                var alltotmoker =0;
                var alltotselisih =0;
               
                
                //loop data
                $.each(response.data, function (index, datas) {
                        //loop each brand
                        var totkas =0;
                        var totmoker =0;
                        var totselisih =0;
                        $.each(datas, function (index, data) {
                            if(data.bapkas != 0 || data.bapkas.length != 0){
                            selisih = parseInt(data.bapkas - data.pagu);
                           
                            //console.log(index.cabang);
                            template +=`<tr class='rowappend'>
                                            <td class="text-center">`+no+`</td>
                                            <td class="text-left">`+data.name+`</td>
                                            <td class="text-left">`+data.cabang+` -`+data.area+`</td>
                                            <td class="text-right">`+convertToRupiah(parseInt(data.bapkas))+`</td>
                                            <td class="text-right">`+convertToRupiah(parseInt(data.pagu))+`</td>
                                            <td class="text-right">`+convertToRupiah(parseInt(selisih))+`</td>
                                            <td class="text-left"></td>                                        
                                </tr>`;
                                totkas += parseInt(data.bapkas);
                                totmoker += parseInt(data.pagu);
                                totselisih += parseInt(selisih);

                                alltotkas += parseInt(data.bapkas);
                                alltotmoker += parseInt(data.pagu);
                                alltotselisih += parseInt(selisih);
                                no++;
                            }
                            
                        }); 
                            template +=`<tr class='rowappend' bgcolor='#F7F9F9'>
                                        <td colspan='3' class="text-right"><b>`+index+` - Total </b></td>
                                        <td class="text-right"><b>`+convertToRupiah(parseInt(totkas))+`</b></td>
                                        <td class="text-right"><b>`+convertToRupiah(parseInt(totmoker))+`</b></td>
                                        <td class="text-right"><b>`+convertToRupiah(parseInt(totselisih))+`</b></td>
                                        <td class="text-right"></td>
                                        </tr>`;                   
                });               

                $('.kt-section__content table').find('tfoot').find('.kas').text(convertToRupiah(parseInt(alltotkas)));
                $('.kt-section__content table').find('tfoot').find('.moker').text(convertToRupiah(parseInt(alltotmoker)));
                $('.kt-section__content table').find('tfoot').find('.selisih').text(convertToRupiah(parseInt(alltotselisih)));
                $('.kt-section__content table').append(template);

                //console.log(response.data.JABRAY);
				// if(response.status == true){
				// 	var template = '';
				// 	var no = 1;
                //     var selisih     = 0;
                //     var totkas      = 0;
                //     var totmoker    = 0;
                //     var totselisih  = 0;
                //     var status = "";
                //     var keterangan ="";
                   
				// 	$.each(response.data, function (index, data) {
                //         data.forEach(element => console.log(element));
                //         for (const val of data) { // You can use `let` instead of `const` if you like
                //                 console.log(val);
                //             }
				// 		template += "<tr class='rowappend'>";
				// 		// template += "<td class='text-center'>"+no+"</td>";
				// 		// template += "<td class='text-left'>"+data.area+"</td>";
				// 		// template += "<td class='text-left'>"+data.cabang+"</td>";
				// 		// template += "<td class='text-left'>"+data.name+"</td>";
				// 		// template += "<td class='text-left'></td>";
				// 		// template += "<td class='text-left'></td>";
				// 		// template += "<td class='text-left'></td>";
				// 		// template += "<td class='text-left'></td>";
				// 		// template += "<td class='text-left'></td>";
                //         // if(data.bapkas!=null){
                //         //     selisih = parseInt(data.bapkas - );
                //         //     template += "<td class='text-right'>"+convertToRupiah(parseInt(data.bapkas))+"</td>";
                //         //     template += "<td class='text-right'>"+convertToRupiah(parseInt())+"</td>";
                //         //     template += "<td class='text-right'>"+convertToRupiah(parseInt(selisih))+"</td>";
                //         //     if(parseInt(selisih)>0){status=" <span class='badge badge-danger'><i class='fa fa-arrow-alt-circle-up'></i></span>"; keterangan=" Mutasi ke cabang lain";}else{status="<span class='badge badge-warning'><i class='fa fa-arrow-alt-circle-down'></i></span>"; keterangan="Minta mutasi dari cabang lain";}
                //         //     template += "<td class='text-center'>"+status+"</td>";
                //         //     template += "<td class='text-left'>"+keterangan+"</td>";
                //         //     totkas += parseInt(data.bapkas);
                //         //     totmoker += parseInt();
                //         //     totselisih += parseInt(selisih);
                //         // }
                //         template += '</tr>';
                //         //console.log(data);
                //         no++;
				// 	});
                //     // $('.kt-section__content table').find('tfoot').find('.kas').text(convertToRupiah(parseInt(totkas)));
                //     // $('.kt-section__content table').find('tfoot').find('.moker').text(convertToRupiah(parseInt(totmoker)));
                //     // $('.kt-section__content table').find('tfoot').find('.selisih').text(convertToRupiah(parseInt(totselisih)));
				// 	$('.kt-section__content table').append(template);
				// }
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
                    $('#saldoawal').val(formatRupiah(response.data));
                    $('#penerimaan').val(formatRupiah(response.data.amount_in));
                    $('#pengeluaran').val(formatRupiah(response.data.amount_out));
                    $('#mutasi').val(formatRupiah(response.data.amount_mutation));
                    $('#saldoakhir').val(formatRupiah(response.data));
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

const initArea = ()=>{
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/gcore/datamaster/areas"); ?>",
        dataType : "json",
        success:function(res){
            let template = '<option value="all">All</option>';
            res.data.forEach(res=>{
                console.log(res.id)
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="area_id"]').append(template);
        },
        error:function(e){
            console.log(e);
        }
    });
    initFillBrand();
    initFillUnit();
}

const initFillBrand =() =>{
    let temp = '<option value="all">All</option>';
    $('[name="branch_id"]').append(temp);
}

const initFillUnit =() =>{
    let temp = '<option value="all">All</option>';
    $('[name="unit_id"]').append(temp);
}


const initCabang = () => {
    let area_id = $('#area_id').val();
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/branchies"); ?>/" + area_id,
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                console.log(res.id)
                console.log('iya')
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="branch_id"]').append(template);
        },
        error: function(e) {
            console.log(e);
        }
    });
}

const initUnit = () => {
    let branch_id = $('#branch_id').val();
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/gcore/datamaster/units"); ?>/" + branch_id,
        dataType: "json",
        success: function(res) {
            let template = '<option value="all">All</option>';
            res.data.forEach(res => {
                console.log(res.id)
                console.log('iya')
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="unit_id"]').append(template);
        },
        error: function(e) {
            console.log(e);
        }
    });
}

$('[name="area_id"]').on('change', function(){
    let area_id = $(this).val();
    $('[name="branch_id"]').empty();
    $('[name="unit_id"]').empty();
    initFillUnit();
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/gcore/datamaster/branchies"); ?>/"+area_id,
        dataType : "json",
        success:function(res){
            let template = '<option value="all">All</option>';
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

var type = $('[name="area_id"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
}

$('[name="branch_id"]').on('change', function(){
    let branch_id = $(this).val();
    $('[name="unit_id"]').empty();
    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/gcore/datamaster/units"); ?>/"+branch_id,
        dataType : "json",
        success:function(res){
            let template = '<option value="all">All</option>';
            res.data.forEach(res=>{
                template += `<option value="${res.id}">${res.name}</option>`;
            })
            $('[name="unit_id"]').append(template);
        },
        error:function(e){
            console.log(e);
        }
    });
})

var typecabang = $('[name="branch_id"]').attr('type');
if(typecabang == 'hidden'){
	$('[name="cabang"]').trigger('change');
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

$('.select2').select2();
$('.select2').on('select2:closing', function (e) {
    let branch_id = $('#branch_id').val();
    var search = $('.select2-search__field').val();
    console.log('Final Search Value: ' + search);

    $.ajax({
        type : 'GET',
        url : "<?php echo base_url("api/gcore/datamaster/units_search"); ?>/"+branch_id + "/" + search,
        dataType : "json",
        success:function(res){
            console.log(res);
            let template = '';
            res.data.forEach(res=>{
                console.log(res.name);
                session = `<?php echo $this->session->userdata( 'user' )->level ?>`
                
                if(session == 'area'){
                    
                    sessionId = `<?php echo $this->session->userdata( 'user' )->area_id ?>`
                    console.log('res', res.area_id)
                    console.log('session', sessionId)
                    if (sessionId == res.area_id){
                        template += `<option value="${res.id}">${res.name}</option>`;
                    }
                }else{
                    template += `<option value="${res.id}">${res.name}</option>`;
                }
                
            })
            
            $('[name="unit_id"]').empty();
            template += `<option value="all">All</option>`;
            $('[name="unit_id"]').append(template);
        },
        error:function(e){
            console.log(e);
        }
    });
});

jQuery(document).ready(function() {
    initCariForm();
    //initGetUnit();

    initArea();
    initCabang();
    initUnit();

    $(document).on("click", ".viewBtn", function () {
                var el = $(this);
                popView(el);
    });
});


</script>
