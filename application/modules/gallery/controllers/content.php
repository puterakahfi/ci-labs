<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * content controller
 */
class content extends Admin_Controller
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

		$this->auth->restrict('Gallery.Content.View');
		$this->load->model('gallery_model', null, true);
		$this->lang->load('gallery');
		
		Template::set_block('sub_nav', 'content/_sub_nav');

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

		// Deleting anything?
		if (isset($_POST['delete']))
		{
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->gallery_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('gallery_delete_success'), 'success');
				}
				else
				{
					Template::set_message(lang('gallery_delete_failure') . $this->gallery_model->error, 'error');
				}
			}
		}

		$records = $this->gallery_model->find_all();

		Template::set('records', $records);
		Template::set('toolbar_title', 'Manage gallery');
		Template::render();
	}

	//--------------------------------------------------------------------


	/**
	 * Creates a gallery object.
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict('Gallery.Content.Create');

		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_gallery())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('gallery_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'gallery');

				Template::set_message(lang('gallery_create_success'), 'success');
				redirect(SITE_AREA .'/content/gallery');
			}
			else
			{
				Template::set_message(lang('gallery_create_failure') . $this->gallery_model->error, 'error');
			}
		}
		Assets::add_module_js('gallery', 'gallery.js');

		Template::set('toolbar_title', lang('gallery_create') . ' gallery');
		Template::render();
	}

	//--------------------------------------------------------------------


	/**
	 * Allows editing of gallery data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = $this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang('gallery_invalid_id'), 'error');
			redirect(SITE_AREA .'/content/gallery');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Gallery.Content.Edit');

			if ($this->save_gallery('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('gallery_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'gallery');

				Template::set_message(lang('gallery_edit_success'), 'success');
			}
			else
			{
				Template::set_message(lang('gallery_edit_failure') . $this->gallery_model->error, 'error');
			}
		}
		else if (isset($_POST['delete']))
		{
			$this->auth->restrict('Gallery.Content.Delete');

			if ($this->gallery_model->delete($id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('gallery_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'gallery');

				Template::set_message(lang('gallery_delete_success'), 'success');

				redirect(SITE_AREA .'/content/gallery');
			}
			else
			{
				Template::set_message(lang('gallery_delete_failure') . $this->gallery_model->error, 'error');
			}
		}
		Template::set('gallery', $this->gallery_model->find($id));
		Template::set('toolbar_title', lang('gallery_edit') .' gallery');
		Template::render();
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_gallery($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id_gallery'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();

		if ($type == 'insert')
		{
			$id = $this->gallery_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			}
			else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$return = $this->gallery_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}