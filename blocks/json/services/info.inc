<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Service $this->service
 */
$out = [
    'id'       => (int)$this->service->getId(),
    'name'     => $this->service->getName(),
    'class'    => $this->service->getClass(),
    'base_url' => $this->service->getUrl(),
    'methods'  => $this->service->getMethods()
];
echo json_encode($out, JSON_PRETTY_PRINT);
