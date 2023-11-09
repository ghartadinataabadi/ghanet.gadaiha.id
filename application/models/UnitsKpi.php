<?php 
require_once 'Master.php';
class UnitsKpi extends Master
{
    public $table = 'units_kpi';
    public $primary_key = 'id';

	public function bobotBooking($angka){
		$nilaibooking = 0;
		if($angka <= 65){
			$nilaibooking = 2;
			return $nilaibooking;
		}else if($angka <= 75 && $angka > 65){
			$nilaibooking = 4;
			return $nilaibooking;
		}else if($angka <= 85 && $angka > 75){
			$nilaibooking = 6;
			return $nilaibooking;
		}else if($angka <= 95 && $angka > 85){
			$nilaibooking = 8;
			return $nilaibooking;
		}else if($angka > 95){
			$nilaibooking = 10;
			return $nilaibooking;
		}
	}

	public function bobotDpd($angka){
		$nilaiDpd = 0;
		if($angka > 4.5){
			$nilaiDpd = 2;
			return $nilaiDpd;
		}else if($angka <= 4.5 && $angka > 4){
			$nilaiDpd = 4;
			return $nilaiDpd;
		}else if($angka <= 4 && $angka > 3.5){
			$nilaiDpd = 6;
			return $nilaiDpd;
		}else if($angka <= 3.5 && $angka >3){
			$nilaiDpd = 8;
			return $nilaiDpd;
		}else if($angka < 3){
			$nilaiDpd = 10;
			return $nilaiDpd;
		}
	}
	
	public function bobotRate($angka){
		$nilaiRate = 0;
		if($angka <= 1.6){
			$nilaiRate = 2;
			return $nilaiRate;
		}else if($angka <= 1.7 && $angka > 1.6){
			$nilaiRate = 4;
			return $nilaiRate;
		}else if($angka <= 1.8 && $angka > 1.7){
			$nilaiRate = 6;
			return $nilaiRate;
		}else if($angka <= 1.9 && $angka > 1.8){
			$nilaiRate = 8;
			return $nilaiRate;
		}else if($angka > 1.9){
			$nilaiRate = 10;
			return $nilaiRate;
		}
	}
	
	public function getProfit()
	{
		$this->db->select('a.id,b.id as id_unit,b.name,b.id_area,c.area,a.month,a.year,a.profit_unit');		
		$this->db->join('units as b','b.id=a.id_unit');		
		$this->db->join('areas as c','c.id=b.id_area');		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('units_kpi as a')->result();
	}

}

?>