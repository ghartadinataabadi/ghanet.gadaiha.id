<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Dailytask extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Dailytask';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsModel', 'units');
        $this->load->model('AreasModel', 'areas');
        $this->load->model('RegularPawnsModel', 'regular');
	}

	/**
	 * Welcome Index()
	 */

   public function index(){
      $this->regular->db->where('id_repayment',null)
         ->limit(400)
         ->order_by('id','asc');
      $reguler = $this->regular->all();
      if($reguler){
         foreach($reguler as $data){
            $pelunasan = $this->regular->db
                  ->from('units_repayments')
                  ->where('id_unit', $data->id_unit)
                  ->where('permit', $data->permit)
                  ->where('no_sbk', $data->no_sbk)->get()->row();
            if($pelunasan){
               $this->regular->update(['id_repayment'=>$pelunasan->id], $data->id);
            }
         }
      }
   }


	public function outstanding()
	{
        $currdate =date("Y-m-d");
        $nextdate = date('Y-m-d', strtotime('+1 days', strtotime($currdate)));
        $lastdate = date('Y-m-d', strtotime('-1 days', strtotime($currdate)));

         $date = date('Y-m-d');
         $lastdate = $this->regular->getLastDateTransaction()->date;
         if ($date > $lastdate){
            $date = $lastdate;
         }else{
            $date= $date;
         }
      
        $units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
          $unit->ost_yesterday = $this->regular->getOstYesterday($unit->id, $date);
			 $unit->credit_today = $this->regular->getCreditToday($unit->id, $date);
          $unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $date);
          $unit->total_disburse = $this->regular->getTotalDisburse($unit->id);
			 $totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa - $unit->repayment_today->noa;
			 $totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up - $unit->repayment_today->up;
			 $unit->total_outstanding = (object) array(
				'noa'	=> $totalNoa,
				'up'	=> $totalUp,
				'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			 );

             $outstanding['id_unit']   = $unit->id;
             $outstanding['date']      = $currdate;
             $outstanding['noa']       = $unit->total_outstanding->noa;
             $outstanding['os']        = $unit->total_outstanding->up;
             $outstanding['ticket']    = $unit->total_outstanding->tiket;
//             $check = $this->db->get_where('units_outstanding',array('id_unit' => $unit->id,'date'=>$currdate));
//             if($check->num_rows() > 0){
//                $this->db->update('units_outstanding', $outstanding, array('id_unit' => $unit->id,'date'=>$currdate));
//             }else{
//                $this->db->insert('units_outstanding', $outstanding);
//             }

             $credit['id_unit']   = $unit->id;
             $credit['date']      = $currdate;
             $credit['noa']       = $unit->credit_today->noa;
             $credit['credit']    = $unit->credit_today->up;
             $check = $this->db->get_where('units_pencairan',array('id_unit' => $unit->id,'date'=>$currdate));
                if($check->num_rows() > 0){
                   $this->db->update('units_pencairan', $credit, array('id_unit' => $unit->id,'date'=>$currdate));
                }else{
                   $this->db->insert('units_pencairan', $credit);
                }

            $pym['id_unit']   = $unit->id;
            $pym['date']      = $currdate;
            $pym['noa']       = $unit->repayment_today->noa;
            $pym['payment']   = $unit->repayment_today->up;
            $check = $this->db->get_where('units_pelunasan',array('id_unit' => $unit->id,'date'=>$currdate));
            if($check->num_rows() > 0){
               $this->db->update('units_pelunasan', $pym, array('id_unit' => $unit->id,'date'=>$currdate));
            }else{
               $this->db->insert('units_pelunasan', $pym);
            }

            $disburse['id_unit']   = $unit->id;
            $disburse['date']      = $currdate;
            $disburse['noa']       = $unit->total_disburse->noa;
            $disburse['disburse']        = $unit->total_disburse->credit;
            $disburse['ticket']    = $unit->total_disburse->tiket;
            $check = $this->db->get_where('units_disburse',array('id_unit' => $unit->id,'date'=>$currdate));
            if($check->num_rows() > 0){
               $this->db->update('units_disburse', $disburse, array('id_unit' => $unit->id,'date'=>$currdate));
            }else{
               $this->db->insert('units_disburse', $disburse);
            }

      }
      echo "Done";        
   } 

   public function yesterday()
	{
        $currdate =date("Y-m-d");
        $nextdate = date('Y-m-d', strtotime('+1 days', strtotime($currdate)));
        $lastdate = date('Y-m-d', strtotime('-1 days', strtotime($currdate)));

         $date = date('Y-m-d');
         $lastdate = $this->regular->getLastDateTransaction()->date;
         if ($date > $lastdate){
            $date = $lastdate;
         }else{
            $date= $date;
         }
      
        $units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
          $unit->ost_yesterday = $this->regular->getOstYesterday($unit->id, $lastdate);
			 $unit->credit_today = $this->regular->getCreditToday($unit->id, $lastdate);
          $unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $lastdate);
          $unit->total_disburse = $this->regular->getTotalDisburse($unit->id);
			 $totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa - $unit->repayment_today->noa;
			 $totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up - $unit->repayment_today->up;
			 $unit->total_outstanding = (object) array(
				'noa'	=> $totalNoa,
				'up'	=> $totalUp,
				'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			 );

             $outstanding['id_unit']   = $unit->id;
             $outstanding['date']      = $lastdate;
             $outstanding['noa']       = $unit->total_outstanding->noa;
             $outstanding['os']        = $unit->total_outstanding->up;
             $outstanding['ticket']    = $unit->total_outstanding->tiket;
             $check = $this->db->get_where('units_outstanding',array('id_unit' => $unit->id,'date'=>$lastdate));
             if($check->num_rows() > 0){
                $this->db->update('units_outstanding', $outstanding, array('id_unit' => $unit->id,'date'=>$lastdate));
             }else{
                $this->db->insert('units_outstanding', $outstanding);
             }

             $credit['id_unit']   = $unit->id;
             $credit['date']      = $lastdate;
             $credit['noa']       = $unit->credit_today->noa;
             $credit['credit']    = $unit->credit_today->up;
             $check = $this->db->get_where('units_pencairan',array('id_unit' => $unit->id,'date'=>$lastdate));
                if($check->num_rows() > 0){
                   $this->db->update('units_pencairan', $credit, array('id_unit' => $unit->id,'date'=>$lastdate));
                }else{
                   $this->db->insert('units_pencairan', $credit);
                }

            $pym['id_unit']   = $unit->id;
            $pym['date']      = $lastdate;
            $pym['noa']       = $unit->repayment_today->noa;
            $pym['payment']   = $unit->repayment_today->up;
            $check = $this->db->get_where('units_pelunasan',array('id_unit' => $unit->id,'date'=>$lastdate));
            if($check->num_rows() > 0){
               $this->db->update('units_pelunasan', $pym, array('id_unit' => $unit->id,'date'=>$lastdate));
            }else{
               $this->db->insert('units_pelunasan', $pym);
            }

            $disburse['id_unit']   = $unit->id;
            $disburse['date']      = $lastdate;
            $disburse['noa']       = $unit->total_disburse->noa;
            $disburse['disburse']        = $unit->total_disburse->credit;
            $disburse['ticket']    = $unit->total_disburse->tiket;
            $check = $this->db->get_where('units_disburse',array('id_unit' => $unit->id,'date'=>$lastdate));
            if($check->num_rows() > 0){
               $this->db->update('units_disburse', $disburse, array('id_unit' => $unit->id,'date'=>$currdate));
            }else{
               $this->db->insert('units_disburse', $disburse);
            }

      }
      echo "Done";        
   } 
     
   

}
