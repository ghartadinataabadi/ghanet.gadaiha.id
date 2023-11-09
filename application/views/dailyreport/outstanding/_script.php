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

function initCariForm(){
    //validator
    //events
        $('.rowappend').remove();
		var area = "";
		var code = "";
		var dateStart = "2020-08-12";
		$.ajax({
			type : 'GET',
			url : "<?php echo base_url("api/dashboards/outstanding"); ?>",
			dataType : "json",
			data:{area:area,date:dateStart,id_unit:code},
			success : function(response,status){
				KTApp.unblockPage();
				var html = '';
				var int = 1;
				$.each(response.data, function (index, data) {
					html += '<tr>'
					html += '<td class="text-center">'+ int +'</td>';
					html += '<td>'+ data.name +'</td>';
					html += '<td>'+ data.area +'</td>';
					html += '<td> </td>';
					html += '<td> </td>';
					html += '<td class="text-center">'+data.ost_yesterday.noa+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.ost_yesterday.up)+'</td>';
					html += '<td class="text-center">'+data.credit_today.noa+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.credit_today.up)+'</td>';
					html += '<td class="text-center">'+data.repayment_today.noa+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.repayment_today.up)+'</td>';
					html += '<td class="text-center">'+data.total_outstanding.noa+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.total_outstanding.up)+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.total_outstanding.tiket)+'</td>';
					html += '<td class="text-center">'+data.total_disburse.noa+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.total_disburse.credit)+'</td>';
					html += '<td class="text-right">'+convertToRupiah(data.total_disburse.tiket.toFixed(2))+'</td>';
					html += '</tr>'
					int++;
				});
				$('.table').find('tbody').find('tr').remove();
				$('.table').find('tbody').html(html);
			},
			error: function (jqXHR, textStatus, errorThrown){
				//KTApp.unblockPage();
			},
			complete:function () {
				//KTApp.unblock('#form_bukukas .kt-portlet__body', {});
			}
		});
}

jQuery(document).ready(function() {
    initCariForm();
});

</script>
