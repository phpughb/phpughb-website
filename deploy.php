<?php

namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'phpughb-website');

// Project repository
set('repository', 'git@github.com:scriptibus/phpughb-website.git');

// Set env
set('env', ['APP_ENV' => 'prod']);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', []);
set('shared_dirs', ['var']);


// Hosts
host('phpughb.lucasnothnagel.de')
    ->stage('production')
    ->user('phpughb')
    ->set('deploy_path', '~/serve/production');


// Tasks
task('deploy:create_cache_dir', function () {
    // Set cache dir
    set('cache_dir', '{{release_path}}/'.'/var/cache');

    // Set rights
    run('/usr/bin/php {{release_path}}/bin/console cache:warmup --env=prod');
    run('chmod -R g+w {{cache_dir}}');
})->desc('Create cache dir');

//Reset cache
task('cache:reset', function () {
    run('/usr/bin/php {{release_path}}/bin/console cache:clear --env=prod');
    run('/usr/bin/php {{release_path}}/bin/console cache:warmup --env=prod');
})->desc('Clear and warm cache');

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:create_cache_dir',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success',
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
