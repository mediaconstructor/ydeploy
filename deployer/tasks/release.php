<?php

namespace Deployer;

desc('Release locally prepared release to server');
task('release', [
    'deploy:info',
    'deploy:setup',
    'deploy:lock',
//    'deploy:release',
//    'deploy:symlink',
    'deploy:release',
    'deploy:copy_dirs',
    'upload',
    'deploy:shared',
    'deploy:dump_info',
    'deploy:writable',
    'deploy:vendors',
    'deploy:redaxo_vendor',
    'setup',
    'database:migration',
//    'deploy:symlink',
//    'server:clear_cache',
//    'deploy:unlock',
//    'deploy:cleanup',
//    'deploy:success',
    'deploy:publish',
]);

before('deploy:cleanup', 'server:clear_cache');
