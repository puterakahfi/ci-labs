<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * gallery controller
 */
class gallery extends Front_Controller
{

	//--------------------------------------------------------------------


	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('gallery_model', null, true);
		$this->lang->load('gallery');
		

		Assets::add_module_js('gallery', 'gallery.js');
	}

	//--------------------------------------------------------------------


	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{

		$records = $this->gallery_model->find_all();

		Template::set('records', $records);
		Template::render();
	}

	//--------------------------------------------------------------------



}