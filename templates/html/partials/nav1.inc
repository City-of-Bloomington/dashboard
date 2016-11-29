<nav id="nav1">
    <div class="container">
    <?php
        use Application\Models\GroupsTable;
        use Blossom\Classes\Url;

        $url = new Url(Url::current_url());

        $table = new GroupsTable();
        $list  = $table->find();
        foreach ($list as $g) {
            $url   = parent::generateUri('home').'?group_id='.$g->getId();
            $name  = parent::escape($g->getName());
            echo "<a href=\"$url\" class=\"{$g->getCSSClass()}\">$name</a>";
        }
    ?>
    </div>
</nav>
