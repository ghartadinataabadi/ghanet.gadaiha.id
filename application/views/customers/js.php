<script>

	$('.upload').on('click', function (e) {
		e.preventDefault();
		$('#modal-upload').modal('show');
	});
	$('#modal-form').on('click', function (e) {
		e.preventDefault();
		$(this).modal('show');
	});
	$('.form-input').on('submit', function (e) {
		e.preventDefault();
		var data = new FormData(this);
		console.log(data);
		$.ajax({
			url : '<?php echo base_url('api/datamaster/customers/upload');?>',
			type : 'POST',
			data : data,
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			success : function(response) {
				location.reload();
			}
		});
	});

	$(document).on('click','.btn-edit', function(e){
		e.preventDefault();
		$.ajax({
			url : '<?php echo base_url('api/datamaster/customers/show/');?>'+$(this).data('id'),
			dataType:'JSON',
			success : function(response) {
				var data = response.data;
				$('[name="id"]').val(data.id);
				$('[name="no_cif"]').val(data.no_cif);
				$('[name="nik"]').val(data.nik);
				$('[name="name"]').val(data.name);
				$('[name="mobile"]').val(data.mobile);
				$('[name="birth_date"]').val(data.birth_date);
				$('[name="birth_place"]').val(data.birth_place);
				$('[name="gender"]').val(data.gender);				
				$('[name="marital"]').val(data.marital);
				$('[name="marital"]').trigger('change');
				$('[name="province"]').val(data.province);
				$('[name="city"]').val(data.city);
				$('[name="address"]').val(data.address);
				$('[name="job"]').val(data.job);
				$('[name="mother_name"]').val(data.mother_name);
				$('[name="sibling_name"]').val(data.sibling_name);
				$('[name="sibling_birth_date"]').val(data.sibling_birth_date);
				$('[name="sibling_birth_place"]').val(data.sibling_birth_place);
				$('[name="sibling_job"]').val(data.sibling_job);
				$('[name="sibling_relation"]').val(data.sibling_relation);
				$('[name="sibling_address_1"]').val(data.sibling_address_1);
				$('[name="rt"]').val(data.rt);
				$('[name="rw"]').val(data.rw);
				$('[name="kelurahan"]').val(data.kelurahan);
				$('[name="kecamatan"]').val(data.kecamatan);
				$('[name="kodepos"]').val(data.kodepos);
				$('#modal-form').trigger('click');
			}
		});

		

	});

	$('.btn-save').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url : '<?php echo base_url('api/datamaster/customers/update');?>',
			type : 'POST',
			data : $('.form-modal').serialize(),
			success : function(response) {
				location.reload();
			}
		});
	});

	$('.btn-close').on('click', function (e) {
		e.preventDefault();
		// Coding
		$('#modal-form').modal('toggle'); //or  $('#IDModal').modal('hide');
		return false;
	});

	$('.close').on('click', function (e) {
		e.preventDefault();
		// Coding
		$('#modal-form').modal('toggle'); //or  $('#IDModal').modal('hide');
		return false;
	});

// function initCariForm(){
//     //validator
//     var validator = $("#form_usia").validate({
//         ignore:[],
//         rules: {
//             area: {
//                 required: true,
//             },
//             unit: {
//                 required: true,
//             }
//         },
//         invalidHandler: function(event, validator) {
//             KTUtil.scrollTop();
//         }
//     });

