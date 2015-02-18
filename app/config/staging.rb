set :branch, "staging"

### DO NOT EDIT BELOW ###
set :host,          "dev.sumocoders.eu"
set :user,          "sites"
set :domain,        "#{project}.#{client}.sumocoders.eu"
set :deploy_to,     "/home/#{user}/apps/#{client}/#{project}"
set :document_root, "/home/#{user}/#{client}/#{project}"

server "#{host}", :app, :web, :db, :primary => true

# Cleanup
set :keep_releases,  2

before 'deploy:setup', 'sumo:setup:client_folder'
