### DO NOT EDIT BELOW ###
set :branch, "staging"

set :document_root, "/home/sites/php71/#{fetch :client}/#{fetch :project}"
set :deploy_to, "/home/sites/apps/#{fetch :client}/#{fetch :project}"

set :keep_releases,  2

set :url, "http://#{fetch :project}.#{fetch :client}.php71.sumocoders.eu"

set :php_bin, 'php7.1'
set :php_bin_custom_path, fetch(:php_bin)

set :opcache_reset_strategy, 'fcgi'
set :opcache_reset_fcgi_connection_string, '/var/run/php_71_fpm_sites.sock'

server 'dev02.sumocoders.eu', user: 'sites', roles: %w{app db web}

SSHKit.config.command_map[:composer] = "#{fetch :php_bin} #{shared_path.join("composer.phar")}"
SSHKit.config.command_map[:php] = fetch(:php_bin)

namespace :composer do
    desc 'Install the vendors'
    task :install_vendors do
        composer.install_composer
        run %{
            alias php="~/#{php_bin}" &&
            cd #{latest_release} &&
            #{php_bin} -d 'suhosin.executor.include.whitelist = phar' -d 'date.timezone = UTC' #{shared_path}/composer.phar install -o --no-dev
        }
    end
end
