#!/bin/bash

function echo_and_run() {
	echo ""
	echo -e "\033[1m$1\033[0m"
	$1
}

action=$1
type=$2
port=$3

wrong_usage=0
if [ ! -f ".projectroot_framecalendar" ]; then
	echo "Please run deploy.sh in project root."
	wrong_usage=1
fi

if !( [ "$action" == "build" ] || [ "$action" == "start" ] || [ "$action" == "deploy" ] ); then
	echo "Please specify action: build, start or deploy"
	wrong_usage=1
fi

if !( [ "$type" == "dev" ] || [ "$type" == "prod" ] ); then
	echo "Please specify type: dev or prod"
	wrong_usage=1
fi

number_regex='^[0-9]+$'
if !( [ "$port" != "" ] && [[ "$port" =~ $number_regex ]] && [ "$port" -ge 1024 ] && [ "$port" -le 65536 ] ); then
	echo "Please specify port: 1024-65536"
	wrong_usage=1
fi

if [ "$wrong_usage" == "1" ]; then
	echo
	echo "Usage: bash podman/deploy.sh build|start|deploy prod|dev PORT"
	exit
fi

#
# CODE
#

if [ "$action" == "build" ] || [ "$action" == "deploy" ]; then
	echo_and_run "podman build -t framecalendar-mariadb -f images/mariadb/Containerfile ."
	echo_and_run "podman build -t framecalendar-apache -f images/apache/Containerfile ."
fi

if [ "$action" == "start" ] || [ "$action" == "deploy" ]; then
	if [ ! -f "podman/config/mariadb.env" ] || [ ! -f "podman/config/apache.env" ]; then
		# Checked if only one env file doesn't exists, and if so, creating a new pair.
		if [ -f "podman/config/mariadb.env" ]; then echo_and_run "mv podman/config/mariadb.env podman/config/mariadb.env.old"; fi
		if [ -f "podman/config/apache.env" ]; then echo_and_run "mv podman/config/apache.env podman/config/apache.env.old"; fi

		gen_mariadb_host="127.0.0.1"
		gen_mariadb_random_root_password="1"
		gen_mariadb_user="framecalendar"
		gen_mariadb_password=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 64)
		gen_mariadb_database="framecalendar"

		echo
		echo -e "\033[1mNew configuration:\033[0m"
		echo "FRAMECALENDAR_MARIADB_HOST   = $gen_mariadb_host"
		echo "MARIADB_RANDOM_ROOT_PASSWORD = $gen_mariadb_random_root_password"
		echo "MARIADB_USER                 = $gen_mariadb_user"
		echo "MARIADB_PASSWORD             = $gen_mariadb_password"
		echo "MARIADB_DATABASE             = $gen_mariadb_database"

		echo "FRAMECALENDAR_MARIADB_HOST=$gen_mariadb_host" >> podman/config/apache.env
		echo "FRAMECALENDAR_MARIADB_USER=$gen_mariadb_user" >> podman/config/apache.env
		echo "FRAMECALENDAR_MARIADB_PASSWORD=$gen_mariadb_password" >> podman/config/apache.env
		echo "FRAMECALENDAR_MARIADB_DATABASE=$gen_mariadb_database" >> podman/config/apache.env

		echo "MARIADB_RANDOM_ROOT_PASSWORD=$gen_mariadb_random_root_password" >> podman/config/mariadb.env
		echo "MARIADB_USER=$gen_mariadb_user" >> podman/config/mariadb.env
		echo "MARIADB_PASSWORD=$gen_mariadb_password" >> podman/config/mariadb.env
		echo "MARIADB_DATABASE=$gen_mariadb_database" >> podman/config/mariadb.env
	fi

	echo_and_run "podman pod create --replace -p $port:80 framecalendar"
	echo_and_run "podman container create --replace --pod framecalendar --name framecalendar-mariadb --env-file podman/config/mariadb.env -v framecalendar-mariadb:/var/lib/mysql:Z framecalendar-mariadb:latest"

	if [ "$type" == "prod" ]; then
		echo_and_run "podman container create --replace --pod framecalendar --name framecalendar-apache --env-file podman/config/apache.env framecalendar-apache:latest"
	elif [ "$type" == "dev" ]; then
		echo_and_run "podman container create --replace --pod framecalendar --name framecalendar-apache --env-file podman/config/apache.env -v ./webserver/:/var/www/framecalendar/:Z framecalendar-apache:latest"
	fi

	echo_and_run "podman pod start framecalendar"
fi
