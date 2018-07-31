<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->services
 */
$out = [];
foreach ($this->services as $s) {
    $out[$s->getName()] = [
        'id'       => (int)$s->getId(),
        'base_url' => $s->getUrl(),
        'class'    => $s->getClass(),
        'methods'  => $s->getMethods()
    ];
}
echo json_encode($out, JSON_PRETTY_PRINT);
