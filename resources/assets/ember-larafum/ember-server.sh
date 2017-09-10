#!/usr/bin/env bash

CURDIR=`pwd`

echo "Running in ${CURDIR}"
echo ""

echo "Deleting dist, tmp for rebuilding"
rm -rf ${CURDIR}/dist ${CURDIR}/tmp

echo "Starting server"

ember serve
