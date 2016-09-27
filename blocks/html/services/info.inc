<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Service $this->service
 */
use Application\Models\Person;

$fields = ['id', 'name', 'class', 'url'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->service->$get());
}
$methods = $this->service->getMethods();
?>
<section>
    <header>
        <h1><?= $name; ?></h1>
        <div class="tools">
        <?php
            if (Person::isAllowed('services', 'edit')) {
                $helper = $this->template->getHelper('buttonLink');
                echo $helper->buttonLink(
                    parent::generateUri('services.update', ['id'=>$id]),
                    $this->_('service_edit'),
                    'edit'
                );
            }
        ?>
        </div>

        <table>
        <tr><th><?= $this->_('class'); ?></th><td><?= $class; ?></td></tr>
        <tr><th><?= $this->_('url'  ); ?></th><td><?= $url;   ?></td></tr>
        </table>

        <?php
            foreach ($methods as $n => $method) {
                echo "<fieldset><legend>$n</legend>";
                foreach ($method['parameters'] as $param=>$value) {
                    echo "<dl><dt>$param</dt><dd>$value</dd></dt></dl>";
                }
                echo "</fieldset>";
            }
        ?>

    </header>
</section>