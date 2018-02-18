#!/usr/bin/env bash
branch_name="master"

# Usage info
show_help() {
cat << EOF
Usage: ${0##*/} [-h] [-b BRANCH] [BRANCHNAME]...
    do a git checkout --force BRANCHNAME, clear cache and update composer.
    Any modifications did on versionned files wil be lost.
    Not versionned files will be keept.

    -h      display this help and exit
    -b      git checkout --force BRANCHNAME, default origin/master

EOF
}

# parse options, select branch
while getopts hb: opt; do
    case $opt in
        h) show_help exit 0 ;;
        b) branch_name=$OPTARG;;
        *) show_help >&2 exit 1;;
    esac
done

## get current folder
d=$(cd -P -- "$(dirname -- "$0")" && pwd -P)


#warning
while true; do
    read -p "$d will be updated on $branch_name [y/n] " yn
    case $yn in
        [Yy]* ) cd "$d" && pwd; break;;
        [Nn]* ) exit; break;;
        * ) echo "Please answer yes or no.";;
    esac
done

# Update sources
git checkout --force $branch_name
git pull origin $branch_name

# Cleanup
rm -rf var/cache/prod/*

# Update vendors
php composer.phar self-update
php composer.phar install

# Update and build assets
npm install
npm run build

# End
cd -
