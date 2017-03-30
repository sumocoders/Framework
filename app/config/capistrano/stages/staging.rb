set :branch, "master"

### DO NOT EDIT BELOW ###

set :deploy_to, "/home/sites/apps/#{fetch :client}/#{fetch :project}"
server "dev02.sumocoders.eu", user: "sites", roles: %w{app db web}

set :keep_releases,  2

SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"

### OLD ###
#set :domain,        "#{project}.#{client}.php71.sumocoders.eu"
#set :document_root, "/home/#{user}/php71/#{client}/#{project}"
#
#before 'deploy:setup', 'sumo:setup:client_folder'
#before 'deploy:setup', 'sumo:db:create'
