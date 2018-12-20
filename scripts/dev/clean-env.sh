#!/bin/bash

WORKDIR=`pwd`

echo "Working directory: $WORKDIR"
# php artisan server
ARTISAN="${WORKDIR}/artisan"

echo "artisan: $ARTISAN"
PHP=`which php`

${PHP} ${ARTISAN} clear-compiled
${PHP} ${ARTISAN} config:clear
${PHP} ${ARTISAN} route:clear
${PHP} ${ARTISAN} view:clear
${PHP} ${ARTISAN} cache:clear
${PHP} ${ARTISAN} migrate

composer dump-autoload -o
#composer run-script post-autoload-dump