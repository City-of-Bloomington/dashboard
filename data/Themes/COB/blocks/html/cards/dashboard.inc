<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
$this->template->addToAsset('scripts', $this->theme_config['GOOGLE_CHARTS']);
$this->template->addToAsset('scripts', BASE_URI.'/COB/js/sparklines.js');
#$this->template->addToAsset('scripts', BASE_URI.'/COB/js/chartjs/Chart.min.js');

include APPLICATION_HOME.'/blocks/html/cards/dashboard.inc';