<nav id="nav1">
    <div class="container">
    <?php
        $home = parent::generateUri('home');
        echo "<a href=\"$home\">{$this->_('home')}</a>";

        use Application\Models\GroupsTable;
        use Blossom\Classes\Url;

        $url = new Url(Url::current_url());

        $table = new GroupsTable();
        $list  = $table->find();
        foreach ($list as $g) {
            $url   = $home.'?group_id='.$g->getId();
            $name  = parent::escape($g->getName());
            echo "<a href=\"$url\" class=\"{$g->getCSSClass()}\">$name</a>";
        }
    ?>
    </div>
</nav>
