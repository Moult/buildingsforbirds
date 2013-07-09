# Buildings for Birds

Some buildings were just meant for birds.

# Installation

 1. `git submodule update --init --recursive`
 2. Verify `RewriteBase /` in `.htaccess`
 3. Verify `application/logs/`, `application/cache/`, and `uploads/` are writeable
 4. Configure everything in `application/config/*`
 5. Configure `application/bootstrap.php`

## Development

 1. `curl -s https://getcomposer.org/installer | php`
 2. `php composer.phar install --dev`
 3. `vim behat.yml`
 4. `vim build.xml`

`bin/behat` and `bin/phpspec` is now available to you. PHPSpec2 is set to load
classes in `application/classes/`.

`phing -projecthelp` lists all tools.

# Licenses

This software is open-source and free software. See `licenses/` for full text.
