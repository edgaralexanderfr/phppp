build: clean build-dist link-headers build-lib

build-dist:
	mkdir dist/bundle
	mkdir dist/bundle/php-types
	mkdir dist/bundle/php-io
	mkdir dist/bundle/php-http
	cp -r ../php-types/src dist/bundle/php-types/src
	cp -r ../php-io/src dist/bundle/php-io/src
	cp -r ../php-http/src dist/bundle/php-http/src
	cp ../php-types/autoload.php dist/bundle/php-types/autoload.php
	cp ../php-io/autoload.php dist/bundle/php-io/autoload.php
	cp ../php-http/autoload.php dist/bundle/php-http/autoload.php

link-headers: link-php-types-header link-php-io-header link-php-http-header link-phppp-header

link-php-types-header:
	echo '<?php'                                                    > dist/php-types
	echo ''                                                        >> dist/php-types
	echo "require_once __DIR__ . '/bundle/php-types/autoload.php';" >> dist/php-types

link-php-io-header:
	echo '<?php'                                                    > dist/php-io
	echo ''                                                        >> dist/php-io
	echo "require_once __DIR__ . '/bundle/php-io/autoload.php';"    >> dist/php-io

link-php-http-header:
	echo '<?php'                                                    > dist/php-http
	echo ''                                                        >> dist/php-http
	echo "require_once __DIR__ . '/bundle/php-http/autoload.php';"  >> dist/php-http

link-phppp-header:
	echo '<?php'                                                    > dist/phppp
	echo ''                                                        >> dist/phppp
	echo "require_once __DIR__ . '/php-types';"                    >> dist/phppp
	echo "require_once __DIR__ . '/php-io';"                       >> dist/phppp
	echo "require_once __DIR__ . '/php-http';"                     >> dist/phppp

build-lib:
	cp -r ../php-types/lib/php-types.phar lib/php-types.phar
	cp -r ../php-io/lib/php-io.phar lib/php-io.phar
	cp -r ../php-http/lib/php-http.phar lib/php-http.phar

clean:
	
	rm -rf dist
	rm -rf lib
	mkdir dist
	mkdir lib