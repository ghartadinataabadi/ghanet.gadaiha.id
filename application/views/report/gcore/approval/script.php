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

    $('#area').select2({ placeholder: "Select area", width: '100%' });
    $('#unit').select2({ placeholder: "Select Unit", width: '100%' });
    $('#status').select2({ placeholder: "Select a status", width: '100%' });
    //events
    $('#btncari').on('click',function(){
        $('.rowappend').remove();
        var area_id = $('[name="area_id"]').val();
        var branch_id = $('[name="branch_id"]').val();
        var unit_id = $('[name="unit_id"]').val();        
        var product = $('[name="product"]').val();        
		var dateStart = $('[name="date-start"]').val();
		var dateEnd = $('[name="date-end"]').val();
		var approved = $('[name="approved"]').val();
		var deviation = $('[name="deviation"]').val();
        //alert(approved);
        KTApp.block('#form_bukukas .kt-portlet__body', {});
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/gcore/deviation"); ?>",
			dataType : "json",
            data:{area_id:area_id,branch_id:branch_id, unit_id:unit_id,product:product,dateStart:dateStart,dateEnd:dateEnd,deviation:deviation,approved:approved},
			success : function(response,status){
				KTApp.unblockPage();
				if(response.status == true){
					 var template = '';
					 var no = 1;
					 var totbayar = 0;
					 var totadmin = 0;
					 var totup = 0;
					 var totamount = 0;
					 var deviation = '';
					 var approval = '';

					$.each(response.data, function (index, data) {
						var dpd = parseInt(date_between(`${data.due_date}`,"<?php echo date('Y/m/d');?>"));
						template += "<tr class='rowappend'>";
						template += "<td class='text-center'>"+no+"</td>";
						template += "<td class='text-left'>"+data.office_name+"</td>";
                        template += "<td class='text-left'>"+data.product_name+"</td>";
                        template += "<td class='text-left'>"+data.insurance_item_name+"</td>";
                        template += "<td class='text-left'>"+data.customer+"</td>";
						template += "<td class='text-left'>"+data.sge+"</td>";                        
						template += "<td class='text-center'>"+moment(data.contract_date).format('DD-MM-YYYY')+"</td>";
                        template += "<td class='text-center'>"+moment(data.due_date).format('DD-MM-YYYY')+"</td>";
                        //template += "<td class='text-center'>"+moment(data.auction_date).format('DD-MM-YYYY')+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.estimated_value)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.loan_amount)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.admin_fee)+"</td>";
						template += "<td class='text-right'>"+convertToRupiah(data.monthly_fee)+"</td>";
                        //totbayar = data.loan_amount+data.admin_fee;
						//template += "<td class='text-right'>"+convertToRupiah(totbayar)+"</td>";
						template += "<td class='text-center'>"+data.interest_rate+"</td>";
                        if(data.deviation_type=='0'){deviation='LTV';}
                        else if(data.deviation_type=='1'){deviation='Sewa';}
                        else if(data.deviation_type=='2'){deviation='Admin';}
                        else if(data.deviation_type=='3'){deviation='One Obligor';}
                        else if(data.deviation_type=='5'){deviation='Limit Transaksi';}
                        else{deviation='-';}
						template += "<td class='text-center'>"+deviation+"</td>";

                        if(data.office_type==0){approval='Cabang';}
                        if(data.office_type==1){approval='Area';}
                        if(data.office_type==2){approval='Regional';}
                        if(data.office_type==3){approval='Pusat';}
						template += "<td class='text-left'>"+approval+"</td>";
						template += "<td class='text-right'>"+data.description+"</td>";
						template += '</tr>';
						no++;
                        totadmin    += parseInt(data.admin_fee);
                        totup       += parseInt(data.loan_amount);
                        totamount   += parseInt(totbayar);
					});
                        // template += "<tr class='rowappend'>";
                        // template += "<td colspan='9' class='text-right'>Total</td>";
                        // template += "<td class='text-right'>"+convertToRupiah(totup)+"</td>";
                        // template += "<td class='text-right'>"+convertToRupiah(totadmin)+"</td>";
                        // template += "<td class='text-right'>"+convertToRupiah(totamount)+"</td>";
                        // template += "<td class='text-right'></td>";
                        // template += "<td class='text-right'></td>";
                        // template += "<td class='text-right'></td>";
                        // template += '</tr>';
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

// $(document).ready(function(){
//     $('#btncari').trigger('click');
// })


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

$('#btnexport_pdf').on('click', function(e){
    var area = $('[name="area_id"]').val() ? $('[name="area_id"]').val() : '';
    var cabang = $('[name="branch_id"]').val() ? $('[name="branch_id"]').val() : '';
    var unit = $('[name="unit_id"]').val() ? $('[name="unit_id"]').val() : '';
    var date = $('[name="date"]').val() ? $('[name="date"]').val() : '';
    console.log(date);
    window.location.href = `<?php echo base_url('report/gcore/pdf');?>?area=${area}&cabang=${cabang}&unit=${unit}&date=${date}`;
});

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
    initArea();
     initCabang();
        initUnit();
});
</script>