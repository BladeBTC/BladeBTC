<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use BladeBTC\GUI\Helpers\Password;
use Exception;
use PDO;
use PDOStatement;

/**
 * Class AccountModel
 *
 * @package App\Models
 */
class AccountModel
{

    /**
     * Get all account
     *
     * @param bool $include_deleted include row deleted
     *
     * @return PDOStatement
     */
    public static function getAll($include_deleted = false)
    {
        $db = Database::get();

        if ($include_deleted) {
            $accounts = $db->query("SELECT * FROM gui_account");
        }
        else {
            $accounts = $db->query("SELECT * FROM gui_account WHERE deleted = 0");
        }

        return $accounts;
    }


    /**
     * Create AccountModel
     *
     * @param $data - AccountModel Data
     *
     * @throws Exception
     */
    public static function create($data)
    {
        $db = Database::get();

        $query = "	INSERT
					INTO
					  `gui_account`(
						`first_name`,
						`last_name`,
						`username`,
						`password`,
						`email`,
						`account_group`,
						`inscription_date`
					  )
					VALUES(
					 	:first_name,
						:last_name,
						:username,
						:password,
						:email,
						:account_group,
						NOW()
					)";

        $sth = $db->prepare($query);

        $sth->execute([
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "username" => $data['username'],
            "email" => $data['email'],
            "password" => Password::hash($data['password']),
            "account_group" => $data['account_group'],
        ]);
    }

    /**
     * Update user account
     *
     * @param $data - Update data
     *
     * @throws Exception
     */
    public static function update($data)
    {
        $db = Database::get();

        if (!empty($data["password"])) {

            $query = "	UPDATE
					  `gui_account`
					SET
					  	`first_name` = :first_name,
						`last_name` = :last_name,
						`username` = :username,
						`password` = :password,
						`email` = :email,
						`account_group` = :account_group
					WHERE
					  `id` = :id
					";

            $sth = $db->prepare($query);

            $sth->execute([
                "id" => $data['id'],
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name'],
                "username" => $data['username'],
                "email" => $data['email'],
                "password" => Password::hash($data['password']),
                "account_group" => $data['account_group'],
            ]);

        }
        else {

            $query = "	UPDATE
					  `gui_account`
					SET
					  	`first_name` = :first_name,
						`last_name` = :last_name,
						`username` = :username,
						`email` = :email,
						`account_group` = :account_group
					WHERE
					  `id` = :id
					";

            $sth = $db->prepare($query);

            $sth->execute([
                "id" => $data['id'],
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name'],
                "username" => $data['username'],
                "email" => $data['email'],
                "account_group" => $data['account_group'],
            ]);
        }


    }

