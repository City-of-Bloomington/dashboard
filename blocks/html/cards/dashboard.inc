<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
$this->_include('cards/partials/groupCSS.inc');

echo '<section class="dashboard">';
    foreach ($this->cards as $c) {
        $this->card = $c;
        $this->_include('cards/card.inc');
    }
echo '</section>';
