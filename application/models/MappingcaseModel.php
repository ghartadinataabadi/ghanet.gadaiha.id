<?php
require_once 'Master.php';
class MappingcaseModel extends Master
{
	public $table = 'mapping_case';
	public $primary_key = 'id';

	public function get_list_pendapatan(){
		$ignore = array('1120000', '1110000','1140201'); 
		$this->db->select('no_perk,na_perk');
		$this->db->where('type','CASH_IN');
		$this->db->where_not_in('no_perk',$ignore);
		return $this->db->get('mapping_case as a')->result();
	}

	public function get_list_pendapatan_all(){
		$ignore = array('1120000', '1110000','1140201', '4120104'); 
		$this->db->select('no_perk,na_perk');
		$this->db->where('type','CASH_IN');
		$this->db->where_not_in('no_perk',$ignore);
		return $this->db->get('mapping_case as a')->result();
	}
	
	public function get_list_pendapatan_smartphone(){
		$ignore = array('4120101', '4110101'); 
		$this->db->select('no_perk,na_perk');
		$this->db->where('type','CASH_IN');
		$this->db->where_in('no_perk', $ignore);
		return $this->db->get('mapping_case as a')->result();
	}

	public function get_list_pengeluaran(){
		$ignore = array('1120', '1110','1140'); 
		$this->db->select('no_perk,na_perk');
		$this->db->where('type','CASH_OUT');
		$this->db->where_not_in('SUBSTRING(no_perk,1,4)',$ignore);
		return $this->db->get('mapping_case as a')->result();
	}

}