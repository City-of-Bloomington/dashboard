<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Group extends ActiveRecord
{
    protected $tablename = 'groups';

    public function validate()
    {
        if (!$this->getName() || !$this->getCSSClass()) {
            throw new \Exception('missingRequiredFields');
        }
    }

    public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId      () { return parent::get('id'      ); }
	public function getName    () { return parent::get('name'    ); }
	public function getCSSClass() { return parent::get('cssClass'); }

	public function setName    ($s) { parent::set('name',     $s); }
	public function setCSSClass($s) { parent::set('cssClass', $s); }

	public function handleUpdate(array $post)
	{
        $this->setName    ($post['name'    ]);
        $this->setCSSClass($post['cssClass']);
	}

	//----------------------------------------------------------------
	// Custom functions
	//----------------------------------------------------------------
	public function __toString() { return parent::get('name'); }

	/**
	 * @return string
	 */
	public function getIconUri() {
        $icons = glob(SITE_HOME."/group_icons/{$this->getCSSClass()}.*");
        if (count($icons)) {
            return BASE_URI.'/group_icons/'.basename($icons[0]);
        }
	}
}
