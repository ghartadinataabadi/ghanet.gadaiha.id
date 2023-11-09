<?php

class Master extends CI_Model
{
	public $table;

	public $primary_key = 'id';

	public $hirarki = false;

	public $level = false;

	public function insert(array $data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function buildHirarki($id_parent = 0, $dept = 0, $order = 0)
	{
		if($parents = $this->findWhere(array('id_parent'=>$id_parent))){
			foreach ($parents as $parent){
				$order++;
				if($parent->id_parent == 0){
					$dept = 0;
				}else{
					$dept++;
				}
				$data = array(
					'dept'	=> $dept,
					'order'	=> $order
				);
				$this->update($data, $parent->id);
				if($childs = $this->findWhere(array(
					'id_parent'	=> $parent->id
				))){
					$dept = $dept+1;
					foreach ($childs as $child){
						$order++;
						$data = array(
							'dept'	=> $dept,
							'order'	=> $order
						);
						$this->update($data, $child->id);
						$this->buildHirarki($child->id, $dept, $order++);
					}
				}
			}
		}
	}

	public function all($limit = null)
	{
		if($this->level){
			if($this->session->userdata('user')->level == 'unit'){
				$this->db->where($this->table.'.id_unit', $this->session->userdata('user')->id_unit);
			}elseif($this->session->userdata('user')->level == 'area'){
				$this->db->where('id_area', $this->session->userdata('user')->id_area);
			}
		}
		if(!is_null($limit)){
			$this->db->limit($limit);
		}
		if($this->hirarki){
			$this->db->order_by('order','ASC');
		}
		return $this->db
			->select($this->table.'.*')
			->from($this->table)
			->get()->result();
	}


	public function update(array $data, $condition = array())
	{
		if(is_array($condition)){
			$this->db->where($condition);
		}else{
			$this->db->where($this->primary_key, $condition);
		}
		return $this->db->update($this->table, $data);
	}

	public function last()
	{
		return $this->db->order_by($this->primary_key,'DESC')->get($this->table)->row();
	}

	public function find($condition = array())
	{
		if(is_array($condition)){
			$this->db->where($condition);
		}else{
			$this->db->where($this->primary_key, $condition);
		}
		return $this->db->select($this->table.'.*')->from($this->table)->get()->row();
	}

	public function deleteBatch($field, $array = array())
	{
		$this->db->where_in($field, $array);
		return $this->db->delete($this->table);
	}

	public function deleteBatchSoft($field, $array = array())
	{
		$this->db->where_in($field, $array);
		return $this->db->update($this->table, array(
			'status'	=> 'DELETED'
		));
	}

	public function insertOrUpdate($data, $condition = array())
	{
		if($this->find($condition)){
			return $this->update($data,$condition);
		}else{
			return $this->insert($data);
		}
	}

	public function updateOrInsert($data, $condition = array())
	{
		return $this->insertOrUpdate($data, $condition);
	}

	public function findWhere($condition = array())
	{
		if(is_array($condition)){
			$this->db->where($condition);
		}
		return $this->db->select($this->table.'.*')->from($this->table)->get()->result();
	}

	public function delete($condition = array())
	{
		if(!is_array($condition)){
			$condition = array(
				$this->primary_key		=> $condition
			);
		}
		return $this->db->delete($this->table, $condition);

	}
	
	public function getAll($limit = null)
	{
		// if($this->level){
		// 	if($this->session->userdata('user')->level == 'unit'){
		// 		$this->db2->where($this->table.'.id_unit', $this->session->userdata('user')->id_unit);
		// 	}elseif($this->session->userdata('user')->level == 'area'){
		// 		$this->db2->where('id_area', $this->session->userdata('user')->id_area);
		// 	}
		// }
		if(!is_null($limit)){
			$this->db2->limit($limit);
		}
		if($this->hirarki){
			$this->db2->order_by('order','ASC');
		}
		return $this->db2
			->select($this->table.'.*')
			->from($this->table)
			->get()->result();
	}


}
