#!/bin/bash
DASHBOARD={{ dashboard_install_path }}
PHP=/usr/bin/php

$PHP $DASHBOARD/scripts/updateCardValues.php
