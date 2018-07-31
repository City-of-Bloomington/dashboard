<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
$this->template->addToAsset('scripts', $this->theme_config['GOOGLE_CHARTS']);
$this->template->addToAsset('scripts', BASE_URI.'/COB/js/sparklines.js');

include APPLICATION_HOME.'/blocks/html/cards/info.inc';
