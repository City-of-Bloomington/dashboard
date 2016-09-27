<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
?>
<section>
    <header>
        <h1><?= $this->_(['card', 'cards', count($this->cards)]); ?></h1>
    </header>
    <?php
        foreach ($this->cards as $c) {
            $id          = $c->getId();
            $description = parent::escape($c->getDescription());
            $target      = parent::escape($c->getTarget());
            $entry       = $c->getLastLogEntry();
            $value       = parent::escape($entry['value']);
            $status      = $c->getStatus ($entry['value']);
            $uri         = parent::generateUri('cards.view', ['id'=>$c->getId()]);
            echo "
            <article class=\"card $status\" id=\"card_$id\">
                <header>
                    <h1><a href=\"$uri\">$description</a></h1>
                </header>
                <div class=\"value\">$value ({$entry['timestamp']->format(DATETIME_FORMAT)})</div>
                <div class=\"target\">{$this->_('target')}: $target</div>
            </article>
            ";
        }
    ?>
</section>
