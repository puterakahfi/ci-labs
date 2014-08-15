<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_gallery_permissions extends Migration
{

	/**
	 * Permissions to Migrate
	 *
	 * @var Array
	 */
	private $permission_values = array(
		array(
			'name' => 'Gallery.Content.View',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Content.Create',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Content.Edit',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Content.Delete',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Reports.View',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Reports.Create',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Reports.Edit',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Reports.Delete',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Settings.View',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Settings.Create',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Settings.Edit',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Settings.Delete',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Developer.View',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Developer.Create',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Developer.Edit',
			'description' => '',
			'status' => 'active',
		),
		array(
			'name' => 'Gallery.Developer.Delete',
			'description' => '',
			'status' => 'active',
		),
	);

	/**
	 * The name of the permissions table
	 *
	 * @var String
	 */
	private $table_name = 'permissions';

	/**
	 * The name of the role/permissions ref table
	 *
	 * @var String
	 */
	private $roles_table = 'role_permissions';

	//--------------------------------------------------------------------

	/**
	 * Install this migration
	 *
	 * @return void
	 */
	public function up()
	{
		$role_permissions_data = array();
		foreach ($this->permission_values as $permission_value)
		{
			$this->db->insert($this->table_name, $permission_value);

			$role_permissions_data[] = array(
				'role_id' => '1',
				'permission_id' => $this->db->insert_id(),
			);
		}

		$this->db->insert_batch($this->roles_table, $role_permissions_data);
	}

	//--------------------------------------------------------------------

	/**
	 * Uninstall this migration
	 *
	 * @return void
	 */
	public function down()
	{
		foreach ($this->permission_values as $permission_value)
		{
			$query = $this->db->select('permission_id')
				->get_where($this->table_name, array('name' => $permission_value['name'],));

			foreach ($query->result() as $row)
			{
				$this->db->delete($this->roles_table, array('permission_id' => $row->permission_id));
			}

			$this->db->delete($this->table_name, array('name' => $permission_value['name']));
		}
	}

	//--------------------------------------------------------------------

}