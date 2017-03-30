# config valid only for current version of Capistrano
lock "3.8.0"

set :application, "framework"
set :repo_url, "git@github.com:sumocoders/Framework.git"

append :linked_files, "app/config/parameters.yml"
append :linked_dirs, "app/logs"

set :symfony_console_path, "app/console"

namespace :deploy do
  after :starting, "composer:install_executable"

  before :publishing, "assets:upload"
end
