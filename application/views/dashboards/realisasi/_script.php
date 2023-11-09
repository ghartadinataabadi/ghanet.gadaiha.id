<script>
//globals
var area = "<?php echo $area?>";
var month = '<?php echo $month?>';
var year = '<?php echo $year;?>';

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

$(document).ready(function(){
	targetBooking();
})

$('#area').select2({ placeholder: "Select Area", width: '100%' });
$('#unit').select2({ placeholder: "Select Area", width: '100%' });
$('#month').select2({ placeholder: "Select Month", width: '100%' });
$('#tahun').select2({ placeholder: "Select Year", width: '100%' });

$('#btncari').on('click', function(){

	targetBooking();
});


function convertToRupiah(angka)
{
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return rupiah.split('',rupiah.length-1).reverse().join('');
}

function targetBooking(){

KTApp.block('#form_tarBook .kt-widget14', {});
buildTargetBooking();
}

const buildTargetBooking = ()=>{	
	area = $('#area').val();
	month = $('#month').val();
	year = $('#tahun').val();
	var booking 	= [];
	var target 		= [];
	var percentage 	= [];
	var unitlabel 	= [];
	var tottarget 	 = 0;
	var totrealisasi = 0;

	var os = [];
	var ostarget = [];
	var ospercentage = [];
	var ostottarget = 0;
	var ostotrealisasi = 0;

	$.ajax({
	url:"<?php echo base_url('api/datamaster/unitstarget/reportreal');?>",
	type:"GET",
	dataType:"JSON",
	data:{
		area, year,month
	},
	success:function (response) {
		var Tempjabar 	= "";
		var Tempjatim1	= "";
		var Tempjatim2	= "";
		var Tempntt		= "";
		var Tempntb		= "";
		var Tempall		= "";
		var totjabar 	= 0;
		var totjabarreal= 0;
		var totjatim2 	= 0;
		var totjatim1 	= 0;
		var totjatimreal1 = 0;
		var totjatimreal2 = 0;
		var totntb 		= 0;
		var totntbreal	= 0;
		var totntt 		= 0;
		var totnttreal	= 0;
		var totall 		= 0;
		var status		="";


		var osTempjabar 	= "";
		var osTempjatim1	= "";
		var osTempjatim2	= "";
		var osTempntt		= "";
		var osTempntb		= "";
		var osTempall		= "";
		var ostotjabar 	= 0;
		var ostotjabarreal= 0;
		var ostotjatim1 	= 0;
		var ostotjatim2 	= 0;
		var ostotjatimreal1= 0;
		var ostotjatimreal2= 0;
		var ostotntb 		= 0;
		var ostotntbreal	= 0;
		var ostotntt 		= 0;
		var ostotnttreal	= 0;
		var ostotall 		= 0;
		var osstatus		="";

		$.each(response.data, function (index,unit) {
			unitlabel.push(unit.name);
			booking.push(unit.booking.real);		
			target.push(unit.booking.target);
			percentage.push(unit.booking.percentage);

			os.push(unit.outstanding.real);
			ostarget.push(unit.outstanding.target);
			ospercentage.push(unit.outstanding.percentage);

			ostottarget +=	unit.outstanding.target;	
			ostotrealisasi +=	unit.outstanding.target;	

			tottarget += unit.booking.target;			
			totrealisasi += unit.booking.target;	

			if(unit.area=='Jawa Barat')
			{
				Tempjabar += "<tr class='rowappendjabar'>";
				Tempjabar += "<td class='text-left'><b>"+unit.area+"</b></td>";
				Tempjabar += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.booking.percentage!=0){
					if(unit.booking.percentage >=100)
					{ status="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{status="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					status="";
				}
				Tempjabar += "<td class='text-center'><b>"+status+"</b></td>";
				Tempjabar += "<td class='text-right'><b>"+convertToRupiah(unit.booking.target)+"</b></td>";
				Tempjabar += "<td class='text-right'><b>"+convertToRupiah(unit.booking.real)+"</b></td>";
				Tempjabar += "<td class='text-right'><b>"+unit.booking.percentage+"</b></td>";
				Tempjabar += '</tr>';
				totjabar += parseInt(unit.booking.target);
				totjabarreal += parseInt(unit.booking.real);

				osTempjabar += "<tr class='rowappendjabar'>";
				osTempjabar += "<td class='text-left'><b>"+unit.area+"</b></td>";
				osTempjabar += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.outstanding.percentage!=0){
					if(unit.outstanding.percentage >=100)
					{ osstatus="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{osstatus="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					osstatus="";
				}
				osTempjabar += "<td class='text-center'><b>"+osstatus+"</b></td>";
				osTempjabar += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.target)+"</b></td>";
				osTempjabar += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.real)+"</b></td>";
				osTempjabar += "<td class='text-right'><b>"+unit.outstanding.percentage+"</b></td>";
				osTempjabar += '</tr>';
				ostotjabar += parseInt(unit.outstanding.target);
				ostotjabarreal += parseInt(unit.outstanding.real);
			}

			if(unit.area=='Jawa Timur I')
			{
				Tempjatim1 += "<tr class='rowappendjabar'>";
				Tempjatim1 += "<td class='text-left'><b>"+unit.area+"</b></td>";
				Tempjatim1 += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.booking.percentage!=0){
					if(unit.booking.percentage >=100)
					{ status="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{status="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					status="";
				}
				Tempjatim1 += "<td class='text-center'><b>"+status+"</b></td>";
				Tempjatim1 += "<td class='text-right'><b>"+convertToRupiah(unit.booking.target)+"</b></td>";
				Tempjatim1 += "<td class='text-right'><b>"+convertToRupiah(unit.booking.real)+"</b></td>";
				Tempjatim1 += "<td class='text-right'><b>"+unit.booking.percentage+"</b></td>";
				Tempjatim1 += '</tr>';
				totjatim1 += parseInt(unit.booking.target);
				totjatimreal1 += parseInt(unit.booking.real);

				osTempjatim1 += "<tr class='rowappendjabar'>";
				osTempjatim1 += "<td class='text-left'><b>"+unit.area+"</b></td>";
				osTempjatim1 += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.outstanding.percentage!=0){
					if(unit.outstanding.percentage >=100)
					{ osstatus="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{osstatus="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					osstatus="";
				}
				osTempjatim1 += "<td class='text-center'><b>"+osstatus+"</b></td>";
				osTempjatim1 += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.target)+"</b></td>";
				osTempjatim1 += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.real)+"</b></td>";
				osTempjatim1 += "<td class='text-right'><b>"+unit.outstanding.percentage+"</b></td>";
				osTempjatim1 += '</tr>';
				ostotjatim1 += parseInt(unit.outstanding.target);
				ostotjatimreal1 += parseInt(unit.outstanding.real);

			}

			if(unit.area=='Jawa Timur II')
			{
				Tempjatim2 += "<tr class='rowappendjabar'>";
				Tempjatim2 += "<td class='text-left'><b>"+unit.area+"</b></td>";
				Tempjatim2 += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.booking.percentage!=0){
					if(unit.booking.percentage >=100)
					{ status="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{status="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					status="";
				}
				Tempjatim2 += "<td class='text-center'><b>"+status+"</b></td>";
				Tempjatim2 += "<td class='text-right'><b>"+convertToRupiah(unit.booking.target)+"</b></td>";
				Tempjatim2 += "<td class='text-right'><b>"+convertToRupiah(unit.booking.real)+"</b></td>";
				Tempjatim2 += "<td class='text-right'><b>"+unit.booking.percentage+"</b></td>";
				Tempjatim2 += '</tr>';
				totjatim2 += parseInt(unit.booking.target);
				totjatimreal2 += parseInt(unit.booking.real);

				osTempjatim2 += "<tr class='rowappendjabar'>";
				osTempjatim2 += "<td class='text-left'><b>"+unit.area+"</b></td>";
				osTempjatim2 += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.outstanding.percentage!=0){
					if(unit.outstanding.percentage >=100)
					{ osstatus="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{osstatus="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					osstatus="";
				}
				osTempjatim2 += "<td class='text-center'><b>"+osstatus+"</b></td>";
				osTempjatim2 += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.target)+"</b></td>";
				osTempjatim2 += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.real)+"</b></td>";
				osTempjatim2 += "<td class='text-right'><b>"+unit.outstanding.percentage+"</b></td>";
				osTempjatim2 += '</tr>';
				ostotjatim2 += parseInt(unit.outstanding.target);
				ostotjatimreal2 += parseInt(unit.outstanding.real);

			}

			if(unit.area=='NTB')
			{
				Tempntb += "<tr class='rowappendjabar'>";
				Tempntb += "<td class='text-left'><b>"+unit.area+"</b></td>";
				Tempntb += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.booking.percentage!=0){
					if(unit.booking.percentage >=100)
					{ status="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{status="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					status="";
				}
				Tempntb += "<td class='text-center'><b>"+status+"</b></td>";
				Tempntb += "<td class='text-right'><b>"+convertToRupiah(unit.booking.target)+"</b></td>";
				Tempntb += "<td class='text-right'><b>"+convertToRupiah(unit.booking.real)+"</b></td>";
				Tempntb += "<td class='text-right'><b>"+unit.booking.percentage+"</b></td>";
				Tempntb += '</tr>';
				totntb += parseInt(unit.booking.target);
				totntbreal += parseInt(unit.booking.real);
				// os
				osTempntb += "<tr class='rowappendjabar'>";
				osTempntb += "<td class='text-left'><b>"+unit.area+"</b></td>";
				osTempntb += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.outstanding.percentage!=0){
					if(unit.outstanding.percentage >=100)
					{ osstatus="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{osstatus="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					osstatus="";
				}
				osTempntb += "<td class='text-center'><b>"+osstatus+"</b></td>";
				osTempntb += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.target)+"</b></td>";
				osTempntb += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.real)+"</b></td>";
				osTempntb += "<td class='text-right'><b>"+unit.outstanding.percentage+"</b></td>";
				osTempntb += '</tr>';
				ostotntb += parseInt(unit.outstanding.target);
				ostotntbreal += parseInt(unit.outstanding.real);
			}

			if(unit.area=='NTT')
			{
				Tempntt += "<tr class='rowappendjabar'>";
				Tempntt += "<td class='text-left'><b>"+unit.area+"</b></td>";
				Tempntt += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.booking.percentage!=0){
					if(unit.booking.percentage >=100)
					{ status="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{status="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					status="";
				}
				Tempntt += "<td class='text-center'><b>"+status+"</b></td>";
				Tempntt += "<td class='text-right'><b>"+convertToRupiah(unit.booking.target)+"</b></td>";
				Tempntt += "<td class='text-right'><b>"+convertToRupiah(unit.booking.real)+"</b></td>";
				Tempntt += "<td class='text-right'><b>"+unit.booking.percentage+"</b></td>";
				Tempntt += '</tr>';
				totntt += parseInt(unit.booking.target);
				totnttreal += parseInt(unit.booking.real);
				//os
				osTempntt += "<tr class='rowappendjabar'>";
				osTempntt += "<td class='text-left'><b>"+unit.area+"</b></td>";
				osTempntt += "<td class='text-left'><b>"+unit.name+"</b></td>";
				if(unit.outstanding.percentage!=0){
					if(unit.outstanding.percentage >=100)
					{ osstatus="<i class='fa fa-caret-up text-success'></i><span class='kt-widget16__price  kt-font-success'> Melebihi Target</span>";}
					else
					{osstatus="<i class='fa fa-caret-down text-danger'></i><span class='kt-widget16__price  kt-font-danger'> Dibawah Target</span>";}
				}else{
					osstatus="";
				}
				osTempntt += "<td class='text-center'><b>"+osstatus+"</b></td>";
				osTempntt += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.target)+"</b></td>";
				osTempntt += "<td class='text-right'><b>"+convertToRupiah(unit.outstanding.real)+"</b></td>";
				osTempntt += "<td class='text-right'><b>"+unit.outstanding.percentage+"</b></td>";
				osTempntt += '</tr>';
				ostotntt += parseInt(unit.outstanding.target);
				ostotnttreal += parseInt(unit.outstanding.real);
			}		
		});

		if(Tempjabar){
			Tempjabar += "<tr class='rowappendjabar'>";
			Tempjabar += "<td class='text-right' colspan='3'> Total Jawa Barat </td>";
			Tempjabar += "<td class='text-right'><b>"+convertToRupiah(totjabar)+"</b></td>";
			Tempjabar += "<td class='text-right'><b>"+convertToRupiah(totjabarreal)+"</b></td>";
			Tempjabar += "<td class='text-right'><b></b></td>";
			Tempjabar += '</tr>';

			osTempjabar += "<tr class='rowappendjabar'>";
			osTempjabar += "<td class='text-right' colspan='3'> Total Jawa Barat </td>";
			osTempjabar += "<td class='text-right'><b>"+convertToRupiah(ostotjabar)+"</b></td>";
			osTempjabar += "<td class='text-right'><b>"+convertToRupiah(ostotjabarreal)+"</b></td>";
			osTempjabar += "<td class='text-right'><b></b></td>";
			osTempjabar += '</tr>';
		}

		

		if(Tempjatim1){
			Tempjatim1 += "<tr class='rowappendjabar'>";
			Tempjatim1 += "<td class='text-right' colspan='3'> Total Jawa Timur I</td>";
			Tempjatim1 += "<td class='text-right'><b>"+convertToRupiah(totjatim1)+"</b></td>";
			Tempjatim1 += "<td class='text-right'><b>"+convertToRupiah(totjatimreal1)+"</b></td>";
			Tempjatim1 += "<td class='text-right'><b></b></td>";
			Tempjatim1 += '</tr>';

			osTempjatim1 += "<tr class='rowappendjabar'>";
			osTempjatim1 += "<td class='text-right' colspan='3'> Total Jawa Timur I</td>";
			osTempjatim1 += "<td class='text-right'><b>"+convertToRupiah(ostotjatim1)+"</b></td>";
			osTempjatim1 += "<td class='text-right'><b>"+convertToRupiah(ostotjatimreal1)+"</b></td>";
			osTempjatim1 += "<td class='text-right'><b></b></td>";
			osTempjatim1 += '</tr>';
		}

		if(Tempjatim2){
			Tempjatim2 += "<tr class='rowappendjabar'>";
			Tempjatim2 += "<td class='text-right' colspan='3'> Total Jawa Timur I</td>";
			Tempjatim2 += "<td class='text-right'><b>"+convertToRupiah(totjatim2)+"</b></td>";
			Tempjatim2 += "<td class='text-right'><b>"+convertToRupiah(totjatimreal2)+"</b></td>";
			Tempjatim2 += "<td class='text-right'><b></b></td>";
			Tempjatim2 += '</tr>';

			osTempjatim2 += "<tr class='rowappendjabar'>";
			osTempjatim2 += "<td class='text-right' colspan='3'> Total Jawa Timur I</td>";
			osTempjatim2 += "<td class='text-right'><b>"+convertToRupiah(ostotjatim2)+"</b></td>";
			osTempjatim2 += "<td class='text-right'><b>"+convertToRupiah(ostotjatimreal2)+"</b></td>";
			osTempjatim2 += "<td class='text-right'><b></b></td>";
			osTempjatim2 += '</tr>';
		}

		if(Tempntt){
			Tempntt += "<tr class='rowappendjabar'>";
			Tempntt += "<td class='text-right' colspan='3'> Total NTT </td>";
			Tempntt += "<td class='text-right'><b>"+convertToRupiah(totntt)+"</b></td>";
			Tempntt += "<td class='text-right'><b>"+convertToRupiah(totnttreal)+"</b></td>";
			Tempntt += "<td class='text-right'><b></b></td>";
			Tempntt += '</tr>';

			osTempntt += "<tr class='rowappendjabar'>";
			osTempntt += "<td class='text-right' colspan='3'> Total NTT </td>";
			osTempntt += "<td class='text-right'><b>"+convertToRupiah(ostotntt)+"</b></td>";
			osTempntt += "<td class='text-right'><b>"+convertToRupiah(ostotnttreal)+"</b></td>";
			osTempntt += "<td class='text-right'><b></b></td>";
			osTempntt += '</tr>';
		}

		if(Tempntb){
			Tempntb += "<tr class='rowappendjabar'>";
			Tempntb += "<td class='text-right' colspan='3'> Total NTB </td>";
			Tempntb += "<td class='text-right'><b>"+convertToRupiah(totntb)+"</b></td>";
			Tempntb += "<td class='text-right'><b>"+convertToRupiah(totntbreal)+"</b></td>";
			Tempntb += "<td class='text-right'><b></b></td>";
			Tempntb += '</tr>';

			osTempntb += "<tr class='rowappendjabar'>";
			osTempntb += "<td class='text-right' colspan='3'> Total NTB </td>";
			osTempntb += "<td class='text-right'><b>"+convertToRupiah(ostotntb)+"</b></td>";
			osTempntb += "<td class='text-right'><b>"+convertToRupiah(ostotntbreal)+"</b></td>";
			osTempntb += "<td class='text-right'><b></b></td>";
			osTempntb += '</tr>';
		}
		var realtotal =0;
		var osrealtotal = 0;
		totall = parseInt(totjabar)+parseInt(totjatim1)+parseInt(totntt)+parseInt(totntb)+parseInt(totjatim2);
		realtotal = parseInt(totjabarreal)+parseInt(totjatimreal2)+parseInt(totjatimreal1)+parseInt(totnttreal)+parseInt(totntbreal);
		ostotall = parseInt(ostotjabar)+parseInt(ostotjatim1)+parseInt(ostotntt)+parseInt(ostotntb)+parseInt(ostotjatim2);
		osrealtotal = parseInt(ostotjabarreal)+parseInt(ostotjatimreal2)+parseInt(ostotjatimreal1)+parseInt(ostotnttreal)+parseInt(ostotntbreal);
	
	
		if(totall){
			Tempall += "<tr class='rowappendjabar'>";
			Tempall += "<td class='text-right' colspan='3'> Total </td>";
			Tempall += "<td class='text-right'><b>"+convertToRupiah(totall)+"</b></td>";
			Tempall += "<td class='text-right'><b>"+convertToRupiah(realtotal)+"</b></td>";
			Tempall += '</tr>';

			osTempall += "<tr class='rowappendjabar'>";
			osTempall += "<td class='text-right' colspan='3'> Total </td>";
			osTempall += "<td class='text-right'><b>"+convertToRupiah(ostotall)+"</b></td>";
			osTempall += "<td class='text-right'><b>"+convertToRupiah(osrealtotal)+"</b></td>";
			osTempall += '</tr>';
		}				

		$('#tbltarBook').append(Tempjabar);
		$('#tbltarBook').append(Tempjatim1);
		$('#tbltarBook').append(Tempjatim2);
		$('#tbltarBook').append(Tempntb);
		$('#tbltarBook').append(Tempntt);
		$('#tbltarBook').append(Tempall);

		$('#ostbltarBook').append(osTempjabar);
		$('#ostbltarBook').append(osTempjatim1);
		$('#ostbltarBook').append(osTempjatim2);
		$('#ostbltarBook').append(osTempntb);
		$('#ostbltarBook').append(osTempntt);
		$('#ostbltarBook').append(osTempall);

	},
	complete:function () {
		$('#form_tarBook').find('.total-target').text(convertToRupiah(tottarget));
		$('#form_tarBook').find('.total-realisasi').text(convertToRupiah(totrealisasi));			
		$('#form_booking').find('.total-target').text(convertToRupiah(ostottarget));
		$('#form_booking').find('.total-realisasi').text(convertToRupiah(ostotrealisasi));			
	
		var datapercentage 	= percentage;
		var databooking 	= booking;
		var datatarget 		= target;
		var dataunitlabel 	= unitlabel;

		var dataos = [
				{
					label: 'Target',
					backgroundColor: '#006699',
					yAxisID: 'A',
					data: ostarget
				},
				{
					label: 'Realisasi',
					backgroundColor: '#FFA000',
					yAxisID: 'A',
					data: os
				}
			];


		var data = [
		{
			label: 'Target',
			backgroundColor: '#006699',
			yAxisID: 'A',
			data: datatarget
		},
		 {
			label: 'Realisasi',
			backgroundColor: '#FFA000',
			yAxisID: 'A',
			data: databooking
		}];
		
		
		var options = {
			tooltips: {
				mode: 'label',
			},						
			scales: {
				xAxes: [{
						stacked: false,
						gridLines: {
							display: false
						}
					}],
				yAxes: [{
						id: 'A',
						stacked: false,						
						ticks: {
							beginAtZero: true,
							callback: function (value) {
								valuek = convertToRupiah(value) ;
								return valuek;
							}
						}
					},{
							id: 'B',
							type: 'linear',
							position: 'right',
							ticks: {
								max: 200,
								min: 0,
							}
					}]
			}
	};

	var ctxOs = document.getElementById("graphtarOutstanding");
	var myChartOs = new Chart(ctxOs, {
		type: 'bar',
		data: {
			labels: dataunitlabel,
			datasets: dataos
		},
		options: options
	});

	var ctx = document.getElementById("graphtarBooking");
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: dataunitlabel,
			datasets: data
		},
		options: options
	});



	KTApp.unblock('#form_tarBook .kt-widget14', {});


	},
});
}

</script>
