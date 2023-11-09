<script>
//globals
var datatable;
var AlertUtil;
var createForm;
var editForm;


function initDTEvents() {
    $(".btn_delete").on("click", function() {
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
                    type: 'GET',
                    url: "<?php echo base_url("api/datamaster/bookcash/delete/"); ?>" +
                        targetId,
                    dataType: "json",
                    success: function(data, status) {
                        KTApp.unblockPage();
                        if (data.status == true) {
                            datatable.reload();
                            AlertUtil.showSuccess(data.message, 5000);
                        } else {
                            AlertUtil.showFailed(data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        KTApp.unblockPage();
                        AlertUtil.showFailed(
                            "Cannot communicate with server please check your internet connection"
                        );
                    }
                });
            }
        });
    });

    $(".btn_edit").on("click", function() {
        var targetId = $(this).data("id");
        KTApp.blockPage();
        $.ajax({
            type: 'GET',
            url: "<?php echo base_url("api/datamaster/bookcash/show/"); ?>" + targetId,
            dataType: "json",
            success: function(response, status) {
                // $('.append').find('.form-group').remove();
                // $('.modal-title').text(response.data.unit_name);
                // var total = 0;
                // $.each(response.data.detail, function(index, data) {
                //     total += data.summary * data.amount;
                //     $('.append').append('<div class="form-group"><label>' + data.read +
                //         '</label><span class="form-control">' + data.summary +
                //         '</span></div>');
                // });
                // $('.append').append(
                //     '<div class="form-group"><label>Total</label><span class="form-control">' +
                //     total + '</span></div>');
                KTApp.unblockPage();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                KTApp.unblockPage();
                AlertUtil.showFailed(
                    "Cannot communicate with server please check your internet connection");
            },
            complete: function() {
                $('#modal_add').modal('show');
            }
        });
    });
}

function initDataTable() {
    var option = {
        data: {
            type: 'remote',
            source: {
                read: {
                    url: '<?php echo base_url("api/datamaster/bobot"); ?>',
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
            saveState: {
                cookie: false,
                webstorage: false
            },
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
                sortable: 'desc',
                width: 60,
                textAlign: 'center',
            },
            {
                field: 'type',
                title: 'Type Bobot',
                textAlign: 'left',
            },
            {
                field: 'percentase',
                title: 'Percentase',
                textAlign: 'left',
                template: function(row) {
                    result = row.percentase + " %";
                    return result;
                }
            },
            
            {
                field: 'action',
                title: 'Action',
                sortable: false,
                width: 150,
                overflow: 'visible',
                textAlign: 'center',
                autoHide: false,
                template: function(row) {
                    var result = "";
                    
                        // result = result + "<span data-id='" + row.id + "' href='' class='btn btn-sm btn-clean btn-icon btn-icon-md EditBtn' title='Edit Data' data-toggle='modal' data-target='#modal_edit'><i class='flaticon-edit-1' style='cursor:pointer;'></i></span>";
                        // 

                        result = result + "<span data-id='" + row.id + "' href='' class='btn btn-sm btn-clean btn-icon btn-icon-md viewBtn' title='View Data' data-toggle='modal' data-target='#modal_view'><i class='flaticon-edit-1' style='cursor:pointer;'></i></span>";
                        result = result + '<span data-id="' + row.id + '" href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn_delete" title="Delete" ><i class="flaticon2-trash" style="cursor:pointer;"></i></span>';
                        //result = result + "<span data-id='"+ row.id +"' href='' class='btn btn-sm btn-clean btn-icon btn-icon-md pdfBtn' title='View Data' data-toggle='modal' data-target='#modal_pdf'><i class='flaticon2-print' style='cursor:pointer;'></i></span>";
                    // result = result + '<a href="<?php //echo base_url('transactions/bookcash/preview'); ?>/' +
                    //     row.id +
                    //     '" target="_blank" class="btn btn-sm btn-clean btn-icon btn-icon-md pdfBtn" title="Preview BAP Kas"><i class="flaticon2-print" style="cursor:pointer;"></i></a>';

                    // result = result + "<span data-id='"+ row.id +"' href='' class='btn btn-sm btn-clean btn-icon btn-icon-md EditBtn' title='Edit Data' data-toggle='modal' data-target='#modal_edit'><i class='flaticon-edit-1' style='cursor:pointer;'></i></span>";

                    return result;
                }
            }
        ],
        layout: {
            header: true
        }
    }
    datatable = $('#kt_datatable').KTDatatable(option);
    datatable.on("kt-datatable--on-layout-updated", function() {
        initDTEvents();
    })
}

function initAlert() {
    AlertUtil = {
        showSuccess: function(message, timeout) {
            $("#success_message").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#success_alert_dismiss").trigger("click");
                }, timeout)
            }
            $("#success_alert").show();
            KTUtil.scrollTop();
        },
        hideSuccess: function() {
            $("#success_alert_dismiss").trigger("click");
        },
        showFailed: function(message, timeout) {
            $("#failed_message").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#failed_alert_dismiss").trigger("click");
                }, timeout)
            }
            $("#failed_alert").show();
            KTUtil.scrollTop();
        },
        hideFailed: function() {
            $("#failed_alert_dismiss").trigger("click");
        },
        showFailedDialogAdd: function(message, timeout) {
            $("#failed_message_add").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#failed_alert_dismiss_add").trigger("click");
                }, timeout)
            }
            $("#failed_alert_add").show();
        },
        hideSuccessDialogAdd: function() {
            $("#failed_alert_dismiss_add").trigger("click");
        },
        showFailedDialogEdit: function(message, timeout) {
            $("#failed_message_edit").html(message);
            if (timeout != undefined) {
                setTimeout(function() {
                    $("#failed_alert_dismiss_edit").trigger("click");
                }, timeout)
            }
            $("#failed_alert_edit").show();
        },
        hideSuccessDialogAdd: function() {
            $("#failed_alert_dismiss_edit").trigger("click");
        }
    }
    $("#failed_alert_dismiss").on("click", function() {
        $("#failed_alert").hide();
    })
    $("#success_alert_dismiss").on("click", function() {
        $("#success_alert").hide();
    })
    $("#failed_alert_dismiss_add").on("click", function() {
        $("#failed_alert_add").hide();
    })
    $("#failed_alert_dismiss_edit").on("click", function() {
        $("#failed_alert_edit").hide();
    })
}

