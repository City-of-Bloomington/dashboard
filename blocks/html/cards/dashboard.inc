<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->cards
 */
?>
<section class="dashboard">
<?php
    foreach ($this->cards as $c) {
        $this->card = $c;
        $this->_include('cards/card.inc');
    }
?>
</section>
