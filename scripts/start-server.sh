#!/usr/bin/env bash

function usage() {

    cat << EOF >&2
Usage:

start-server.sh [-p] [-h]

-p|--port Port the server should listen on (defaults to 8000)
-h|--host The hosts ip (defaults to 0.0.0.0)

EOF

    exit 1
}

port=8000
host="0.0.0.0"
while [ $# != "" ]
 do
    case "$1" in
        -p) port="$2" ; shift ;;
        -h) host="$2" ; shift ;;
        --) shift; break ;;
        -*) usage ;;
        *) break ;;
    esac
    shift
done

[ "$port" = "" ] && usage
[ "$host" = "" ] && usage


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

${PHP} ${ARTISAN} serve --host=${host} --port=${port}
