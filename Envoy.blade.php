@servers([ 'remote' => 'forge@warm-pine.blankhosting.com', ])

@setup
    if ( ! isset($repo) )
    {
        throw new Exception('--repo must be specified');
    }

    if ( ! isset($base_dir) )
    {
        throw new Exception('--base_dir must be specified');
    }

    if ( ! isset($github_token) )
    {
        throw new Exception('--github_token must be specified');
    }

    $branch      = isset($branch) ? $branch : 'develop';
    $repo_name   = array_pop(explode('/', $repo));
    $repo        = 'https://api.github.com/repos/' . $repo . '/tarball/' . $branch . '?access_token=' . $github_token;
    $release_dir = $base_dir . '/releases';
    $current_dir = $base_dir . '/current';
    $release     = date('YmdHis');
    $env         = isset($env) ? $env : 'staging';
@endsetup

@macro('deploy', [ 'on' => 'remote', ])
    fetch_repo
    run_composer
    update_symlinks
    down
    migrate
    up
    clean_old_releases
@endmacro

@task('fetch_repo')
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{ $release_dir }};

    # Make the release dir
    mkdir {{ $release }};

    # Download the tarball
    echo 'Fetching project tarball';
    curl -sLo {{ $release }}.tar.gz {{ $repo }};

    # Extract the tarball
    echo 'Extracting tarball';
    tar --strip-components=1 -zxf {{ $release }}.tar.gz -C {{ $release }};

    # Purge temporary files
    echo 'Purging temporary files';
    rm -rf {{ $release }}.tar.gz;
@endtask

@task('run_composer')
    echo 'Installing composer dependencies';
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist --no-scripts -q -o;
@endtask

@task('update_symlinks')
    echo 'Updating symlinks';

    # Remove the storage directory and replace with persistent data
    echo 'Linking storage directory';
    rm -rf {{ $release_dir }}/{{ $release }}/storage;
    cd {{ $release_dir }}/{{ $release }};
    ln -nfs {{ $base_dir }}/storage storage;

    # Import the environment config
    echo 'Linking .env file';
    cd {{ $release_dir }}/{{ $release }};
    ln -nfs {{ $base_dir }}/.env .env;

    # Optimise installation
    echo 'Optimising installation';
    php artisan clear-compiled --env={{ $env }};
    php artisan optimize --env={{ $env }};
    php artisan config:cache --env={{ $env }};
    php artisan route:cache --env={{ $env }};

    # Symlink the latest release to the current directory
    echo 'Linking current release';
    ln -nfs {{ $release_dir }}/{{ $release }} {{ $current_dir }};
@endtask

@task('migrate')
    echo 'Running migrations';
    cd {{ $release_dir }}/{{ $release }};
    php artisan migrate --env={{ $env }} --force;
@endtask

@task('down')
    cd {{ $release_dir }}/{{ $release }};
    php artisan down;
@endtask

@task('up')
    cd {{ $current_dir }};
    php artisan up;
@endtask

@task('clean_old_releases')
    echo 'Purging old releases';
    # This will list our releases by modification time and delete all but the 5 most recent.
    ls -dt {{ $release_dir }}/* | tail -n +6 | xargs -d '\n' rm -rf;
@endtask
