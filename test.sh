#!/bin/bash
#
# This file is part of the Altumo library.
# 
# (c) Steve Sperandeo <steve.sperandeo@altumo.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.


declare -x ALTUMO_TEST_ROOT="`pwd`/source/php"

if [ -z "$ALTUMO_TEST_SERVER" ]; then
    echo -e "Using: localhost - Set ALTUMO_TEST_SERVER to specify a different test server.\n"
    declare -x ALTUMO_TEST_SERVER="localhost"
fi

find $ALTUMO_TEST_ROOT -regex '^.*Test.*$' -exec phpunit --colors {} \;
