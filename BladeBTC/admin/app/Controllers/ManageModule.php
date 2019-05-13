<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-10-17
 * Time: 14:59
 */

namespace App\Controllers;

use App\Helpers\Database;
use App\Helpers\Form;
use App\Helpers\Request;
use App\Models\ModuleModel;
use Exception;

class ManageModule
{

	/**
	 * Handle multiples actions in module management.
	 *
	 * @return null|string
	 */
	public static function action()
	{

		$action = Request::get('action');

		$msg = null;
		switch ($action) {

			case "edit" :

				$module_data = ModuleModel::getById(Request::get('id'), true);
				Form::save($module_data, true);

				$msg = "Le module a bien été chargé.";

				break;

			case "delete":

				$id = Request::get('id');

				if (!ModuleModel::isDashboard($id)) {
					ModuleModel::delete($id);
				} else {
					throw new Exception("Impossible de supprimer ce module puisqu'il est utilisé comme page d'accueil pour un groupe. Vous devez d'abord modifier les groupes.");
				}

				$msg = "Le module a bien été supprimé.";

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
			throw new Exception("Vous devez entrer une description de module.");
		}

		/**
		 * name validate
		 */
		if (empty($name)) {
			Form::remove('name');
			throw new Exception("Vous devez entrer un nom de module.");
		}

		if (!empty($name) && !Database::fieldIsUnique('ae_module', 'name', $name)) {
			Form::remove('name');
			throw new Exception("Ce nom de module est déjà utilisé.");
		}

		/**
		 * icon validate
		 */
		if ($icon == -1) {
			Form::remove('icon');
			throw new Exception("Vous devez entrer une icône de module.");
		}

		/**
		 * parent validate
		 */
		if (empty($parent) && $static != 1) {
			Form::remove('parent');
			throw new Exception("Vous devez entrer un menu parent pour le module.");
		}


		/**
		 * active validate
		 */
		if ($active == -1) {
			Form::remove('active');
			throw new Exception("Vous devez choisir si le module est actif.");
		}

		/**
		 * static validate
		 */
		if ($static == -1) {
			Form::remove('static');
			throw new Exception("Vous devez choisir si le module est static.");
		}

		/**
		 * GroupModel
		 */
		if (is_null($access_level)) {
			Form::remove('access_level');
			throw new Exception("Vous devez choisir au moins un groupe.");
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
			throw new Exception("Vous devez entrer une description de module.");
		}

		/**
		 * name validate
		 */
		if (empty($name)) {
			Form::remove('name');
			throw new Exception("Vous devez entrer un nom de module.");
		}

		if (!empty($name) && !Database::fieldIsUnique('ae_module', 'name', $name, [$module_id])) {
			Form::remove('name');
			throw new Exception("Ce nom de module est déjà utilisé.");
		}

		/**
		 * icon validate
		 */
		if ($icon == -1) {
			Form::remove('icon');
			throw new Exception("Vous devez entrer une icône de module.");
		}

		/**
		 * parent validate
		 */
		if (empty($parent) && $static != 1) {
			Form::remove('parent');
			throw new Exception("Vous devez entrer un menu parent pour le module.");
		}


		/**
		 * active validate
		 */
		if ($active == -1) {
			Form::remove('active');
			throw new Exception("Vous devez choisir si le module est actif.");
		}

		/**
		 * static validate
		 */
		if ($static == -1) {
			Form::remove('static');
			throw new Exception("Vous devez choisir si le module est static.");
		}

		/**
		 * GroupModel
		 */
		if (is_null($access_level)) {
			Form::remove('access_level');
			throw new Exception("Vous devez choisir au moins un groupe.");
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

