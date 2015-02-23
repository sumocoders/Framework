set :application, project

# Cleanup
set  :keep_releases,  3

# Composer configuration
set :use_composer, true
set :copy_vendors, true

# Git configuration
set :scm, :git

# Maintenance page
set :maintenance_template_path, "deployment/maintenance.erb"

# Logging
#logger.level = Logger::DEBUG

# Shared files
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor", app_path + "/sessions"]
set :writable_dirs,     ["app/cache", "app/logs", "app/sessions"]

# Other configurations
set :use_sudo, false
default_run_options[:pty] = true
set :model_manager, "doctrine"

# SSH options
ssh_options[:forward_agent] = true

