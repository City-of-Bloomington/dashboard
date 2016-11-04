#!/bin/bash
DIR=`pwd`

declare -a dependencies=(node-sass node npm composer)
for i in "${dependencies[@]}"; do
    command -v $i > /dev/null 2>&1 || { echo "$i not installed" >&2; exit 1; }
done

composer update

cd $DIR/vendor/City-of-Bloomington/factory-number-one
npm update
./gulp

cd $DIR/public/css
./build_css.sh
