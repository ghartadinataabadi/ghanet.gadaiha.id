<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;


function initDataTable(){
    var option = {
        data: {
            type: 'remote',
            source: {
              read: {
                url: '<?php echo base_url("api/lm/stocks/images"); ?>',
                map: function(raw) {
                  // sample data mapping
                  
                  console.log(raw);
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
                field: 'id',
                title: 'ID',
                sortable: 'DESC',
                width:20,
                textAlign: 'center',
            },
            {
                field: 'unit',
                title: 'Unit',
                width:120,
                textAlign: 'center',
            },
            {
                field: 'date',
                title: 'date',
            },     
            {
              title:'Summary',
              field:'grams',
              template:function(raw){
                if(raw.grams){
                  let template = '';
                  raw.grams.forEach(g=>{
                    template += `<h6>${g.weight} grams total ${g.total} pcs</h6><br/>`;
                  });
                  return template;
                }

                return '';
              }
            }, 
            {
                field: 'images',
                title: 'Images',
                template:function(row){
                    return `<img class="img-fluid" src="<?php echo base_url('storage/stocks');?>/${row.filename}"/>`;
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
                           result = result + '<span data-id="' + row.id + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_delete" title="Delete" ><i class="flaticon2-trash" style="cursor:pointer;"></i></span>';
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
      initDTEvents();
    })

    $('#limit').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'limit');
		});

		$('#area').on('change', function() {
      datatable.search('0', 'unit');
			datatable.search($(this).val().toLowerCase(), 'area');
		});
		$('#unit').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'unit');
		});
		$('#cabang').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'cabang');
		});
    $('#date').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'date');
		});
}


jQuery(document).ready(function() { 
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

function initDTEvents(){
    $(".btn_delete").on("click",function(){
        var targetId = $(this).data("id");
        //alert(targetId);
        swal.fire({
            title: 'Anda Yakin?',
            text: "Akan menghapus data ini",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus'
        }).then(function(result) {
            if (result.value) {
                KTApp.blockPage();
                $.ajax({
                    type : 'GET',
                    url : "<?php echo base_url("api/lm/stocks/images_delete/"); ?>/"+targetId,
                    dataType : "json",
                    success : function(data,status){
                      
                      datatable.reload();
                      console.log('success');
                        KTApp.unblockPage();
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        KTApp.unblockPage();
                        datatable.reload();
                     }
                });  
            }
        });
    });
}
</script>
