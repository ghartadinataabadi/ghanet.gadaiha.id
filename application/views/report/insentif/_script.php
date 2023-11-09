<script>
//globals
var cariForm;

function convertToRupiah(angka)
{
    if(!angka) return 0;
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
            // cabang: {
            //     required: true,
            // },
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
        // KTApp.block('#tblsm', {});
        var month= $('[name="month"]').val();
        var year=$('[name="year"]').val();
        $('#tblsm').find('tbody').find('tr').remove();
        $('#tblsm').find('tfoot').find('tr').remove();
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/transactions/regularpawns/insentif"); ?>",
			dataType : "json",
			data:{month, year},
			success : function(response,status){
                const { details } = response.data;
                let  i = 1;
                let admin = 0;
                details.forEach(data=>{
                    let tr  = '<tr class="rowappend">';
                    tr += `<td>${i}</td>`;
                    tr += `<td>${data.area}</td>`;
                    tr += `<td>${data.name}</td>`;
                    tr += `<td class="text-right">${convertToRupiah(data.admin)}</td>`;
                    tr += `<td class="text-right">${convertToRupiah(data.booking)}</td>`;
                    tr += `<td class="text-right">${data.target_booking ? convertToRupiah(data.target_booking) : 0}</td>`;
                    tr += `<td class="text-right">${data.target_booking ? (data.booking/data.target_booking*100).toFixed(2) : 0} %</td>`;
                    tr += `<td class="text-right">${convertToRupiah(data.outstanding)}</td>`;
                    tr += `<td class="text-right">${data.target_os ? convertToRupiah(data.target_os) : 0}</td>`;
                    tr += `<td class="text-right">${data.target_os ? (data.outstanding/data.target_os*100).toFixed(2) : 0} %</td>`;
					      tr += `<td>
                    <button class="btn btn-info" onclick="details(${data.id},${month},${year})" type="button">Detail</button>
                    </td>`;
                    tr += '<tr/>';
                    i++;
                    admin += parseInt(data.admin);
                    $('#tblsm').find('tbody').append(tr);
                });
                let tr  = '<tr class="rowappend">';
                    tr += `<td>Total Admin</td>`;
                    tr += `<td>${convertToRupiah(admin)}</td>`;
                    tr += `<td>Insentif Unit</td>`;
                    tr += `<td class="text-center">${convertToRupiah(Math.round(35/100*admin))}</td>`;
                    tr += `<td>Insentif Holding</td>`;
                    tr += `<td class="text-center">${convertToRupiah(Math.round(25/100*admin))}</td>`;
                    tr += `<td>Insentif Asuransi</td>`;
                    tr += `<td class="text-center">${convertToRupiah(Math.round(40/100*admin))}</td>`;
                    tr += '<tr/>';
                    i++;
                $('#tblsm').find('tbody').append(tr);
				KTApp.unblockPage();
			},
			error: function (jqXHR, textStatus, errorThrown){
				KTApp.unblockPage();
			},
			complete:function () {
				KTApp.unblock('#tblsm', {});
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

var type = $('[name="area"]').attr('type');
if(type == 'hidden'){
    $('[name="area"]').trigger('change');
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
});

const getExcel = () => {
    const year = $('[name="year"]').val();
    const month = $('[name="month"]').val();
    return window.location.href = `<?php echo base_url('report/insentif/export');?>?year=${year}&month=${month}`;
}

const getRegExcel = () =>{
    const year = $('[name="year"]').val();
    const month = $('[name="month"]').val();
    return window.location.href = `<?php echo base_url('report/insentif/export_regular');?>?year=${year}&month=${month}`;

}

const getCicilExcel = () =>{
    const year = $('[name="year"]').val();
    const month = $('[name="month"]').val();
    return window.location.href = `<?php echo base_url('report/insentif/export_cicilan');?>?year=${year}&month=${month}`;

}


const details = (id_unit, month, year) => {
    return window.location.href = `<?php echo base_url('report/insentif/export_detail');?>?id_unit=${id_unit}&year=${year}&month=${month}`;
}


const kpidetail = (id_unit, month, year) =>{
    $.ajax({
        url:`<?php echo base_url();?>api/transaction/regularpawns/kpi`,
        data:{id_unit, month, year},
        type:"GET",
        dataType:"JSON",
        success:function(res){
            console.log(res);
        }
    })
}


</script>
