load 'deploy' if respond_to?(:namespace) # cap2 differentiator

set :client,  'xxx'
set :project, 'xxx'
set :repository,  'xxx'

### DO NOT EDIT BELOW ###

require 'capifony_symfony2'
set :interactive_mode, false
set :stages,           %w(production staging)
set :default_stage,    'staging'
set :stage_dir,        'deployment/stages'

require 'capistrano/ext/multistage'

load 'deployment/gem'
load 'deployment/deploy'
