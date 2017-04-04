set :branch, "master"

### DO NOT EDIT BELOW ###

set :document_root, "/home/sites/php71/#{fetch :client}/#{fetch :project}"
set :deploy_to, "/home/sites/apps/#{fetch :client}/#{fetch :project}"
set :keep_releases,  2
set :url, "http://#{fetch :project}.#{fetch :client}.php71.sumocoders.eu"
set :fcgi_connection_string, "/var/run/php_71_fpm_sites.sock"

server "dev02.sumocoders.eu", user: "sites", roles: %w{app db web}

SSHKit.config.command_map[:composer] = "#{fetch :php_bin} #{shared_path.join("composer.phar")}"

### OLD ###
#set :domain,        "#{project}.#{client}.php71.sumocoders.eu"
#set :document_root, "/home/#{user}/php71/#{client}/#{project}"
#
#before 'deploy:setup', 'sumo:setup:client_folder'
#before 'deploy:setup', 'sumo:db:create'