//     $('#usiadari').select2({ placeholder: " Select Usiadari", width: '80%' });
//     $('#usiasampai').select2({ placeholder: "Select Usiasampai", width: '80%' });
//     //events
//     $('#btncari').on('click',function(){
//         $('.rowappend').remove();
//         var usiadari = $('[name="usiadari"]').val();
//         var usiasampai = $('[name="usiasampai"]').val();
//         KTApp.block('#form_usia .kt-portlet__body', {});
// 		$.ajax({
// 			type : 'GET',
// 			url : "<?php echo base_url("api/datamaster/customers/usia"); ?>",
// 			dataType : "json",
// 			data:{usiadari:usiadari,usiasampai:usiasampaid},
// 			success : function(response,status){
// 				KTApp.unblockPage();
// 				if(response.status == true){
// 					var template = '';
// 					var no = 1;
// 					var amount = 0;
// 					var admin = 0;
//                     var status="";
// 					$.each(response.data, function (index, data) {
// 						template += "<tr class='rowappend'>";
// 						template += "<td class='text-center'>"+data.no_cif+"</td>";
// 						template += "<td class='text-center'>"+data.nik+"</td>";
// 						template += "<td class='text-center'>"+data.name+"</td>";
// 						template += "<td class='text-center'>"+data.gender+"</td>";
// 						template += "<td class='text-center'>"+data.marital+"</td>";
// 						template += "<td class='text-center'>"+data.birth_place+"</td>";
// 						template += "<td class='text-center'>"+data.birth_date+"</td>";
// 						template += "<td class='text-center'>"+data.mobile+"</td>";
// 						template += "<td class='text-center'>"+data.rt+"</td>";
// 						template += "<td class='text-center'>"+data.rw+"</td>";
// 						template += "<td class='text-center'>"+data.kelurahan+"</td>";
// 						template += "<td class='text-center'>"+data.kecamatan+"</td>";
// 						template += "<td class='text-center'>"+data.kodepos+"</td>";
// 						template += "<td class='text-center'>"+data.province+"</td>";
// 						template += "<td class='text-center'>"+data.city+"</td>";
// 						template += "<td class='text-center'>"+data.address+"</td>";
// 						template += "<td class='text-center'>"+data.citizenship+"</td>";
// 						template += "<td class='text-center'>"+data.mother_name+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_name+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_birth_place+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_birth_date+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_address_1+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_address_2+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_job+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_relation+"</td>";
// 						template += "<td class='text-center'>"+data.status+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_address_2+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_address_2+"</td>";
// 						template += "<td class='text-center'>"+data.sibling_address_2+"</td>";
// 						template += "<td class='text-center'>"+moment(data.date_sbk).format('DD-MM-YYYY')+"</td>";
//                         template += "<td class='text-center'>"+moment(data.deadline).format('DD-MM-YYYY')+"</td>";
//                         if(data.date_repayment!=null){ var DateRepayment = moment(data.date_repayment).format('DD-MM-YYYY');}else{ var DateRepayment = "-";}
// 						template += "<td class='text-center'>"+DateRepayment+"</td>";
// 						template += "<td>"+data.customer_name+"</td>";
// 						template += "<td class='text-center'>"+data.capital_lease+"</td>";
// 						template += "<td class='text-right'>"+convertToRupiah(data.estimation)+"</td>";
// 						template += "<td class='text-right'>"+convertToRupiah(data.admin)+"</td>";
// 						template += "<td class='text-right'>"+convertToRupiah(data.amount)+"</td>";
//                         if(data.status_transaction=="L"){ status="Lunas";}
//                         else if(data.status_transaction=="N"){ status="Aktif";}
//                         template += "<td class='text-center'>"+status+"</td>";
//                         template += "<td class='text-right'>";
//                         if(data.description_1!=null){template += "- " + data.description_1;}
//                         if(data.description_2!=null){template += "<br>- " + data.description_2;}
//                         if(data.description_3!=null){template += "<br>- " + data.description_3;}
//                         if(data.description_4!=null){template += "<br>- " + data.description_4;}
//                         template +="</td>";
// 						template += '</tr>';
// 						no++;
// 						amount += parseInt(data.amount);
// 						admin += parseInt(data.admin);
// 					});
// 					template += "<tr class='rowappend'>";
// 					template += "<td colspan='8' class='text-right'>Total</td>";
// 					template += "<td class='text-right'>"+convertToRupiah(admin)+"</td>";
// 					template += "<td class='text-right'>"+convertToRupiah(amount)+"</td>";
// 					template += "<td class='text-right'></td>";
// 					template += "<td class='text-right'></td>";
// 					template += '</tr>';
// 					$('.kt-section__content table').append(template);
// 				}
// 			},
// 			error: function (jqXHR, textStatus, errorThrown){
// 				KTApp.unblockPage();
// 			},
// 			complete:function () {
// 				KTApp.unblock('#form_bukukas .kt-portlet__body', {});
// 			}
// 		});
//     })

