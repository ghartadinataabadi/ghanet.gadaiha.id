<?php 
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
?>

<div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">								
<!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">            
            <h3 class="kt-subheader__title">Data Master</h3>            
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>            
            <span class="kt-subheader__desc">Pagu</span>            
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">                              
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

 <!-- begin:: Content -->
 <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-align-justify"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                       Pagu Unit
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">                              
                    </div>      
                </div>
            </div>

        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="col-md-pull-12" >  
                <!--begin: Alerts -->   
                <div class="kt-section">
                    <div class="kt-section__content">
                        <div class="alert alert-success fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20" role="alert" id="success_alert" style="display: none">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text" id="success_message"></div>
                            <div class="alert-close">
                                <button type="button" class="close" aria-label="Close" id="success_alert_dismiss">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>  
                         <div class="alert alert-danger fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20" role="alert" id="failed_alert" style="display: none">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text" id="failed_message"></div>
                            <div class="alert-close">
                                <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>                   
                    </div>                   
                </div>        
                <!--end: Alerts -->           
            </div>

            <!--begin: Datatable -->       
            <form action="#" id="form_add" class="form-horizontal"> 
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="kt_widget11_tab1_content">
                        <!--begin::Widget 11-->
                        <div class="kt-widget11">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Area</th>
                                            <th class="text-left">Cabang</th>
                                            <th class="text-left">Unit</th>
                                            <th class="text-left">Modal Kerja</th>
                                            <th class="text-left">Patty Cash</th>
                                            <th class="text-left">#Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>  
                                    <?php $no=0; foreach ($pagu as $row):?>
                                        <tr>
                                            <td class="text-left">
                                            <input type="hidden" name="id[]" value="<?php echo $row->id;?>"/>
                                            <input type="hidden" name="id_unit[]" value="<?php echo $row->id_unit;?>"/>
                                            <?php echo $row->area; ?>
                                            </td>
                                            <td class="text-left"><?php echo $row->cabang; ?></td>
                                            <td class="text-left"><?php echo $row->name; ?></td>
                                            <td class="text-left"><input type="text" class="form-control modalkerja" name="modalkerja[]" value="<?php echo $row->working_capital;?>"/></td>
                                            <td class="text-left"><input type="text" class="form-control" name="pattycash[]" value="<?php echo $row->patty_cash;?>" /></td>
                                            <td class="text-left"><button type="button" class="btn btn-primary btn-sm btnpublish">Publish</button></td>
                                        </tr>
                                        <?php $no++; endforeach;?>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Widget 11-->
                    </div>
                </div>
            </div>
            </form>
            <!--end: Datatable -->

        </div>
        </div>
    </div>
    <!-- end:: Content -->
	
</div>
</div>

<?php 
$this->load->view('temp/Footer.php');
$this->load->view('datamaster/pagu/_script.php');
?>