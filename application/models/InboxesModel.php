<?php
require_once 'Master.php';
class InboxesModel extends Master
{
	/**
	 * @var string
	 */

	public $table = 'inboxes';

	/**
	 * @var string
	 */

	public $primary_key = 'id';

	public function statistic()
	{
		$this->model->db
			->select('units.name as unit_name')
			->join('users','users.id = inboxes.compose_from')
			->join('units','units.id = users.id_unit')
			->where('inboxes.status',"DELETED")
			->where('units.id',$this->session->userdata('user')->id_unit);
		$trash = $this->all();

		$this->model->db
			->select('units.name as unit_name')
			->join('users','users.id = inboxes.compose_from')
			->join('units','units.id = users.id_unit')
			->where('inboxes.status',"PUBLISH")
			->where('units.id',$this->session->userdata('user')->id_unit);
		$send = $this->all();

		$this->model->db
			->select('units.name as unit_name')
			->join('users','users.id = inboxes.compose_from')
			->join('units','units.id = users.id_unit')
			->where('inboxes.status',"PUBLISH")
			->where('compose_to',$this->session->userdata('user')->id_unit);
		$inbox = $this->all();

		return (object) array(
			'trash'	=> count($trash),
			'send'	=> count($send),
			'inbox'	=> count($inbox),
		);
	}
}
