#!/bin/bash
#
# build-less.sh - Helper to build less templates into css
#

set -eu

project_root=$1
web_root=$2
target=${3%%.less}

cd $project_root

./node_modules/.bin/lessc \
  $web_root/less/$target.less > $web_root/css/$target.css
