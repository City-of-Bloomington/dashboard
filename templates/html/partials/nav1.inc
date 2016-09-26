<nav id="nav1">
    <div class="container">
    <?php
        /**
         * Navigation items are defined in /routes.inc
         *
         * We pulled them out so that theme designers could override this
         * file without worrying about changes to the navigation items
         * over time.
         */
        $links = [
            ['msgid'=>'home', 'uri'=>self::generateUri('home')]
        ];

        foreach ($links as $item) {
            echo "<a href=\"$item[uri]\">{$this->_($item['msgid'])}</a>";
        }
    ?>
    </div>
</nav>
