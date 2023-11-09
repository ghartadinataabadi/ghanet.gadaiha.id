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

function datediff(due_date){
    var currdate =  new Date();
    var date1 = new Date(currdate);
	var date2 = new Date(due_date);
    var Difference_In_Time = date2.getTime() - date1.getTime();
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    return Difference_In_Days;
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

    $('#area').select2({ placeholder: "Select area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a status", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area_id = $('[name="area_id"]').val();
        var branch_id = $('[name="branch_id"]').val();
        var unit_id = $('[name="unit_id"]').val();        
        //var product = $('[name="product"]').val();        
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();

        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/gcore/dpdmortages"); ?>",
			dataType : "json",
            data:{area_id:area_id,branch_id:branch_id, unit_id:unit_id,dateStart:dateStart,dateEnd:dateEnd},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					var template = '';
					 var no = 1;
					 var totbuy = 0;
					 var totadmin = 0;
					 var totdp = 0;
					 var totsewa = 0;
                     var totangsuran =0;
					 var totup = 0;
					 var totamountbuy = 0;
					 var dpddays = 0;
					 var denda = 0;
					 var gp = 5;
                    //alert(dateStart);
					$.each(response.data, function (index, data) {
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.office_name+"</td>";
						template += "<td class='text-left'>"+data.insurance_item_name+"</td>";
						template += "<td class='text-left'>"+data.customer+"</td>";
						template += "<td class='text-left'>"+data.telp+"</td>";
                        template += "<td class='text-left'>"+data.sge+"</td>";
						template += "<td class='text-center'>"+moment(data.contract_date).format('DD-MM-YYYY')+"</td>";
                        template += "<td class='text-center'>"+moment(data.due_date).format('DD-MM-YYYY')+"</td>"; 
                        template += "<td class='text-center'>"+data.tenor+"</td>";                      
                        template += "<td class='text-right'>"+convertToRupiah(data.loan_amount)+"</td>";                      
                        template += "<td class='text-right'>"+convertToRupiah(data.down_payment)+"</td>";                      
                        template += "<td class='text-right'>"+convertToRupiah(data.monthly_installment)+"</td>";  
                        totbuy =  parseInt(data.loan_amount) + parseInt(data.down_payment);
                        template += "<td class='text-right'>"+convertToRupiah(totbuy)+"</td>";                      
                        template += "<td class='text-right'>"+convertToRupiah(data.monthly_fee)+"</td>";                      
                        template += "<td class='text-center'>"+convertToRupiah(data.monthly_interest)+"</td>"; 
                        template += "<td class='text-center'>"+gp+"</td>"; 
                        dpddays = parseInt(data.dpd)+parseInt(gp);
                        template += "<td class='text-center'>"+dpddays+"</td>";  
                        denda = (dpddays < 0) ? parseFloat(dpddays/31)*parseFloat(data.monthly_fee) : denda=0;                                        
                        template += "<td class='text-right'>"+denda.toFixed(0)+"</td>";                       
                        template += "<td class='text-left'>"+data.description+"</td>";                      
						template += '</tr>';
						no++;
						totsewa += parseInt(data.monthly_fee);
						totangsuran += parseInt(data.monthly_installment);
						totdp += parseInt(data.down_payment);
						totup += parseInt(data.loan_amount);
						totamountbuy += parseInt(totbuy);
					});
					template += "<tr class='rowappend'>";
					template += "<td colspan='9' class='text-right'>Total</td>";
					template += "<td class='text-right'>"+convertToRupiah(totup)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totdp)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totangsuran)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totamountbuy)+"</td>";
					template += "<td class='text-right'>"+convertToRupiah(totsewa)+"</td>";
					template += "<td class='text-right'></td>";
					template += "<td class='text-right'></td>";
					template += "<td class='text-right'></td>";
					template += "<td class='text-left'></td>";
					template += "<td class='text-left'></td>";
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

function date_between(start, end){
    var date1 = new Date(`${start}`); 
    var date2 = new Date(`${end}`); 
    
    // To calculate the time difference of two dates 
    var Difference_In_Time = date2.getTime() - date1.getTime(); 
    
    // To calculate the no. of days between two dates 
    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24); 
    return Difference_In_Days+1;
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
    //alert('test');
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

$('#btnexport_pdf').on('click', function(e){
    var area = $('[name="area_id"]').val() ? $('[name="area_id"]').val() : '';
    var cabang = $('[name="branch_id"]').val() ? $('[name="branch_id"]').val() : '';
    var unit = $('[name="unit_id"]').val() ? $('[name="unit_id"]').val() : '';
    var date = $('[name="date"]').val() ? $('[name="date"]').val() : '';
    console.log(date);
    window.location.href = `<?php echo base_url('report/gcore/pdf');?>?area=${area}&cabang=${cabang}&unit=${unit}&date=${date}`;
});

jQuery(document).ready(function() {
    initCariForm();
    initArea();
     initCabang();
        initUnit();
});
</script>