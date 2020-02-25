<?php

namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'phpughb-website');

// Project repository
set('repository', 'git@github.com:phpughb/phpughb-website.git');

// Keep .env.local
set('shared_files', ['.env.local']);

// Set env
set('env', ['APP_ENV' => 'prod']);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Hosts
host('phpughb.lucasnothnagel.de')
    ->stage('production')
    ->user('phpughb')
    ->set('deploy_path', '~/serve/production')
;

// Tasks
task('deploy:js:install:vendors', function () {
    run('cd {{release_path}} && yarn install');
})->desc('Install js vendors');

task('deploy:js:compile:assets', function () {
    run('cd {{release_path}} && yarn encore production');
})->desc('Encore Compile Assets');

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
    'deploy:shared',
    'deploy:vendors',
    'deploy:create_cache_dir',
    'deploy:js:install:vendors',
    'deploy:js:compile:assets',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success',
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
