<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;
use Blossom\Classes\Url;

class Service extends ActiveRecord
{
    protected $tablename = 'services';

    public static function availableClasses()
    {
        $classes = [];
        foreach (glob(SITE_HOME.'/Classes/*Service.php') as $file) {

            $classes[] = substr(basename($file), 0, -4);
        }
        return $classes;
    }

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
                $sql = 'select * from services where id=?';

				$rows = parent::doQuery($sql, [$id]);
                if (count($rows)) {
                    $this->data = $rows[0];
                }
                else {
                    throw new \Exception('services/unknown');
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
        if (!$this->getName() || !$this->getClass() || !$this->getUrl()) {
            throw new \Exception('missingRequiredFields');
        }
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()    { return parent::get('id'   ); }
	public function getName()  { return parent::get('name' ); }
	public function getClass() { return parent::get('class'); }
	public function getUrl()   { return parent::get('url'  ); }

	public function setName ($s) { parent::set('name',  $s); }
	public function setClass($s) { parent::set('class', $s); }
	public function setUrl  ($s) { parent::set('url',   $s); }

	public function handleUpdate(array $post)
	{
        $fields = ['name', 'class', 'url'];
        foreach ($fields as $f) {
            $set = 'set'.ucfirst($f);
            $this->$set($post[$f]);
        }
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function factory()
	{
        $class = '\Site\Classes\\'.$this->getClass();
        $o = new $class($this->getUrl());
        return $o;
	}

	/**
	 * @return array
	 */
	public function getMethods()
	{
        $o = $this->factory();
        return $o->getMethods();
	}
}
