set :application, project

# Cleanup
set  :keep_releases,  3

# Composer configuration
set :use_composer, true
set :composer_options, '--no-scripts'

# Git configuration
set :scm, :git

# Logging
logger.level = Logger::MAX_LEVEL

# Other configurations
set :use_sudo, false
default_run_options[:pty] = true
set :model_manager, "doctrine"

# SSH options
ssh_options[:forward_agent] = true
