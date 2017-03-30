set :client,  "client"
set :project, "framework"
set :repo_url, "git@github.com:sumocoders/Framework.git"

### DO NOT EDIT BELOW ###
lock "3.8.0"

append :linked_files, "app/config/parameters.yml"
append :linked_dirs, "app/logs"

set :application, "#{fetch :project}"
set :symfony_console_path, "app/console"

namespace :deploy do
  after :starting, "composer:install_executable"

  before :publishing, "assets:upload"
end
