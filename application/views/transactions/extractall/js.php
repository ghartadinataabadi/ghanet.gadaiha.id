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
		$.ajax({
			url : '<?php echo base_url('api/transactions/loaninstallments/extractall');?>',
			type : 'POST',
			data : data,
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			success : function(response) {
			},
			complete: function () {
				window.location.reload();
			}
		});
	});


	function initDataTable(){
		var option = {
			data: {
				type: 'remote',
				source: {
					read: {
						url: '<?php echo base_url("api/transactions/loaninstallments"); ?>',
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
					field: 'no_sbk',
					title: 'NO SBK',
					sortable: 'asc',
					width:60,
					textAlign: 'center',
				},
				{
					field: 'nic',
					title: 'NO Cif',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
				},
				{
					field: 'name',
					title: 'Nama Nasabah',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
				},
				{
					field: 'date_sbk',
					title: 'Tanggal SBK',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
				},
				{
					field: 'date_repayment',
					title: 'Tanggal Pelunasan',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
				},
				{
					field: 'amount_loan',
					title: 'UP',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
				},
				{
					field: 'januari',
					title: 'Januari',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[1];
					}
				},
				{
					field: 'februari',
					title: 'Februari',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[2];
					}
				},
				{
					field: 'maret',
					title: 'Maret',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[3];
					}
				},
				{
					field: 'april',
					title: 'April',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[4];
					}
				},
				{
					field: 'mei',
					title: 'Mei',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[5];
					}
				},
				{
					field: 'juni',
					title: 'Juni',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[6];
					}
				},
				{
					field: 'juli',
					title: 'Juli',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[7];
					}
				},
				{
					field: 'agustus',
					title: 'Agustus',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[8];
					}
				},
				{
					field: 'september',
					title: 'September',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[9];
					}
				},
				{
					field: 'oktober',
					title: 'Oktober',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[10];
					}
				},
				{
					field: 'november',
					title: 'November',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[11];
					}
				},
				{
					field: 'desember',
					title: 'Desember',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.angsuran[12];
					}
				},
				{
					field: 'saldo_awal',
					title: 'Saldo Awal',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.wallet_begin;
					}
				},
				{
					field: 'saldo_akhir',
					title: 'Saldo Akhir',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.wallet_end;
					}
				},
				{
					field: 'sm1',
					title: 'SM1',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[1];
					}
				},
				{
					field: 'sm2',
					title: 'SM2',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[2];
					}
				},
				{
					field: 'sm3',
					title: 'SM3',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[3];
					}
				},
				{
					field: 'sm4',
					title: 'SM4',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[4];
					}
				},
				{
					field: 'sm5',
					title: 'SM5',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[5];
					}
				},
				{
					field: 'sm6',
					title: 'SM6',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[6];
					}
				},
				{
					field: 'sm7',
					title: 'SM7',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[7];
					}
				},
				{
					field: 'sm8',
					title: 'SM8',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[8];
					}
				},
				{
					field: 'sm9',
					title: 'SM9',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[9];
					}
				},
				{
					field: 'sm10',
					title: 'SM10',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[10];
					}
				},
				{
					field: 'sm11',
					title: 'SM11',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[11];
					}
				},
				{
					field: 'sm12',
					title: 'SM12',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm[12];
					}
				},
				{
					field: 'volo_begin',
					title: 'Volo Awal',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.volo_begin;
					}
				},
				{
					field: 'volo_end',
					title: 'Volo Akhir',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.volo_end;
					}
				},
				{
					field: 'sm_begin',
					title: 'Sewa Modal Awal',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm_begin;
					}
				},
				{
					field: 'sm_ed',
					title: 'Sewa Modal Akhir',
					sortable: 'asc',
					width:60,
					textAlign: 'left',
					template:function (raw) {
						return raw.detail.sm_end;
					}
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
			//initDTEvents();
		})
	}

	$(document).ready(function () {
		initDataTable();
	});
</script