    /**
     * Delete AccountModel
     *
     * @param $account_id - AccountModel ID
     *
     * @throws Exception
     */
    public static function delete($account_id)
    {

        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						deleted = 1,
						deleted_date = NOW()
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
        ]);

    }

    /**
     * Check if account is deleted
     *
     * @param $account_id - AccountModel ID
     *
     * @return bool
     */
    public static function isDeleted($account_id)
    {

        $db = Database::get();

        $deleted = $db->query("SELECT deleted FROM gui_account WHERE id=$account_id")->fetchObject()->deleted;

        if ($deleted == 1) {
            return true;
        }

        return false;

    }

    /**
     * Get all data from account
     *
     * @param      $account_id  - AccountModel ID
     * @param bool $fetch_assoc - Fetch mode
     *
     * @return mixed
     */
    public static function getById($account_id, $fetch_assoc = false)
    {
        $db = Database::get();

        if ($fetch_assoc) {
            $account = $db->query("SELECT * FROM gui_account WHERE id=$account_id")->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $account = $db->query("SELECT * FROM gui_account WHERE id=$account_id")->fetchObject();
        }

        return $account;
    }


    /**
     * Get account id by username
     *
     * @param $username - Nom d'utilisateur
     *
     * @return null - AccountModel ID or NULL
     */
    public static function getIdByUsername($username)
    {
        $db = Database::get();

        $id = $db->query("SELECT id FROM gui_account WHERE username='$username'")->fetchObject();

        return is_object($id) ? $id->id : null;
    }

    /**
     * Get account id by email
     *
     * @param $email - Courriel
     *
     * @return null - AccountModel ID or NULL
     */
    public static function getIdByEmail($email)
    {

        $db = Database::get();

        $id = $db->query("SELECT id FROM gui_account WHERE email='$email'")->fetchObject();


        return is_object($id) ? $id->id : null;
    }


    /**
     * @param $account_id - User ID
     *
     * @return mixed - Return Full Name
     */
    public static function getFullName($account_id)
    {
        if (!is_null($account_id)) {
            return self::getFirstName($account_id) . " " . self::getLastName($account_id);
        }

        return null;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - First Name
     */
    public static function getFirstName($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT first_name FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->first_name;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - Last Name
     */
    public static function getLastName($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT last_name FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->last_name;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - Email
     */
    public static function getEmail($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT email FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->email;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - Password Hash
     */
    public static function getPassword($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT password FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->password;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - Login Attempt Count
     */
    public static function getLoginAttempt($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT login_attempt FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->login_attempt;
    }


    /**
     * @param $account_id - User ID
     *
     * @return mixed - Inscription Date
     */
    public static function getInscriptionDate($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT inscription_date FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->inscription_date;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - Last Login Date
     */
    public static function getLastLoginDate($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT last_login_date FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->last_login_date;
    }

    /**
     * @param $account_id - User ID
     *
     * @return mixed - Last IP
     */
    public static function getLastIp($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT last_login_ip FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->last_ip;
    }


    /**
     * @param $account_id - User ID
     *
     * @return mixed - AccountModel GroupModel
     */
    public static function getAccountGroup($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT account_group FROM gui_account WHERE id=$account_id");

        return $account->fetchObject()->account_group;
    }


    /**
     * Get profile image
     *
     * @param $account_id - User ID
     *
     * @return mixed
     */
    public static function getProfileImg($account_id)
    {
        $db = Database::get();

        $account = $db->query("SELECT profile_img FROM gui_account WHERE id=$account_id")->fetchObject()->profile_img;

        return is_null($account) ? 'avatar.png' : $account;
    }

    /**
     * Update First Name
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function setFirstName($data)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						first_name = :first_name
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $data['id'],
            "first_name" => $data['first_name'],
        ]);
    }

    /**
     * Update Last Name
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function setLastName($data)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						last_name = :last_name
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $data['id'],
            "last_name" => $data['last_name'],
        ]);
    }

    /**
     * Update Email
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function setEmail($data)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						email = :email
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $data['id'],
            "email" => $data['email'],
        ]);
    }

    /**
     * Update password hash
     *
     * @param $account_id
     * @param $hash
     *
     * @throws Exception
     */
    public static function setPassword($account_id, $hash)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						password = :password
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
            "password" => $hash,
        ]);
    }

    /**
     * Update login attempt
     *
     * @param $account_id
     * @param $attempt
     *
     * @throws Exception
     */
    public static function setLoginAttempt($account_id, $attempt)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						login_attempt = :login_attempt
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
            "login_attempt" => $attempt,
        ]);
    }

    /**
     * Update Inscription Date
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function setInscriptionDate($data)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						inscription_date = :inscription_date
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $data['id'],
            "inscription_date" => $data['inscription_date'],
        ]);
    }

    /**
     * Update Last Login Date
     *
     * @param $account_id
     *
     * @throws Exception
     */
    public static function setLastLoginDate($account_id)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						last_login_date = NOW()
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
        ]);
    }

    /**
     * Update last IP
     *
     * @param $account_id
     * @param $ip
     *
     * @throws Exception
     */
    public static function setLastIp($account_id, $ip)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						last_login_ip = :last_ip
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
            "last_ip" => $ip,
        ]);
    }

    /**
     * Update account group
     *
     * @param $account_id - AccountModel ID
     * @param $group_id   - GroupModel ID
     *
     * @throws Exception
     */
    public static function setAccountGroup($account_id, $group_id)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						account_group = :account_group
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
            "account_group" => $group_id,
        ]);
    }

    /**
     * Set profile image
     *
     * @param $account_id - User ID
     *
     * @param $img
     *
     * @throws Exception
     */
    public static function setProfileImg($account_id, $img)
    {
        $db = Database::get();

        $query = "	UPDATE 
						gui_account 
					SET 
						profile_img = :profile_img
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $account_id,
            "profile_img" => $img,
        ]);
    }
}


