SHELL := /bin/bash

ENV ?= test

basics:
	echo "Dropping database..."
	symfony console doctrine:database:drop --force --if-exists --no-debug --env=$(ENV)

	echo "Creating database..."
	symfony console doctrine:database:create --no-debug --env=$(ENV)

	echo "Running migrations..."
	symfony console doctrine:migrations:migrate --no-interaction --no-debug --env=$(ENV)

	echo "Loading fixtures..."
	symfony console doctrine:fixtures:load -n --env=$(ENV)

	echo "Executing tests..."
	symfony php vendor/bin/simple-phpunit

	echo "Validating architecture..."
	php vendor/bin/deptrac
.PHONY: basics
