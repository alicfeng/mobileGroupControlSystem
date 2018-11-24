#!/bin/bash
php artisan config:cache || {
	echo -e "configure reload failed";
	exit 1;
}
php artisan route:cache || {
	echo -e "route reload failed";
	exit 1;
}
php artisan optimize || {
	echo -e "optimize failed";
	exit 1;
}
