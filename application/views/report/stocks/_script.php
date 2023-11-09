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
                    url : "<?php echo base_url("api/lm/stocks/delete"); ?>",
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
            url : "<?php echo base_url("api/lm/stocks/show"); ?>/"+targetId,
            dataType : "json",
            success : function(response,status){
                KTApp.unblockPage();
                $('#modal_add').find('[name="id"]').val(response.data.id);
                $('#modal_add').find('[name="id_lm_gram"]').val(response.data.id_lm_gram);
                $('#modal_add').find('[name="amount"]').val(response.data.amount);
                $('#modal_add').find('[name="date_receive"]').val(response.data.date_receive);
                $('#modal_add').find('[name="status"]').val(response.data.status);
                $('#modal_add').find('[name="description"]').val(response.data.description);
                $('#modal_add').find('[name="reference_id"]').val(response.data.reference_id);
                const types =    document.querySelector('#modal_add').querySelectorAll('[name="type"]');
                types.forEach(el=>{
                    if(response.data.type == el.value){
                        el.setAttribute('checked', true);
                    }else{
                        el.removeAttribute('checked');
                    }
                })
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
                url: '<?php echo base_url("api/lm/stocks"); ?>',
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
                sortable: 'asc',
                width:60,
                textAlign: 'center',
            },
            {
				  field: 'unit',
				  title: 'Unit',
				  width:60,
				  textAlign: 'center',
			  },
              {
				  field: 'amount',
				  title: 'Amount',
				  width:60,
				  textAlign: 'center',
			  },
			  {
				  field: 'image',
				  title: 'image',
				  width:60,
				  textAlign: 'center',
                  template:(e)=>{
                      return  e.image ? `<img src="<?php echo base_url();?>storage/lm/${e.image}" class="img-fluid"/>` : '';
                  }
			  },
			  {
				  field: 'weight',
				  title: 'weight',
				  width:60,
				  textAlign: 'center',
			  },
			  {
				  field: 'date_receive',
				  title: 'Tanggal',
				  width:60,
				  textAlign: 'center',
			  },
              {
				  field: 'type',
				  title: 'type',
				  width:60,
				  textAlign: 'center',
                  template: function(row){
                      return row.type === 'CREDIT' ? 'Barang Keluar' : 'Barang Masuk';
                  }
			  },
              {
				  field: 'description',
				  title: 'description',
				  width:60,
				  textAlign: 'center',
			  },
              {
				  field: 'price',
				  title: 'Price',
				  width:60,
				  textAlign: 'right',
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
                sortable: false,
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
    $('[name="id_lm_gram"]').val('');
    $('[name="description"]').val('');
    $('[name="amount"]').val('');
    $('[name="reference_id"]').val('');
    $('[name="date_receive"]').val('');
}
    
function submitHandler(event){
    event.preventDefault();
    var id = event.target.querySelector('[name="id"]').value;
    if(id){
        var url = "<?php echo base_url("api/lm/stocks/update"); ?>";
    }else{
        var url = "<?php echo base_url("api/lm/stocks/insert"); ?>";
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
                validator.resetForm();
            })
        }
    });  
}

function initItem(){
    $.ajax({
        url:"<?php echo base_url();?>/api/lm/grams",
        type:"GET",
        dataType:"JSON",
        success : function(res,status){
           const form = document.querySelector('#modal_add').querySelector('[name="id_lm_gram"]');
            res.data.forEach(data=>{
                const opt = document.createElement('option');
                opt.textContent =   `${data.weight} gram`;
                opt.value = data.id;
                form.appendChild(opt);
            });
        }
    })
}

jQuery(document).ready(function() { 
    initDataTable();
    initItem();
});

</script>
