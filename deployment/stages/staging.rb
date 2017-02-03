set :branch, "staging"

### DO NOT EDIT BELOW ###
set :host,          "dev02.sumocoders.eu"
set :user,          "sites"
set :domain,        "#{project}.#{client}.php71.sumocoders.eu"
set :deploy_to,     "/home/#{user}/apps/#{client}/#{project}"
set :document_root, "/home/#{user}/php71/#{client}/#{project}"

server "#{host}", :app, :web, :db, :primary => true

# Cleanup
set :keep_releases,  2

before 'deploy:setup', 'sumo:setup:client_folder'
before 'deploy:setup', 'sumo:db:create'
