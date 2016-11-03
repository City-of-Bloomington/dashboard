<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class CardLogEntry extends ActiveRecord
{
    protected $tablename = 'cardLog';
    protected $card;

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
                if (!empty($id['logDate'      ])) { $id['logDate'      ] = new \DateTime($id['logDate'      ]); }
                if (!empty($id['effectiveDate'])) { $id['effectiveDate'] = new \DateTime($id['effectiveDate']); }

				$this->data = $id;
			}
			else {
                $sql = "select * from {$this->tablename} where id=?";

				$rows = self::doQuery($sql, [$id]);
                if (count($rows)) {
                    $this->data = $rows[0];
                }
                else {
                    throw new \Exception("{$this->tablename}/unknown");
                }
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->setLogDate(new \DateTime());
		}
	}

    public function validate()
    {
        if (!$this->getLogDate      ()) { $this->setLogDate      (new \DateTime()); }
        if (!$this->getEffectiveDate()) { $this->setEffectiveDate(new \DateTime()); }

        if (!$this->getCard_id() || !$this->getResponse()) {
            throw new \Exception('missingRequiredFields');
        }
    }

    public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()      { return parent::get('id'); }
	public function getCard_id() { return parent::get('card_id'); }
	public function getCard()    { return parent::getForeignKeyObject(__namespace__.'\Card', 'card_id'); }
	public function getLogDate      ($format=null) { return parent::getFormattedDate('logDate',       $format); }
	public function getEffectiveDate($format=null) { return parent::getFormattedDate('effectiveDate', $format); }

	public function setCard_id($id)  { parent::setForeignKeyField (__namespace__.'\Card', 'card_id', $id); }
	public function setCard(Card $o) { parent::setForeignKeyObject(__namespace__.'\Card', 'card_id', $o ); }
	public function setLogDate      (\DateTime $d) { parent::set('logDate',       $d); }
	public function setEffectiveDate(\DateTime $d) { parent::set('effectiveDate', $d); }

	/**
	 * @return array
	 */
	public function getResponse() { return json_decode(parent::get('response'), true); }
	public function setResponse(array $r=null)
	{
        if ($r) { $r = json_encode($r); }
        parent::set('response', $r);
    }

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	/**
	 * @return int
	 */
	public function getMetricValue()
	{
        return $this->getResponse()[$this->getCard()->getResponseKey()];
	}

	/**
	 * @return string
	 */
	public function getMetricUnits()
	{
        $card       = $this->getCard();
        $definition = $card->getMethodDefinition();
        return $definition['labels'][$card->getResponseKey()];
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
        $hasData  = false;
        $response = $this->getResponse();
        $card     = $this->getCard();

        if (is_int($response[$card->getResponseKey()])) { $hasData = true; }

        if ($hasData) {
            $target = (int)$card->getTarget();
            $value  = $this->getMetricValue();

            $status = 'fail';
            switch ($card->getComparison()) {
                case 'gt' : if ($value >  $target) { $status = 'pass'; } break;
                case 'gte': if ($value >= $target) { $status = 'pass'; } break;
                case 'lt' : if ($value <  $target) { $status = 'pass'; } break;
                case 'lte': if ($value <= $target) { $status = 'pass'; } break;
            }
            return $status;
        }
	}

	/**
	 * @return array ['start'=> DateTime, 'end'=> DateTime]
	 */
	public function getPeriodRange()
	{
        $end   = $this->getLogDate();
        $start = clone($end);
        $start->sub(new \DateInterval("P{$this->getCard()->getPeriod()}D"));

        return [
            'start' => $start,
            'end'   => $end
        ];
	}
}
