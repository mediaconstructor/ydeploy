<?php

namespace Deployer;

use Deployer\Task\Context;
use function dirname;
use function strlen;
use function YDeploy\upgradeReleasesList;

$baseDir = dirname(__DIR__, 5);
if (str_starts_with($baseDir, getcwd())) {
    $baseDir = substr($baseDir, strlen(getcwd()));
    $baseDir = ltrim($baseDir.'/', '/');
}

set('base_dir', $baseDir);
set('media_dir', '{{base_dir}}media');
set('cache_dir', '{{base_dir}}redaxo/cache');
set('data_dir', '{{base_dir}}redaxo/data');
set('src_dir', '{{base_dir}}redaxo/src');

localhost('local')
    ->set('deploy_path', '{{base_dir}}.build')
    ->set('release_path', '{{deploy_path}}/release')
    ->set('current_path', '{{deploy_path}}/current')
    ->set('labels', ['stage' => 'build']);

set('bin/console', '{{base_dir}}redaxo/bin/console');

set('branch', static function () {
    $branch = null;
    on(host('local'), static function () use (&$branch) {
        $branch = run('{{bin/git}} rev-parse --abbrev-ref HEAD');
    });

    return $branch;
});

$releaseName = Deployer::get()->config->fetch('release_name');
set('release_name', static function () use ($releaseName) {
    upgradeReleasesList();
    return $releaseName();
});

$releasesList = Deployer::get()->config->fetch('releases_list');
set('releases_list', static function () use ($releasesList) {
    upgradeReleasesList();
    return $releasesList();
});

set('shared_dirs', array_merge(
    get('shared_dirs', []),
    [
        '{{media_dir}}',
        '{{data_dir}}/addons/cronjob',
        '{{data_dir}}/addons/phpmailer',
        '{{data_dir}}/addons/yform',
        '{{data_dir}}/core',
    ]
));

set('writable_dirs', array_merge(
    get('writable_dirs', []),
    [
        '{{base_dir}}assets',
        '{{media_dir}}',
        '{{cache_dir}}',
        '{{data_dir}}',
    ]
));

set('copy_dirs', array_merge(
    get('copy_dirs', []),
    [
        '{{base_dir}}assets',
        '{{src_dir}}',
    ]
));

set('clear_paths', array_merge(
    get('clear_paths', []),
    [
        '.github',
        '.idea',
        'gulpfile.js',
        '.gitignore',
        '.gitlab-ci.yml',
        '.php-cs-fixer.dist.php',
        'package.json',
        'README.md',
        'webpack.config.js',
        'yarn.lock',
        'REVISION',
    ]
));

set('keep_releases', 5);

set('url', static function () {
    return 'https://'.Context::get()->getHost()->getHostname();
});

set('allow_anonymous_stats', false);

after('deploy:failed', 'deploy:unlock');

set('bin/mysql', static function () {
    return which('mysql');
});

set('bin/mysqldump', static function () {
    return which('mysqldump');
});
