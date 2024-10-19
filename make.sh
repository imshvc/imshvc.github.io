#!/usr/bin/sh

rm -rf public
mkdir public
php gen-pages.php
cp -r static/* public