function initCariForm() {

    // var validator = $("#form_add").validate({
    //     ignore:[],
    //     rules: {
    //         kasir: {
    //             kasir: true,
    //         },
    //         date: {
    //             date: true,
    //         },
    //         saldoawal: {
    //             saldoawal: true,
    //         },
    //         penerimaan: {
    //             penerimaan: true,
    //         },
    //         pengeluaran: {
    //             pengeluaran: true,
    //         }, 
    //         saldoakhir: {
    //             saldoakhir: true,
    //         }
    //     },
    //     invalidHandler: function(event, validator) {
    //         KTUtil.scrollTop();
    //     }
    // });

    // $('#id_unit').select2({
    //     placeholder: "Please select a Unit",
    //     width: '100%'
    // }); 

    //events
    $('#btn_add_submit').on('click', function() {
        var isValid = $("#form_add").valid();
        var os = $('[name="os"]').val();
        var noa = $('[name="noa"]').val();
        var saldo = $('[name="saldoakhir"]').val();
        if (isValid) {
            KTApp.block('#modal_add .modal-content', {});
            swal.fire({
                title: 'Anda Yakin?',
                text: "Total Os = " + os + ", Noa = " + noa + ", Saldo Akhir = " + saldo,
                type: 'warning',
                showBackButton: true,
                confirmButtonText: 'Ya, Benar'
            }).then(function(result) {
                if (result.value) {
                    // KTApp.blockPage();
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo base_url("api/datamaster/bookcash/insert"); ?>",
                        data: $('#form_add').serialize(),
                        dataType: "json",
                        success: function(data, status) {
                            KTApp.unblock('#modal_add .modal-content');
                            if (data.status == true) {
                                datatable.reload();
                                $('#modal_add').modal('hide');
                                AlertUtil.showSuccess(data.message, 5000);
                            } else {
                                AlertUtil.showFailedDialogAdd(data.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            KTApp.unblock('#modal_add .modal-content');
                            AlertUtil.showFailedDialogAdd(
                                "server busy please try again later");
                        }
                    });
                }
            });
        }
    })

    // $('#modal_add').on('hidden.bs.modal', function () {
    //        validator.resetForm();
    //     })
    // return {
    //         validator:validator
    //     }
}



