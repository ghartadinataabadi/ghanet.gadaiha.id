<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
error_reporting(0);
class Repairos extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Outstanding';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'model');
	}

    public function index()
    {
        $date = $this->input->get('date') ? $this->input->get('date') : $this->model->db->select('date')->order_by('date','desc')->get('units_outstanding')->row()->date;
        $outstandings = $this->model->db
            ->select('units_outstanding.*')
            ->join('units','units.id = units_outstanding.id_unit')
            ->where('id_area', $this->input->get('id_area'))
            ->where('date', $date)->get('units_outstanding')->result();
        foreach($outstandings as $os){
            $this->model->db->where('id', $os->id)
                ->update('units_outstanding',[
                    'os'    => $os->real_outstanding,
                    'noa_os_regular'    => $os->noa_real_reguler,
                    'os_regular'    => $os->os_real_reguler,
                    'noa_os_mortage'    => $os->noa_real_mortage,
                    'os_mortage'    => $os->os_real_mortage,
                ]);
        }
        
    }

}
