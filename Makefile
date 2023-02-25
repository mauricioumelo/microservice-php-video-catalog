#!/usr/bin/make

SHELL = /bin/sh

WWWUSER := $(shell id -u)
WWWGROUP := $(shell id -g)

export WWWUSER
export WWWGROUP

config-project: step1 step2 step3 step4 step5
step1:
	docker compose up -d --build
	
step2:
	docker compose exec app composer install	
	
step3:
	if [ -f ~/.zshrc ]; then \
		if ! grep "alias sail" ~/.zshrc ; \
		then\
			echo "\nalias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'" >> ~/.bashrc ; \
		fi; \
	elif [ -f ~/.bashrc ]; then \
		if !grep "alias sail"  ~/.zshrc ; \
		then\
			echo "\nalias sail='[  -f sail ] && sh sail || sh vendor/bin/sail'" >> ~/.zshrc ; \
		fi; \
	fi

step4:
	vendor/bin/sail npm i

step5: 
	vendor/bin/sail npx husky install