function initEditForm() {

    // $('#e_id_unit').select2({
    //     placeholder: "Please select a Unit",
    //     width: '100%'
    // });

    //events
    $('#btn_edit_submit').on('click', function() {
        var isValid = $("#form_edit").valid();
        if (isValid) {
            KTApp.block('#modal_view .modal-content', {});
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url("api/datamaster/bobot/update"); ?>",
                data: $('#form_edit').serialize(),
                dataType: "json",
                success: function(data, status) {
                    KTApp.unblock('#modal_view .modal-content');
                    if (data.status == true) {
                        datatable.reload();
                        $('#modal_view').modal('hide');
                        AlertUtil.showSuccess(data.message, 5000);
                    } else {
                        AlertUtil.showFailedDialogEdit(data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    KTApp.unblock('#modal_view .modal-content');
                    AlertUtil.showFailedDialogEdit(
                        "Cannot communicate with server please check your internet connection");
                }
                //alert('test');
            });
        }
    })

}

$('#btn_edit_submit').on('click', function() {
        var isValid = $("#form_edit").valid();
        if (isValid) {
            KTApp.block('#modal_view .modal-content', {});
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url("api/datamaster/bobot/update"); ?>",
                data: $('#form_edit').serialize(),
                dataType: "json",
                success: function(data, status) {
                    KTApp.unblock('#modal_view .modal-content');
                    if (data.status == true) {
                        datatable.reload();
                        $('#modal_view').modal('hide');
                        AlertUtil.showSuccess(data.message, 5000);
                    } else {
                        AlertUtil.showFailedDialogEdit(data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    KTApp.unblock('#modal_view .modal-content');
                    AlertUtil.showFailedDialogEdit(
                        "Cannot communicate with server please check your internet connection");
                }
                //alert('test');
            });
        }
    })

function clear() {
    $('#saldoawal').val("");
    $('#penerimaan').val("");
    $('#pengeluaran').val("");

    $('#totmutasi').val("");
    $('#mutasi').val("");

    $('#total_penerimaan').val("");
    $('#totpenerimaan').val("");
    $('#total_pengeluaran').val("");
    $('#totpengeluaran').val("");

    $('#saldoakhir').val("");
    $('#total').val("");
    $('#selisih').val("");
    $('#kasir').val("");
    $('#note').val("");
    $('#noa_regular').val("");
    $('#noa_cicilan').val("");
    $('#os_unit').val("");

    $('#booking').val("");
    $('#noa_booking').val("");
    $('#repayment').val("");
    $('#noa_repayment').val("");
    $('#dpd').val("");
    $('#noa_dpd').val("");
    $('#pengeluarannon').val("");
    $('#penerimaanmoker').val("");

}

