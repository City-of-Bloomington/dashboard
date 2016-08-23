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
    public static $services = [
        'uReport' => [
            'base_url'=> 'http://aoi.bloomington.in.gov/crm/metrics',
            'methods' => [
                'onTimePercentage' => [
                    'uri'        => '/onTimePercentage?format=json',
                    'parameters' => ['category_id'=>'', 'numDays'=>'']
                ]
            ]
        ]
    ];
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
        if (!$this->getDescription() || !$this->getService() || !$this->getMethod()) {
            throw new \Exception('missingRequiredFields');
        }

        if (!array_key_exists($this->getService(), Card::$services)) {
            throw new \Exception('cards/invalidService');
        }

        if (!array_key_exists($this->getMethod(), Card::$services[$this->getService()]['methods'])) {
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
	public function getService()       { return parent::get('service');      }
	public function getMethod()        { return parent::get('method');       }
	public function getParameters()    { return json_decode(parent::get('parameters'), true); }
	public function getTarget()        { return (int)parent::get('target');  }
	public function getComparison()    { return parent::get('comparison');   }

	public function setDescription($s) { parent::set('description', $s); }
	public function setService    ($s) { parent::set('service',     $s); }
	public function setMethod     ($s) { parent::set('method',      $s); }
	public function setTarget     ($i) { parent::set('target', (int)$i); }
	public function setComparison ($s) { parent::set('comparison',  $s); }
	public function setParameters(array $p=null)
	{
        if ($p) { $p = json_encode($p); }
        parent::set('parameters', $p);
    }

	public function handleUpdate($post)
	{
        $fields = ['description', 'service', 'method', 'parameters', 'target', 'comparison'];
        foreach ($fields as $f) {
            $set = 'set'.ucfirst($f);
            $this->$set($post[$f]);
        }
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------

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
}