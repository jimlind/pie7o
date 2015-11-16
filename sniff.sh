echo '**** CodeSniffing Source'
vendor/bin/phpcs src --standard=vendor/escapestudios/symfony2-coding-standard/Symfony2/ruleset.xml -p --colors
vendor/bin/phpcs src --standard=PSR2 -p --colors

echo '**** CodeSniffing Tests'
vendor/bin/phpcs tests --standard=vendor/escapestudios/symfony2-coding-standard/Symfony2/ruleset.xml -p --colors
vendor/bin/phpcs tests --standard=PSR2 -p --colors

echo '**** CodeSniffing Examples'
vendor/bin/phpcs example* --standard=vendor/escapestudios/symfony2-coding-standard/Symfony2/ruleset.xml -p --colors
vendor/bin/phpcs example* --standard=PSR2 -p --colors

echo '**** Dry Run of PHP CodeSniffer Fixer'
vendor/bin/php-cs-fixer fix . --level=symfony --dry-run