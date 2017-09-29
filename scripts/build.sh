#!/usr/bin/env sh

# install required packages

PACKAGES=`printf "%s " $(cat build/freebsd_packages)`

pkg install -y ${PACKAGES}

#for package in $(cat build/freebsd_packages); do
#    echo "pkg install ${package}"
#done

# is php here?
if ! [ -x "$(command -v php)" ]; then
  echo 'Error: PHP is not installed.' >&2
  exit 1
fi
# is npm here?
if ! [ -x "$(command -v npm)" ]; then
  echo 'Error: PHP is not installed.' >&2
  exit 1
fi
# is php version sufficient?
PHPVERSION=`php -r 'if(version_compare(PHP_VERSION, "7.1.0") >= 0) { echo "1"; } else { echo "0"; }'`
if [ $PHPVERSION -lt 1 ]; then
    echo "Current PHP version not sufficient. Will exit here."
    exit 1
fi
