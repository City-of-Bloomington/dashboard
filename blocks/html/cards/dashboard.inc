<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
foreach ($this->cards as $c) {
    $id          = $c->getId();
    $description = parent::escape($c->getDescription());
    $target      = parent::escape($c->getTarget());
    $units       = parent::escape($c->getUnits());
    $entry       = $c->getLastLogEntry();
    $value       = parent::escape($entry['value']);
    $status      = $c->getStatus ($entry['value']);
    $uri         = parent::generateUri('cards.view', ['id'=>$c->getId()]);
    echo "
    <article class=\"card $status\" id=\"card_$id\">
        <header>
            <h1><a href=\"$uri\">$description</a></h1>
        </header>
        <div class=\"value\">$value$units ({$entry['timestamp']->format(DATETIME_FORMAT)})</div>
        <div class=\"target\">{$this->_('target')}: $target$units</div>
    </article>
    ";
}
