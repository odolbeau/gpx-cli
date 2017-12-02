.PHONY: ${TARGETS}
.DEFAULT_GOAL := help

define say =
    echo "$1"
endef

define say_red =
    echo "\033[31m$1\033[0m"
endef

define say_green =
    echo "\033[32m$1\033[0m"
endef

define say_yellow =
    echo "\033[33m$1\033[0m"
endef

define say_cyan =
    echo "\033[1m\033[36m$1\033[0m\033[21m"
endef

help:
	@$(call say_yellow,"Usage:")
	@$(call say,"  make [command]")
	@$(call say,"")
	@$(call say_yellow,"Available commands:")
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%s\033[0m___%s\n", $$1, $$2}' | column -ts___

install: ## Install project
	@$(call say_cyan,"==\> Install Composer dependencies")
	@composer install -n

cs-fix: ## Fix coding standard
	@vendor/bin/php-cs-fixer fix src/ --rules=@Symfony,declare_strict_types --allow-risky=yes
	@vendor/bin/php-cs-fixer fix tests/ --rules=@Symfony,declare_strict_types --allow-risky=yes

cs-lint: ## Lint php code source
	@$(call say_cyan,"==\> Check style")
	@vendor/bin/php-cs-fixer fix src/ --dry-run --diff --rules=@Symfony,declare_strict_types --allow-risky=yes
	@vendor/bin/php-cs-fixer fix tests/ --dry-run --diff --rules=@Symfony,declare_strict_types --allow-risky=yes

test: cs-lint ## Launch tests
	@$(call say_cyan,"==\> Launch unit tests")
	@vendor/bin/phpunit
