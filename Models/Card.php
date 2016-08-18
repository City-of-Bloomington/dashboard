<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Card extends ActiveRecord
{
	/**
	 * Populates the object with data
	 *
	 * Passing in an associative array of data will populate this object without
	 * hitting the database.
	 *
	 * Passing in a scalar will load the data from the database.
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int|array $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
				$this->data = $id;
			}
			else {
                $pdo = Database::getConnection();
                $sql = 'select * from cards where id=?';

				$rows = parent::doQuery($sql, [$id]);
                if (count($rows)) {
                    $this->data = $rows[0];
                }
                else {
                    throw new \Exception('cards/unknownCard');
                }
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	public function validate()
	{
        if (!$this->getDescription()) { throw new \Exception('missingRequiredFields'); }
	}

	public function save  () { parent::save  (); }
	public function delete() { parent::delete(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()            { return parent::get('id');           }
	public function getDescription()   { return parent::get('description');  }

	public function setDescription($s) { parent::set('description', $s); }

	public function handleUpdate($post)
	{
        $fields = ['description'];
        foreach ($fields as $f) {
            $set = 'set'.ucfirst($f);
            $this->$set($post[$f]);
        }
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
}