//     return {
//         validator:validator
//     }
// }


	function initDataTable(){		
		var general = $('#generalSearch').val();
		var limit= $('#limit').val();
		var area = $('#area').val();
		var unit= $('#unit').val();
		var cabang = $('#cabang').val();
		var usiadari = $('#usiadari').val();
		var usiasampai = $('#usiasampai').val();
		var option = {
			data: {
				type: 'remote',
				source: {
					read: {
						url: `<?php echo base_url("api/datamaster/customers"); ?>?area=${area}&unit=${unit}&cabang=${cabang}&usiadari=${usiadari}&usiasampai=${usiasampai}`,
						map: function(raw) {
							// sample data mapping
							var dataSet = raw;
							if (typeof raw.data !== 'undefined') {
								dataSet = raw.data;
							}
							return dataSet;
						},
					},
				},
				serverPaging: true,
				serverFiltering: true,
				serverSorting: true,
				saveState : {cookie: false,webstorage: false},
			},
			sortable: true,
			pagination: true,
			search: {
				input: $('#generalSearch'),
			},
			columns: [
				{
					field: 'no_cif',
					title: 'NO Cif',
					sortable: 'asc',
					width:60,
					textAlign: 'center',
				},
				{
					field: 'nik',
					title: 'KTP',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'name',
					title: 'Nama',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'gender',
					title: 'Jenis Kelamin',
					sortable: 'asc',
					textAlign: 'left',
					template:function (row) {
						var result;
						if(row.gender == 'MALE'){
							result = 'PRIA'
						}else{
							result = 'Wanita';
						}
						return result
					}
				},
				{
					field: 'marital',
					title: 'Status Kawin',
					sortable: 'asc',
					textAlign: 'left',
					template:function (row) {
						var result;
						if(row.marital == 'SINGLE'){
							result = 'Belum Kawin'
						}else if(row.marital = 'MARRIED'){
							result = 'Kawin';
						}else{
							result = 'Bercerai';
						}
						return result
					}
				},
				{
					field: 'birth_place',
					title: 'Tempat Lahir',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'birth_date',
					title: 'Tanggal Lahir',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'age_customer',
					title: 'Age',
					sortable: 'asc',
					textAlign: 'left',
					template: function(row){
						return Math.floor(row.age_customer/365);

					}
					// sortable: false,
					// width: 100,
					// overflow: 'visible',
					// textAlign: 'center',
					// autoHide: false,
					// template: function (row) {
					// 	var result ="";
					// 	result = row.birth_date;
					// 	return result;
					// }
				},
				{
					field: 'mobile',
					title: 'No Telpon',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'rt',
					title: 'RT',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'rw',
					title: 'RW',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'kelurahan',
					title: 'Kelurahan',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'kecamatan',
					title: 'Kecamatan',
					sortable: 'asc',
					textAlign: 'left',
				},{
					field: 'kodepos',
					title: 'Kode Pos',
					sortable: 'asc',
					textAlign: 'left',
				},{
					field: 'province',
					title: 'Provinsi',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'city',
					title: 'Kota',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'address',
					title: 'Alamat',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'citizenship',
					title: 'Kewarganegaraan',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'mother_name',
					title: 'Nama Ibu',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_name',
					title: 'Nama Saudara',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_birth_place',
					title: 'Tempat Lahir Saudara',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_birth_date',
					title: 'Tanggal Lahir Saudara',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_address_1',
					title: 'Alamat Saudara 1',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_address_2',
					title: 'Alamat Saudara 2',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_job',
					title: 'Pekerjaan Saudaraa',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'sibling_relation',
					title: 'Hubungan Keluarga',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'status',
					title: 'Status',
					sortable: 'asc',
					textAlign: 'left',
				},
				{
					field: 'action',
					title: 'Action',
					sortable: false,
					width: 100,
					overflow: 'visible',
					textAlign: 'center',
					autoHide: false,
					template: function (row) {
						var result ="";
						result = result + '<button data-id="' + row.id + '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></button>';
						return result;
					}
				}
			],
			layout:{
				header:true
			}
		}

		datatable = $('#kt_datatable').KTDatatable(option);
		datatable.on("kt-datatable--on-layout-updated",function(){
			// initDTEvents();
		});
	
		

		
		$('#limit').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'limit');
		});

		$('#area').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'area');
		});
		$('#unit').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'unit');
		});
		$('#cabang').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'cabang');
		});
		$('#usiadari').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'usiadari');

		});$('#usiasampai').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'usiasampai');
		});
		
	}

	$(document).ready(function () {
		initDataTable();
	});

	var type = $('[name="area"]').attr('type');
	if(type == 'hidden'){
		$('[name="area"]').trigger('change');
	}
	var type = $('[name="usiadari"]').attr('type');
	if(type == 'hidden'){
		$('[name="usiadari"]').trigger('change');
	}
	var type = $('[name="usiasampai"]').attr('type');
	if(type == 'hidden'){
		$('[name="usiasampai"]').trigger('change');
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

// $('[name="usiadari"]').on('change',function(){
//         var usiadari = $('[name="usiadari"]').val();
//         var usiasampai =  $('[name="usiasampai"]');
// 		var units =  $('[name="id_unit"]');
//         var url_data = $('#url_get_usiasampai').val() + '/' + usiadari;
//         $.get(url_data, function (data, status) {
//             var response = JSON.parse(data);
//             if (status) {
//                 $("#usiasampai").empty();
// 				var opt = document.createElement("option");
// 				opt.value = "0";
// 				opt.text = "All";
// 				units.append(opt);
//                 for (var i = 0; i < response.data.length; i++) {
//                     var opt = document.createElement("option");
//                     opt.value = response.data[i].id;
//                     opt.text = response.data[i].name;
//                     usiasampai.append(opt);
//                 }
//             }
//         });
// });


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
$('#area').select2({ placeholder: "Select Area", width: '100%' });
$('#unit').select2({ placeholder: "Select Unit", width: '100%' });
$('#usiadari').select2({ placeholder: "Select usiadari", width: '100%' });
$('#usiasampai').select2({ placeholder: "Select usiasampai", width: '100%' });
</script>
