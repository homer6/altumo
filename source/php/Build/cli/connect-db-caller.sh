#!/bin/bash

SCRIPT=$(readlink -f $0)
SCRIPTPATH=`dirname $SCRIPT`
PHP_BIN=`which php`


$PHP_BIN $SCRIPTPATH/connect-database.php
$SCRIPTPATH/connect-database.sh