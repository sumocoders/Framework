set :deploy_config_path, "app/config/capistrano/deploy.rb"
set :stage_config_path, "app/config/capistrano/stages"

require "capistrano/setup"
require "capistrano/deploy"
require "capistrano/scm/git"
install_plugin Capistrano::SCM::Git
require "capistrano/symfony"

set :format_options, log_file: "app/logs/capistrano.log"

## DO NOT EDIT ABOVE ##

## TO CHECK BELOW

#   https://github.com/capistrano/bundler

## DO NOT EDIT ABOVE ##
# Load custom tasks from `lib/capistrano/tasks` if you have any defined
Dir.glob("app/config/capistrano/tasks/*.rake").each { |r| import r }
