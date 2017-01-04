<nav id="nav1">
    <div class="container">
    <?php
        use Application\Models\GroupsTable;
        use Blossom\Classes\Url;

        $current_url = new Url(Url::current_url());

        $table = new GroupsTable();
        $list  = $table->find();
        foreach ($list as $g) {
            $url = clone($current_url);
            $url->group_id = $g->getId();

            $name  = parent::escape($g->getName());
            echo "<a href=\"$url\" class=\"{$g->getCSSClass()}\">$name</a>";
        }
    ?>
    </div>
</nav>
