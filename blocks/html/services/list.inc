<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->services
 */
use Application\Models\Person;

$helper = $this->template->getHelper('buttonLink');
?>
<section>
    <header>
        <h1><?= $this->_(['service', 'services', count($this->services)]); ?></h1>
        <div class="tools">
        <?php
            if (Person::isAllowed('services', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('services.update'),
                    $this->_('service_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
        <thead>
            <tr><th><?= $this->_('name' ); ?></th>
                <th><?= $this->_('class'); ?></th>
                <th><?= $this->_('url'  ); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $fields = ['id', 'name', 'class', 'url'];
            foreach ($this->services as $s) {
                foreach ($fields as $f) {
                    $get = 'get'.ucfirst($f);
                    $$f  = self::escape($s->$get());
                }

                $uri = parent::generateUri('services.view', ['id'=>$s->getId()]);

                echo "
                <tr><td><a href=\"$uri\">$name</a></td>
                    <td>$class</td>
                    <td>$url</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
</section>