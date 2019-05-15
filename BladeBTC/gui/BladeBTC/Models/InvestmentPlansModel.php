<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;

/**
 * Class InvestmentPlansModel
 *
 * @package BladeBTC\GUI\Models\
 */
class InvestmentPlansModel
{
	
	public static function getAll()
	{
		$db = Database::get();
	}

	public static function add($data)
	{
		$db = Database::get();

		$query = "INSERT INTO 
					  table (
						field
					  )
					VALUES (
						:field 
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"field"  => $data['field'],
		]);
	}
	
	
	public static function update($data)
	{
		$db = Database::get();

		$query = "UPDATE
					  table
					SET
					  field = :field
					WHERE
					  id = :id";

		$sth = $db->prepare($query);

		$sth->execute([
			"id"   => $data["id"],
			"field"  => $data["field"],
		]);
	}
	
	
	public static function delete($data)
	{
	
		$db = Database::get();
	
		Database::transaction($db, [
		
			"DELETE FROM table WHERE field = $data"
			
		]);
	}
}
