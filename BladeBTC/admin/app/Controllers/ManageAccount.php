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
use App\Helpers\Password;
use App\Helpers\Request;
use App\Helpers\Utils;
use App\Models\AccountModel;
use App\Models\GroupModel;
use Exception;

class ManageAccount
{

	/**
	 * Handle multiples actions in account management.
	 *
	 * @return null|string
	 */
	public static function action()
	{

		$action = Request::get('action');

		$msg = null;
		switch ($action) {

			case "edit_group" :

				$group_data = GroupModel::getByGroupId(Request::get('group_id'), true);
				Form::save($group_data, true);
				$msg = "Le groupe a bien été chargé.";

				break;

			case "delete_group":

				GroupModel::delete(Request::get('group_id'));

				$msg = "Le groupe a bien été supprimé.";

				break;


			case "edit_account" :

				$account_data = AccountModel::getById(Request::get('id'), true);
				Form::save($account_data, true);
				$msg = "Le compte a bien été chargé.";

				break;

			case "delete_account":

				AccountModel::delete(Request::get('id'));

				$msg = "Le compte a bien été supprimé.";

				break;

			case "reset_pwd":

				AccountModel::setPassword(Request::get('id'), Password::hash('atc2453!'));
				AccountModel::setLoginAttempt(Request::get('id'), 0);

				$msg = "Le mot de passe a bien été réinitialisé avec le mot de passe suivant : atc2453!";

				break;


			case "unlock":

				AccountModel::setLoginAttempt(Request::get('id'), 0);

				$msg = "Le compte à bien été déverrouillé.";

				break;
		}

		return $msg;
	}

	/**
	 * Add new account group
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function addGroup()
	{

		/**
		 * Save form data
		 */
		Form::save(Request::post());

		/**
		 * Form value
		 */
		$group_name = Request::post('group_name');
		$dashboard = Request::post('dashboard');

		/**
		 * GroupModel name validate
		 */
		if (empty($group_name)) {
			Form::remove('group_name');
			throw new Exception("Vous devez entrer un nom de groupe.");
		}

		/**
		 * Dashboard validate
		 */
		if ($dashboard == -1) {
			Form::remove('dashboard');
			throw new Exception("Vous devez entrer une page d'accueil.");
		}

		/**
		 * Check if MenuModel already have same name.
		 */
		if (!empty($group_name) && !Database::fieldIsUnique('ae_group', 'group_name', $group_name)) {
			Form::remove('group_name');
			throw new Exception("Un autre groupe porte déjà le même nom.");
		}

		/**
		 * Prepare data
		 */
		$group = [
			"group_name" => $group_name,
			"dashboard"  => $dashboard,
		];

		try {

			GroupModel::add($group);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Edit account group
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function editGroup()
	{

		/**
		 * Update form data
		 */
		Form::update(Request::post());

		/**
		 * Form value
		 */
		$row_id = Form::getReturn('id');
		$group_id = Form::getReturn('group_id');
		$group_name = Request::post('group_name');
		$dashboard = Request::post('dashboard');

		/**
		 * GroupModel name validate
		 */
		if (empty($group_name)) {
			Form::remove('group_name');
			throw new Exception("Vous devez entrer un nom de groupe.");
		}

		/**
		 * Dashboard validate
		 */
		if ($dashboard == -1) {
			Form::remove('dashboard');
			throw new Exception("Vous devez entrer une page d'accueil.");
		}

		/**
		 * Check if MenuModel already have same name.
		 */
		if (!empty($group_name) && !Database::fieldIsUnique('ae_group', 'group_name', $group_name, [$row_id])) {
			Form::remove('group_name');
			throw new Exception("Un autre groupe porte déjà le même nom.");
		}

		/**
		 * Prepare data
		 */
		$group = [
			"group_id"   => $group_id,
			"group_name" => $group_name,
			"dashboard"  => $dashboard,
		];

		try {

			GroupModel::update($group);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

	}

	/**
	 * Add new account
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function addAccount()
	{

		/**
		 * Save form data
		 */
		Form::save(Request::post());

		/**
		 * Form value
		 */
		$first_name = Request::post('first_name');
		$last_name = Request::post('last_name');
		$username = Request::post('username');
		$account_group = Request::post('account_group');
		$password = Request::post('password');
		$email = Request::post('email');

		/**
		 * Validate first name
		 */
		if (empty($first_name)) {
			Form::remove('first_name');
			throw new Exception("Vous devez entrer un prénom.");
		}


		/**
		 * Validate last name
		 */
		if (empty($last_name)) {
			Form::remove('last_name');
			throw new Exception("Vous devez entrer un nom.");
		}


		/**
		 * Validate username
		 */
		if (empty($username)) {
			Form::remove('username');
			throw new Exception("Vous devez entrer un nom d'utilisateur.");
		}

		if (!empty($username) && !Database::fieldIsUnique('ae_account', 'username', $username)) {
			Form::remove('username');
			throw new Exception("Ce nom d'utilisateur n'est pas disponible.");
		}

		/**
		 * Validate group
		 */
		if (empty($account_group)) {
			Form::remove('account_group');
			throw new Exception("Vous devez choisir un groupe.");
		}

		/**
		 * Validate password
		 */
		if (empty($password)) {
			Form::remove('password');
			throw new Exception("Vous devez entrer un mot de passe.");
		}

		/**
		 * Validate email
		 */
		if (!Utils::isEmail($email)) {
			Form::remove('email');
			throw new Exception("Vous devez entrer un courriel valide.");
		}


		/**
		 * Prepare data
		 */
		$account = [
			"first_name"    => $first_name,
			"last_name"     => $last_name,
			"username"      => $username,
			"email"         => $email,
			"password"      => Password::hash($password),
			"account_group" => $account_group,
		];

		try {

			AccountModel::create($account);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Edit account
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function editAccount()
	{

		/**
		 * Save form data
		 */
		Form::update(Request::post());

		/**
		 * Form value
		 */
		$first_name = Request::post('first_name');
		$last_name = Request::post('last_name');
		$username = Request::post('username');
		$account_group = Request::post('account_group');
		$password = Request::post('password');
		$email = Request::post('email');
		$account_id = Form::getReturn('id');

		/**
		 * Validate first name
		 */
		if (empty($first_name)) {
			Form::remove('first_name');
			throw new Exception("Vous devez entrer un prénom.");
		}


		/**
		 * Validate last name
		 */
		if (empty($last_name)) {
			Form::remove('last_name');
			throw new Exception("Vous devez entrer un nom.");
		}


		/**
		 * Validate username
		 */
		if (empty($username)) {
			Form::remove('username');
			throw new Exception("Vous devez entrer un nom d'utilisateur.");
		}

		if (!empty($username) && !Database::fieldIsUnique('ae_account', 'username', $username, [$account_id])) {
			Form::remove('username');
			throw new Exception("Ce nom d'utilisateur n'est pas disponible.");
		}

		/**
		 * Validate group
		 */
		if (empty($account_group)) {
			Form::remove('account_group');
			throw new Exception("Vous devez choisir un groupe.");
		}


		/**
		 * Validate email
		 */
		if (!Utils::isEmail($email)) {
			Form::remove('email');
			throw new Exception("Vous devez entrer un courriel valide.");
		}


		/**
		 * Prepare data
		 */
		$account = [
			"id"            => $account_id,
			"first_name"    => $first_name,
			"last_name"     => $last_name,
			"username"      => $username,
			"email"         => $email,
			"password"      => $password,
			"account_group" => $account_group,
		];

		try {

			AccountModel::update($account);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

	}
}