function popAdd(el) {
    $('.rowappend_kertas').remove();
    $('.rowappend_logam').remove();
    clear();
    //ConverPecahanKertas();
    //KTApp.block('#modal_add .kt-portlet__body', {});
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/datamaster/Bookcash/get_type_money_kertas"); ?>",
        dataType: "json",
        //data:{nosbk:nosbk,unit:unit},
        success: function(response, status) {
            KTApp.unblockPage();
            if (response.status == true) {
                var template = '';
                var no = 1;
                //var saldo = up;
                //var cicilan = 0;
                $.each(response.data, function(index, data) {
                    template += "<tr class='rowappend_kertas'>";
                    template +=
                        "<td><input type='text' class='form-control form-control-sm pecahan' id='k_pecahan_" +
                        no + "' name='k_pecahan[]' value=" + data.amount +
                        " style='background-color:grey; color:white;' readonly><input type='hidden' class='form-control form-control-sm pecahan' id='k_fraction_" +
                        no + "' name='k_fraction[]' value=" + data.id + " readonly></td>";
                    template +=
                        "<td><input type='text' class='form-control form-control-sm jumlah' id='k_jumlah_" +
                        no + "' name='k_jumlah[]'></td>";
                    template +=
                        "<td><input type='text' class='form-control form-control-sm total' id='k_total_" +
                        no +
                        "' name='k_total[]' style='background-color:grey; color:white;' readonly></td>";
                    template += '</tr>';
                    no++;
                });
                $('#kertas').append(template);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            KTApp.unblockPage();
        },
        complete: function() {
            //KTApp.unblock('#modal_add .kt-portlet__body', {});
        }
    });

    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/datamaster/Bookcash/get_type_money_logam"); ?>",
        dataType: "json",
        //data:{nosbk:nosbk,unit:unit},
        success: function(response, status) {
            KTApp.unblockPage();
            if (response.status == true) {
                var template = '';
                var no = 1;
                //var saldo = up;
                //var cicilan = 0;
                $.each(response.data, function(index, data) {
                    template += "<tr class='rowappend_logam'>";
                    template +=
                        "<td><input type='text' class='form-control form-control-sm pecahan' id='l_pecahan_" +
                        no + "' name='l_pecahan[]' value=" + data.amount +
                        " style='background-color:grey; color:white;' readonly><input type='hidden' class='form-control form-control-sm pecahan' id='l_fraction_" +
                        no + "' name='l_fraction[]' value=" + data.id + " readonly></td>";
                    template +=
                        "<td><input type='text' class='form-control form-control-sm jumlah' id='l_jumlah_" +
                        no + "' name='l_jumlah[]'></td>";
                    template +=
                        "<td><input type='text' class='form-control form-control-sm total' id='l_total_" +
                        no +
                        "' name='l_total[]' style='background-color:grey; color:#F8F9F9;' readonly></td>";
                    template += '</tr>';
                    no++;
                });
                $('#logam').append(template);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            KTApp.unblockPage();
        },
        complete: function() {
            //KTApp.unblock('#modal_add .kt-portlet__body', {});
        }
    });
}

function popEdit(el) {
   
    var id = $(el).attr('data-id');

    // $('#e_id_unit').select2({
    //     placeholder: "Please select a Unit",
    //     width: '100%'
    // });

    // console.log(id);  
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/datamaster/bobot/getBookCash"); ?>",
        dataType: "json",
        data: {
            id: id
        },
        success: function(response, status) {
            if (response.status == true) {
                console.log(response.data.percentase);
               
                $('#id_edit').val(response.data.id);
                $('#e_type').val(response.data.type);
                $('#e_percentase').val(response.data.percentase);

                $('#e_percent_1').val(response.data.percent_1);
                $('#e_percent_2').val(response.data.percent_2);
                $('#e_percent_3').val(response.data.percent_3);
                $('#e_percent_4').val(response.data.percent_4);
                $('#e_percent_5').val(response.data.percent_5);
                // $('#e_percent_6').val(response.data.percent_6);

                $('#e_bobot_1').val(response.data.bobot_1);
                $('#e_bobot_2').val(response.data.bobot_2);
                $('#e_bobot_3').val(response.data.bobot_3);
                $('#e_bobot_4').val(response.data.bobot_4);
                $('#e_bobot_5').val(response.data.bobot_5);
                // $('#e_bobot_6').val(response.data.bobot_6);
            }
        },
         error: function(jqXHR, textStatus, errorThrown) {
            //KTApp.unblockPage();
        },
        complete: function() {
            // convertView();
            //KTApp.unblock('#form_bukukas .kt-portlet__body', {});
        }
    });

    
}

