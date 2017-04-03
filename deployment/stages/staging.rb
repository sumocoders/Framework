set :branch, "staging"

### DO NOT EDIT BELOW ###
set :host,          "dev02.sumocoders.eu"
set :user,          "sites"
set :domain,        "#{project}.#{client}.php71.sumocoders.eu"
set :deploy_to,     "/home/#{user}/apps/#{client}/#{project}"
set :document_root, "/home/#{user}/php71/#{client}/#{project}"

server "#{host}", :app, :web, :db, :primary => true

# Cleanup
set :keep_releases,  2

before 'deploy:setup', 'sumo:setup:client_folder'
before 'deploy:setup', 'sumo:db:create'

set :php_bin, 'php7.1'

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
