# config valid only for current version of Capistrano
lock "3.8.0"

set :application, "framework"
set :repo_url, "git@github.com:sumocoders/Framework.git"

# Default value for :linked_files is []
# append :linked_files, "config/database.yml", "config/secrets.yml"

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"

set :symfony_console_path, "app/console"

namespace :deploy do
  after :starting, "composer:install_executable"

  before :publishing, "assets:upload"
end
