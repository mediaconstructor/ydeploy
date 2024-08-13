<?php

namespace Deployer;

desc('Prepare the next release in local subdir ".build"');
task('build', static function () {
    set('deploy_path', getcwd() . '/.build');
    info('Building in ' . getcwd() . '/.build');
    set('keep_releases', 1);

    invoke('build:info');
    invoke('build:setup');
    invoke('build:assets');
    invoke('deploy:clear_paths');
})->once(true);
