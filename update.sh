#!/bin/bash

CHMOD_FILES=0460
CHMOD_DIRECTORIES=2570
CHMOD_PROJECT=0700
OWNER_USER=www-data
OWNER_GROUP=www-developer

CLI_USER=root
CLI_GROUP=root
CHMOD_CLI=0700

CLIENT_USER=root
CLIENT_GROUP=root
CHMOD_CLIENT=0700

function usageParam() {
  echo -e "  \e[32m$1\e[33m $2"
  echo -e "      \e[39m$3"
  if [ $4 != ".nodefault" ]; then
    echo -e "      Default: \e[94m$4"
  fi
  echo -e "\e[39m"
}

function usage() {
  echo -e "\e[41m --  DYNDNS UPDATER USAGE -- \e[49m"
  echo -e ""
  echo -e "Parameters:"
  usageParam "-ou|--ouser" "USER" "Owner user for web files and directories" $OWNER_USER
  usageParam "-og|--ogroup" "GROUP" "Owner group for web files and directories" $OWNER_GROUP
  usageParam "-cu|--cuser" "USER" "Owner user for CLI script files and directory" $CLI_USER
  usageParam "-cg|--cgroup" "GROUP" "Owner group for CLI script files and directory" $CLI_GROUP
  usageParam "-du|--duser" "USER" "Owner user for client script files and directory" $CLIENT_USER
  usageParam "-dg|--dgroup" "GROUP" "Owner group for client script files and directory" $CLIENT_GROUP
  usageParam "-mf|--mfile" "MODE" "Permission mode for web files" $CHMOD_FILES
  usageParam "-md|--mdir" "MODE" "Permission mode for web directories" $CHMOD_DIRECTORIES
  usageParam "-mp|--mproject" "MODE" "Permission mode for project files (e.g. .gitignore)" $CHMOD_PROJECT
  usageParam "-mc|--mcli" "MODE" "Permission mode for CLI scripts" $CHMOD_CLI
  usageParam "-md|--mclient" "MODE" "Permission mode for client scripts" $CHMOD_CLIENT
  usageParam "-h|--help" "" "Show this usage help" ".nodefault"
  echo -e ""
}

while [ "$1" != "" ]; do
    case $1 in
        -mf | --mfile )         shift
                                CHMOD_FILES=$1
                                ;;
        -md | --mdir )          shift
                                CHMOD_DIRECTORIES=$1
                                ;;
        -mp | --mproject )      shift
                                CHMOD_PROJECT=$1
                                ;;
        -mc | --mcli )          shift
                                CHMOD_CLI=$1
                                ;;
        -md | --mclient )       shift
                                CHMOD_CLIENT=$1
                                ;;
        -ou | --ouser )         shift
                                OWNER_USER=$1
                                ;;
        -og | --ogroup )        shift
                                OWNER_GROUP=$1
                                ;;
        -cu | --cuser )         shift
                                CLI_USER=$1
                                ;;
        -cg | --cgroup )        shift
                                CLI_GROUP=$1
                                ;;
        -du | --duser )         shift
                                CLIENT_USER=$1
                                ;;
        -dg | --dgroup )        shift
                                CLIENT_GROUP=$1
                                ;;
        -h | --help )           usage
                                exit
                                ;;
        * )                     usage
                                exit 1
    esac
    shift
done

# args: text, back, text
function printWithColor() {
  echo -e "\e[$2m\e[$3m[updater] $1\e[49m\e[39m"
}

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
CW_DIR=`pwd`

printWithColor "Switching to $SCRIPT_DIR..." 46 30
cd $SCRIPT_DIR
printWithColor "git pull" 45 30
git pull
printWithColor "Set owners" 45 30
printWithColor "Owner user: $OWNER_USER" 103 30
printWithColor "Owner group: $OWNER_GROUP" 103 30
printWithColor "Owner of CLI scripts: ${CLI_USER}:${CLI_GROUP}" 103 30
printWithColor "Owner of client scripts: ${CLIENT_USER}:${CLIENT_GROUP}" 103 30
chown -R $OWNER_USER:$OWNER_GROUP .
chown -R $CLI_USER:$CLI_GROUP ./cli
chown -R $CLIENT_USER:$CLIENT_GROUP ./client
printWithColor "Set permissions" 45 30
printWithColor "Mode for files: $CHMOD_FILES" 103 30
printWithColor "Mode for directories: $CHMOD_DIRECTORIES" 103 30
printWithColor "Mode for CLI scripts: $CHMOD_CLI" 103 30
printWithColor "Mode for client scripts: $CHMOD_CLIENT" 103 30
printWithColor "Mode for project files: $CHMOD_PROJECT" 103 30
find . -type f -exec chmod $CHMOD_FILES {} \;
find . -type d -exec chmod $CHMOD_DIRECTORIES {} \;
find cli -type f -exec chmod $CHMOD_CLI {} \;
find client -type f -exec chmod $CHMOD_CLIENT {} \;
chmod $CHMOD_PROJECT .gitignore update.sh
chmod +x update.sh
printWithColor "Switching back to $CW_DIR..." 46 30
cd $CW_DIR
printWithColor "Ready." 42 30
