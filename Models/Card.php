<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;
use Blossom\Classes\Url;

class Card extends ActiveRecord
{
    protected $tablename = 'cards';
    protected $service;

    public static $comparisons = ['gt', 'gte', 'lt', 'lte'];

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
                    throw new \Exception('cards/unknown');
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
        if (!$this->getDescription() || !$this->getService_id() || !$this->getMethod()) {
            throw new \Exception('missingRequiredFields');
        }

        if (!array_key_exists($this->getMethod(), $this->getService()->getMethods())) {
            throw new \Exception('cards/invalidMethod');
        }
	}

	public function save  () { parent::save  (); }
	public function delete() { parent::delete(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()            { return parent::get('id');           }
	public function getDescription()   { return parent::get('description');  }
	public function getMethod()        { return parent::get('method');       }
	public function getParameters()    { return json_decode(parent::get('parameters'), true); }
	public function getTarget()        { return (int)parent::get('target');  }
	public function getComparison()    { return parent::get('comparison');   }
	public function getService_id()    { return parent::get('service_id');   }
	public function getService()       { return parent::getForeignKeyObject(__namespace__.'\Service', 'service_id'); }

	public function setDescription($s) { parent::set('description', $s); }
	public function setMethod     ($s) { parent::set('method',      $s); }
	public function setTarget     ($i) { parent::set('target', (int)$i); }
	public function setComparison ($s) { parent::set('comparison',  $s); }
	public function setService_id($id)     { parent::setForeignKeyField (__namespace__.'\Service', 'service_id', $id); }
	public function setService(Service $o) { parent::setForeignKeyObject(__namespace__.'\Service', 'service_id', $o ); }
	public function setParameters(array $p=null)
	{
        if ($p) { $p = json_encode($p); }
        parent::set('parameters', $p);
    }

	public function handleUpdate($post)
	{
        $fields = ['description', 'service_id', 'method', 'parameters', 'target', 'comparison'];
        foreach ($fields as $f) {
            $set = 'set'.ucfirst($f);
            $this->$set($post[$f]);
        }
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	/**
	 * Queries the configured webservice and returns the value
	 */
	public function getCurrentValue()
	{
        $service = $this->getService();
        $method  = $this->getMethod();
        $params  = $this->getParameters();

        $o = $service->factory();
        $value = $o->$method($params);
        return $value;
	}

	/**
	 * return array [timestamp=> , value=> ]
	 */
	public function getLastLogEntry()
	{
        $sql = "select timestamp, value from card_log where card_id=?
                order by timestamp desc limit 1";
        $result = parent::doQuery($sql, [$this->getId()]);
        if (count( $result)) {
            $row   = $result[0];
            return [
                'timestamp' => new \DateTime($row['timestamp']),
                'value'     => $row['value']
            ];
        }
	}

	/**
	 * @return Blossom\Classes\Url
	 */
	public function getQueryUrl()
	{
        $service = self::$services[$this->getService()];
        $method  = $service['methods'][$this->getMethod()];
        $url     = new Url($service['base_url'].$method['uri']);

        $p = $this->getParameters();
        if (count($p)) {
            foreach ($p as $k=>$v) { $url->$k = $v; }
        }

        return $url;
	}

	/**
	 * @param string $value
	 */
	public function logValue($value)
	{
        $sql = 'insert into card_log set card_id=?, value=?';
        $pdo = Database::getConnection();
        $query = $pdo->prepare($sql);
        $success = $query->execute([$this->getId(), $value]);
        if (!$success) {
            $error = $query->errorInfo();
            throw new \Exception($error[2]);
        }
	}

	/**
	 * @return array
	 */
	public function getLogValues()
	{
        $log = [];

        $sql = "select timestamp, value from card_log where card_id=?
                order by timestamp desc";
        $result = parent::doQuery($sql, [$this->getId()]);
        foreach ($result as $row) {
            $log[] = [
                'timestamp' => new \DateTime($row['timestamp']),
                'value'     => $row['value']
            ];
        }
        return $log;
	}

	/**
	 * @return string PASS|FAIL|UNKNOWN
	 */
	public function getStatus($value)
	{
        $target = $this->getTarget();

        $status = 'fail';
        switch ($this->getComparison()) {
            case 'gt' : if ($value >  $target) { $status = 'pass'; } break;
            case 'gte': if ($value >= $target) { $status = 'pass'; } break;
            case 'lt' : if ($value <  $target) { $status = 'pass'; } break;
            case 'lte': if ($value <= $target) { $status = 'pass'; } break;
        }
        return $status;
	}
}