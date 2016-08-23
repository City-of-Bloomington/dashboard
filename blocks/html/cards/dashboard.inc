<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
$this->template->addToAsset('scripts', BASE_URI.'/js/cards/list.js');
?>
<section>
    <header>
        <h1><?= $this->_(['card', 'cards', count($this->cards)]); ?></h1>
    </header>
    <?php
        foreach ($this->cards as $c) {
            $id          = $c->getId();
            $description = self::escape($c->getDescription());
            $uri         = self::generateUri('cards.view', ['id'=>$id]);
            $queryUrl    = $c->getQueryUrl();
            echo "
            <article class=\"card\" id=\"card_$id\"
                    data-queryUrl=\"$queryUrl\"
                    data-target=\"{$c->getTarget()}\"
                    data-comparison=\"{$c->getComparison()}\">
                <header>
                    <h1><a href=\"$uri\">$description</a></h1>
                </header>
            </article>
            ";
        }
    ?>
</section>
