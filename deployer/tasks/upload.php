<?php

namespace Deployer;

desc('Upload locally prepared release to server');
task('upload', static function () {
    $source = host('local')->get('release_path');

    upload($source . '/', '{{release_path}}', [
        'flags' => '-rltz',
        'options' => [
            '--executability',
            '--exclude', '.cache',
            '--exclude', '.git',
            '--exclude', '.tools',
            '--exclude', 'deploy.php',
            '--exclude', 'node_modules',
            '--delete',
        ],
    ]);
});