function popView(el) {
    // $('.rowappend_kertas').remove();
    // $('.rowappend_logam').remove();
    var id = $(el).attr('data-id');
    //console.log(id);  
    $.ajax({
        type: 'GET',
        url: "<?php echo base_url("api/datamaster/bobot/getBookCash"); ?>",
        dataType: "json",
        data: {
            id: id
        },
        success: function(response, status) {
            if (response.status == true) {
                console.log(response.data.type);

                if(response.data.type == 'area' || response.data.type == 'cabang' || response.data.type == 'unit'){

                    var v_percent_1 = document.getElementById('v_percent_1');
                    v_percent_1.style.display = "none";
                    var v_percent_2 = document.getElementById('v_percent_2');
                    v_percent_2.style.display = "none";
                    var v_percent_3 = document.getElementById('v_percent_3');
                    v_percent_3.style.display = "none";
                    var v_percent_4 = document.getElementById('v_percent_4');
                    v_percent_4.style.display = "none";
                    var v_percent_5 = document.getElementById('v_percent_5');
                    v_percent_5.style.display = "none";

                    var v_bobot_1 = document.getElementById('v_bobot_1');
                    v_bobot_1.style.display = "none";
                    var v_bobot_2 = document.getElementById('v_bobot_2');
                    v_bobot_2.style.display = "none";
                    var v_bobot_3 = document.getElementById('v_bobot_3');
                    v_bobot_3.style.display = "none";
                    var v_bobot_4 = document.getElementById('v_bobot_4');
                    v_bobot_4.style.display = "none";
                    var v_bobot_5 = document.getElementById('v_bobot_5');
                    v_bobot_5.style.display = "none";

                    
                    var percentaseAll = document.getElementById('percentaseAll');
                    percentaseAll.style.display = "none";
                    var bobotAll = document.getElementById('bobotAll');
                    bobotAll.style.display = "none";

                }else{

                    var v_percent_1 = document.getElementById('v_percent_1');
                    v_percent_1.style.display = "inline";
                    var v_percent_2 = document.getElementById('v_percent_2');
                    v_percent_2.style.display = "inline";
                    var v_percent_3 = document.getElementById('v_percent_3');
                    v_percent_3.style.display = "inline";
                    var v_percent_4 = document.getElementById('v_percent_4');
                    v_percent_4.style.display = "inline";
                    var v_percent_5 = document.getElementById('v_percent_5');
                    v_percent_5.style.display = "inline";

                    var v_bobot_1 = document.getElementById('v_bobot_1');
                    v_bobot_1.style.display = "inline";
                    var v_bobot_2 = document.getElementById('v_bobot_2');
                    v_bobot_2.style.display = "inline";
                    var v_bobot_3 = document.getElementById('v_bobot_3');
                    v_bobot_3.style.display = "inline";
                    var v_bobot_4 = document.getElementById('v_bobot_4');
                    v_bobot_4.style.display = "inline";
                    var v_bobot_5 = document.getElementById('v_bobot_5');
                    v_bobot_5.style.display = "inline";

                    
                    var percentaseAll = document.getElementById('percentaseAll');
                    percentaseAll.style.display = "inline";
                    var bobotAll = document.getElementById('bobotAll');
                    bobotAll.style.display = "inline";
                }

                $('#id_edit').val(response.data.id);
                $('#v_type').val(response.data.type);
                $('#v_percentase').val(response.data.percentase);

                $('#v_percent_1').val(response.data.percent_1);
                $('#v_percent_2').val(response.data.percent_2);
                $('#v_percent_3').val(response.data.percent_3);
                $('#v_percent_4').val(response.data.percent_4);
                $('#v_percent_5').val(response.data.percent_5);
                // $('#v_percent_6').val(response.data.percent_6);

                $('#v_bobot_1').val(response.data.bobot_1);
                $('#v_bobot_2').val(response.data.bobot_2);
                $('#v_bobot_3').val(response.data.bobot_3);
                $('#v_bobot_4').val(response.data.bobot_4);
                $('#v_bobot_5').val(response.data.bobot_5);
                // $('#v_bobot_6').val(response.data.bobot_6);

                

                
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            //KTApp.unblockPage();
        },
        complete: function() {
            // convertView();
            //KTApp.unblock('#form_bukukas .kt-portlet__body', {});
        }
    });

    

}


jQuery(document).ready(function() {
    initDataTable();
    initAlert();
    initCariForm();
    initEditForm();

    $(document).on("click", ".add", function() {
        var el = $(this);
        popAdd(el);
    });

    $(document).on("click", ".EditBtn", function() {
        var el = $(this);
        popEdit(el);
    });

    $(document).on("click", ".viewBtn", function() {
        var el = $(this);
        popView(el);
    });
});

</script>