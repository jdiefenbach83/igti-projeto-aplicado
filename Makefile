SHELL := /bin/bash

tests:
	echo "Dropping database..."
	symfony console doctrine:database:drop --force --if-exists --no-debug --env=test

	echo "Creating database..."
	symfony console doctrine:database:create --no-debug --env=test

	echo "Running migrations fixtures..."
	symfony console doctrine:migrations:migrate --no-interaction --no-debug --env=test

	echo "Loading fixtures..."
	symfony console doctrine:fixtures:load -n --env=test

	echo "Executing tests..."
	symfony php vendor/bin/simple-phpunit

	echo "Validating architecture..."
	php vendor/bin/deptrac
.PHONY: tests
