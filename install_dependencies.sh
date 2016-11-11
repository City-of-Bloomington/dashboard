#!/bin/bash
APPNAME=dashboard
DIR=`pwd`

declare -a dependencies=(node composer)
for i in "${dependencies[@]}"; do
    command -v $i > /dev/null 2>&1 || { echo "$i not installed" >&2; exit 1; }
done

composer update

cd $DIR/data/Themes/COB
composer update

cd $DIR/data/Themes/COB/vendor/City-of-Bloomington/factory-number-one
npm update

cd $DIR
