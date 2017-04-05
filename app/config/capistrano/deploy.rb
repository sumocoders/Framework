set :client,  "client"
set :project, "framework"
set :repo_url, "git@github.com:sumocoders/Framework.git"
set :production_url, "http://framework.sumocoders.eu"

### DO NOT EDIT BELOW ###
lock "3.8.0"

append :linked_files, "app/config/parameters.yml"
append :linked_dirs, "app/logs"

set :application, "#{fetch :project}"
set :symfony_console_path, "app/console"
set :php_bin, "php7.1"

namespace :deploy do
  after :check, "framework:symlink:document_root"

  after :starting, "composer:install_executable"
  after :starting, "opcache:phpfpm:install_executable"

  before :publishing, "assets:upload"

  after :published, "opcache:phpfpm:reset"
  after :published, "migrations:migrate"

  after :finished, "sumo:notifications:deploy"


end

namespace :assets do
  after :upload, "assets:update_assets_version"
end
