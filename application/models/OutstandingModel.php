<?php
require_once 'Master.php';
class OutstandingModel extends Master
{
	/**
	 * @var string
	 */

	public $table = 'units_outstanding';

	/**
	 * @var string
	 */

	public $primary_key = 'id';

    public $hirarki = true;
    
    public function getOs($unit,$date)
	{
		$today = $this->db->select('sum(os) as up')->from($this->table)
            ->where('id_unit', $unit)
            ->where('date', $date)->get()->row();
            
		$yesterday = $this->db->select('sum(os) as up')->from($this->table)
			->where('id_unit', $unit)
			->where('date', $date)->get()->row();
		return (object)array(
			'today' => (int) $today->up,
			'yesterday' => (int) $yesterday->up
		);
	}



}
