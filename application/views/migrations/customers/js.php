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
				//settimeout(function() {
					///alert(data.marital);
					$('[name="marital"]').val(data.marital);
					$('[name="marital"]').trigger('change');

				//},500)			
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


	function initDataTable(){		
		var general = $('#generalSearch').val();
		var limit= $('#limit').val();
		var area = $('#area').val();
		var unit= $('#unit').val();
		var cabang = $('#cabang').val();
		var option = {
			data: {
				type: 'remote',
				source: {
					read: {
						url: `<?php echo base_url("api/datamaster/customers/getmigrations"); ?>?area=${area}&unit=${unit}&cabang=${cabang}`,
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
					field: 'unit',
					title: 'Unit',
					sortable: 'asc',
					width:60,
					textAlign: 'center',
				},
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
					field: 'mobile',
					title: 'No Telpon',
					sortable: 'asc',
					textAlign: 'left',
				},
				// {
				// 	field: 'rt',
				// 	title: 'RT',
				// 	sortable: 'asc',
				// 	textAlign: 'left',
				// },
				// {
				// 	field: 'rw',
				// 	title: 'RW',
				// 	sortable: 'asc',
				// 	textAlign: 'left',
				// },
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
		
	}

	$(document).ready(function () {
		initDataTable();
	});

	var type = $('[name="area"]').attr('type');
	if(type == 'hidden'){
		$('[name="area"]').trigger('change');
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
</script>
