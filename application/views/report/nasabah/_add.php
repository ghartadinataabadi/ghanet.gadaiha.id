<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form onSubmit="submitHandler(event)" class="modal-dialog form modal-lg" role="document"  enctype="multipart/form-data" action="#" id="form_add" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Nasabah Yogadai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <div class="form-horizontal">
                    <div class="form-body">
                    <div class="alert alert-danger d-none"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>File Yogadai</label>
                                <input type="file" name="file" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn_add_submit">Submit</button>
            </div>
        </div>
    </form>
</div>
<!--end::Modal-->
