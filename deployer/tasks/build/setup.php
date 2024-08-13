<?php

namespace Deployer;

use Deployer\Task\Context;
use RuntimeException;

desc('Prepare build step');
task('build:setup', static function () {
    if ('local' !== Context::get()->getHost()->getAlias()) {
        throw new RuntimeException('Task "build" can only be called on host "local"');
    }

    run('rm -rf {{release_path}}');
    run('mkdir -p {{release_path}}');

    invoke('deploy:update_code');
});
