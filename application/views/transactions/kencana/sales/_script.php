<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;


$(".currency").inputmask('decimal', {
    'alias': 'numeric',
    'groupSeparator': ',',
    'autoGroup': true,
    'digits': 2,
    'radixPoint': ".",
    'digitsOptional': false,
    'allowMinus': false,
    'placeholder': ''
});

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
                    url : "<?php echo base_url("api/kencana/sales/delete"); ?>/"+targetId,
                    dataType : "json",
                    success : function(data,status){
                        KTApp.unblockPage();
                        if(data.data !== false){
                            datatable.reload();
                            AlertUtil.showSuccess(data.message,5000);
                        }else{
                            AlertUtil.showFailed(data.message);
                        }                
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        KTApp.unblockPage();
                        AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
                    }
                });  
            }
        });
    });

}


function initDataTable(){
    var option = {
        data: {
            type: 'remote',
            source: {
              read: {
                url: '<?php echo base_url("api/kencana/sales"); ?>',
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
                textAlign: 'center',
            },
            {
                field: 'reference_code',
                title: 'Kode Refence',
                textAlign: 'center',
            },
		
            {
                field: 'total_quantity',
                title: 'Jumlah',
                textAlign: 'center',
            },
            {
                field: 'total_price',
                title: 'Harga perpicis',
                textAlign: 'right',
                template: function(data){
                    return `Rp ${convertToRupiah(data.total_price)}`;
                }
            },
            {
                field: 'description',
                title: 'Deskripsi',
                textAlign: 'center',
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
                    result = result + `<a data-id="' + row.id + '" href="<?php echo base_url();?>transactions/kencana/sales/form/${row.id}" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></a>`;
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
}


jQuery(document).ready(function() { 
    initDataTable();
});

</script>
