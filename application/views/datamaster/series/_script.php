<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;

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
                    url : "<?php echo base_url("api/lm/series/delete"); ?>",
                    data : {id:targetId},
                    dataType : "json",
                    success : function(data,status){
                        KTApp.unblockPage();
                        if(data.data !== false){
                            datatable.reload();
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

    $(".btn_edit").on("click",function(){
        var targetId = $(this).data("id");
        clearForm();
        KTApp.blockPage();
        $.ajax({
            type : 'GET',
            url : "<?php echo base_url("api/lm/series/show"); ?>/"+targetId,
            dataType : "json",
            success : function(response,status){
                KTApp.unblockPage();
                $('#modal_add').find('[name="id"]').val(response.data.id);
                $('#modal_add').find('[name="series"]').val(response.data.series);              
                $('#modal_add').modal('show');
                            
            },
            error: function (jqXHR, textStatus, errorThrown){
                KTApp.unblockPage();
                AlertUtil.showFailed("Cannot communicate with server please check your internet connection");
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
                url: '<?php echo base_url("api/lm/series"); ?>',
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
                field: 'id',
                title: 'ID',
                sortable: 'DESC',
                width:20,
                textAlign: 'center',
            },
            {
                field: 'series',
                title: 'Series',
                width:120,
                textAlign: 'center',
            },
              {
				  field: 'status',
				  title: 'status',
				  width:60,
				  textAlign: 'center',
			  },
			  {
                field: 'action',
                title: 'Action',
                width: 100,
                overflow: 'visible',
                textAlign: 'center',
                autoHide: false,
                template: function (row) {
                    var result ="";
                        result = result + '<span data-id="' + row.id + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_edit" title="Edit" ><i class="flaticon-edit-1" style="cursor:pointer;"></i></span>';
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


const showModal = ()=>{
    clearForm();
    $('#modal_add').modal('show');
}



var clearForm = function(){
    $('[name="id"]').val('');
    $('[name="series"]').val('');
}
    
function submitHandler(event){
    event.preventDefault();
    var id = event.target.querySelector('[name="id"]').value;
    if(id){
        var url = "<?php echo base_url("api/lm/series/update"); ?>";
    }else{
        var url = "<?php echo base_url("api/lm/series/insert"); ?>";
    }
    KTApp.block('#modal_add .modal-content', {});
    $.ajax({
        type : 'POST',
        url : url,
        data : new FormData(event.target),
        cache: false,
        contentType: false,
        processData: false,
        dataType : "json",
        success : function(data,status){
            KTApp.unblock('#modal_add .modal-content');
            if(data.data !== false){
                datatable.reload();
                $('#modal_add').modal('hide');
                clearForm();
            }else{
                event.target.querySelector('.alert').innerHTML = data.message;
                event.target.querySelector('.alert').classList.remove('d-none');

                setInterval(() => {
                    event.target.querySelector('.alert').innerHTML = '';
                    event.target.querySelector('.alert').classList.add('d-none');
                }, 5000);
            }
            $('#modal_add').on('hidden.bs.modal', function () {
            })
        }
    });  
}

jQuery(document).ready(function() { 
    initDataTable();
});

</script>
