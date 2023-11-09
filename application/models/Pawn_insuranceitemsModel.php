<?php
require_once 'Master.php';
class Pawn_insuranceitemsModel extends Master
{
	public $table = 'transaction_insurance_items';
	public $primary_key = 'id';


	public function getInsuranceItems($id)
	{
		return $this->db->select('name,amount,carats,description,estimated_value,gross_weight,net_weight')->from($this->table)
			->order_by('pawn_transaction_id', $id)
			->get()->row();
	}

}