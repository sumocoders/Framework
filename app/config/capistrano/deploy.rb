set :client,  ''
set :project, ''
set :repo_url, ''
set :production_url, ''

### DO NOT EDIT BELOW ###
set :application, "#{fetch :project}"

set :deploytag_utc, false
set :deploytag_time_format, '%Y%m%d-%H%M%S'

set :files_dir, %w(web/files files)

append :linked_files, 'app/config/parameters.yml'
append :linked_dirs, 'app/logs'

set :symfony_console_path, 'app/console'

# Run required tasks after the stage
Capistrano::DSL.stages.each do |stage|
  after stage, 'framework:configure:cachetool'
end

namespace :deploy do
  after :check, 'framework:symlink:document_root'

  after :starting, 'composer:install_executable'
  after :starting, 'cachetool:install_executable'


  after :published, 'migrations:migrate'

  before :publishing, 'assets:upload'
  after :publishing, 'framework:opcache:reset'

  after :finished, 'sumo:notifications:deploy'

  after :failed, 'maintenance:disable'
end

namespace :assets do
  after :upload, 'assets:update_assets_version'
end

namespace :migrations do
  before :migrate, 'maintenance:enable'
  after :migrate, 'maintenance:disable'
end
