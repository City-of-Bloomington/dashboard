<nav id="nav1">
    <div class="container">
    <?php
        use Application\Models\GroupsTable;

        $table = new GroupsTable();
        $list  = $table->find();
        foreach ($list as $g) {
            $url = BASE_URL."?group_id={$g->getId()}";

            $name  = parent::escape($g->getName());
            echo "<a href=\"$url\" class=\"{$g->getCSSClass()}\">$name</a>";
        }
    ?>
    </div>
</nav>
