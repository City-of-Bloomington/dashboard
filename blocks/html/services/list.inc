<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->services
 */
?>
<section>
    <h1><?= $this->_(['service', 'services', count($this->services)]); ?></h1>
    <?php
        print_r($this->services);
    ?>
</section>