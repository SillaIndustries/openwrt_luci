#!/bin/bash
#
# build-po.sh - Helper to build a po file to machine code
#

set -eu

# Prepare "po2lmo", we need it to compile language files
[ -e modules/luci-base/src/po2lmo ] || make -C modules/luci-base/src po2lmo

pathfile="$1"

echo "Compiling '$pathfile' to LMO format..."

# modules/luci-base/po/it/base.po -> base.po
local_file=${pathfile##*/po/*/}
# echo ".. local_file = $local_file"

# modules/luci-base/po/it/base.po -> modules/luci-base/po/it
local_path=${pathfile%/*}
# echo ".. local_path_base = $local_path"

# modules/luci-base/po/it -> it
lang=${local_path##*/po/}
# echo ".. lang = $lang"

# base.po -> base
poname=${local_file%.po}
# echo ".. poname = $poname"

build_file="$poname.po.$lang.lmo"
# echo ".. build_file = $build_file"

mkdir -p .lmobuild

modules/luci-base/src/po2lmo "$pathfile" .lmobuild/$build_file
