SHELL=sh
.DEFAULT_GOAL := help

APP_NAME = lawetago-b2b-php
DOCKER_EXEC = docker exec -it ${APP_NAME}

COMPOSE_FILE = docker compose -f compose.yaml

help:
	@printf "\n%s\n________________________________________________\n" $(shell basename ${APP_NAME})
	@printf "\n\033[32mAvailable commands:\n\033[0m"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep  | sed -e 's/\\$$//' | sed -e 's/##//' | awk 'BEGIN {FS = ":"}; {printf "\033[33m%s:\033[0m%s\n", $$1, $$2}'

setup: ## Setup local development environment
	cp -n .env .env.local || true
	${COMPOSE_FILE} up -d
	make install

rebuild: 	## Rebuild docker images - without composer install
	${COMPOSE_FILE} build --no-cache
	${COMPOSE_FILE} up -d --force-recreate

stop: ## Stop docker containers
	${COMPOSE_FILE} stop

start: ## Start docker containers
	${COMPOSE_FILE} start

shell: ## Run container shell
	${DOCKER_EXEC} sh

install:	## Install dependencies
	${DOCKER_EXEC} composer install

analyze: ## Run static analyze
	${DOCKER_EXEC} composer analyze

cs-fix: 	## Apply source code coding standards
	${DOCKER_EXEC} composer cs-fix

test:		## Run tests
	${DOCKER_EXEC} composer test

cmd: ## Run command in container `make cmd CMD="composer test"`
	${DOCKER_EXEC} ${CMD}

.PHONY: help setup rebuild stop start shell install test cmd
