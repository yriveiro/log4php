Commands
===
* Update dependencies: `composer update`
* Run tests: `./vendor/run/phpunit`
* PHP Lint: `./vendor/bin/parallel-lint src/main`
* PHP Stan: `./vendor/bin/phpstan analyze --level 5 src`
* Check code style: `./vendor/bin/phpcs --standard=PSR2 src/main`

To do
===
* Add phing build test to run all tasks automatically
* Move tests into namespaces
* Extend test to cover all levels from PSR
* ~~Use latest PHP Unit~~
* ~~Fix last 4 test cases~~
* Add PSR-7 Compatibility per default
* Profile
* Remove exclusive locking as much as possible
* Create YAML adapter
* Think about storing important things in APCu
* Add compat Logger & LoggerLevel for simple migration from KLogger & Log4Php 2
* Builder pattern for log configuration
* Add pakcagist integration
* Add test for message interpolation and exception tracing