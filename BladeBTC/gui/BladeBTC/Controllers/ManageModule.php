<?php

namespace BladeBTC\GUI\Controllers;

use BladeBTC\GUI\Helpers\Database;
use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Models\ModuleModel;
use Exception;

class ManageModule
{

    /**
     * Handle multiples actions in module management.
     *
     * @return null|string
     * @throws Exception
     */
	public static function action()
	{

		$action = Request::get('action');

		$msg = null;
		switch ($action) {

			case "edit" :

				$module_data = ModuleModel::getById(Request::get('id'), true);
				Form::save($module_data, true);

				$msg = "The module has been loaded.";

				break;

			case "delete":

				$id = Request::get('id');

				if (!ModuleModel::isDashboard($id)) {
					ModuleModel::delete($id);
				} else {
					throw new Exception("This module can not be deleted because it is used as a home page for a group. You must first edit the groups.");
				}

				$msg = "The module has been removed.";

				break;

		}

		return $msg;
	}

	/**
	 * Add new module
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function add()
	{
		/**
		 * Save form data
		 */
		Form::save(Request::post());

		/**
		 * Form value
		 */
		$description = Request::post('description');
		$name = Request::post('name');
		$icon = Request::post('icon');
		$parent = Request::post('parent');
		$active = Request::post('active');
		$static = Request::post('static');
		$access_level = Request::post('access_level');

		/**
		 * description validate
		 */
		if (empty($description)) {
			Form::remove('description');
			throw new Exception("You must enter a module description.");
		}

		/**
		 * name validate
		 */
		if (empty($name)) {
			Form::remove('name');
			throw new Exception("You must enter a module name.");
		}

		if (!empty($name) && !Database::fieldIsUnique('gui_module', 'name', $name)) {
			Form::remove('name');
			throw new Exception("This module name is already used.");
		}

		/**
		 * icon validate
		 */
		if ($icon == -1) {
			Form::remove('icon');
			throw new Exception("You must enter a module icon.");
		}

		/**
		 * parent validate
		 */
		if (empty($parent) && $static != 1) {
			Form::remove('parent');
			throw new Exception("You must enter a parent menu for the module.");
		}


		/**
		 * active validate
		 */
		if ($active == -1) {
			Form::remove('active');
			throw new Exception("You must choose if the module is active.");
		}

		/**
		 * static validate
		 */
		if ($static == -1) {
			Form::remove('static');
			throw new Exception("You must choose if the module is static.");
		}

		/**
		 * GroupModel
		 */
		if (is_null($access_level)) {
			Form::remove('access_level');
			throw new Exception("You must choose at least one group.");
		}


		/**
		 * Prepare group string
		 */
		$access_level_string = implode(";", $access_level);


		/**
		 * Prepare data
		 */
		$module = [
			"description"  => $description,
			"name"         => $name,
			"icon"         => $icon,
			"parent"       => $parent,
			"active"       => $active,
			"static"       => $static,
			"access_level" => $access_level_string,
		];

		try {

			ModuleModel::add($module);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Edit module
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function edit()
	{

		/**
		 * Save form data
		 */
		Form::update(Request::post());

		/**
		 * Form value
		 */
		$module_id = Form::getReturn('id');
		$description = Request::post('description');
		$name = Request::post('name');
		$icon = Request::post('icon');
		$parent = Request::post('parent');
		$active = Request::post('active');
		$static = Request::post('static');
		$access_level = Request::post('access_level');


		/**
		 * description validate
		 */
		if (empty($description)) {
			Form::remove('description');
			throw new Exception("You must enter a module description.");
		}

		/**
		 * name validate
		 */
		if (empty($name)) {
			Form::remove('name');
			throw new Exception("You must enter a module name.");
		}

		if (!empty($name) && !Database::fieldIsUnique('gui_module', 'name', $name, [$module_id])) {
			Form::remove('name');
			throw new Exception("This module name is already used.");
		}

		/**
		 * icon validate
		 */
		if ($icon == -1) {
			Form::remove('icon');
			throw new Exception("You must enter a module icon.");
		}

		/**
		 * parent validate
		 */
		if (empty($parent) && $static != 1) {
			Form::remove('parent');
			throw new Exception("You must enter a parent menu for the module.");
		}


		/**
		 * active validate
		 */
		if ($active == -1) {
			Form::remove('active');
			throw new Exception("You must choose if the module is active.");
		}

		/**
		 * static validate
		 */
		if ($static == -1) {
			Form::remove('static');
			throw new Exception("You must choose if the module is static.");
		}

		/**
		 * GroupModel
		 */
		if (is_null($access_level)) {
			Form::remove('access_level');
			throw new Exception("You must choose at least one group.");
		}


		/**
		 * Prepare access_level string
		 */
		$access_level_string = implode(";", $access_level);


		/**
		 * Prepare data
		 */
		$module = [
			"id"           => $module_id,
			"description"  => $description,
			"name"         => $name,
			"icon"         => $icon,
			"parent"       => $parent,
			"active"       => $active,
			"static"       => $static,
			"access_level" => $access_level_string,
		];

		try {

			ModuleModel::update($module);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}

