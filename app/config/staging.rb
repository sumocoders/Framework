set :branch, "master"

### DO NOT EDIT BELOW ###
set :host,      'dev.sumocoders.eu'
set :user,      'sites'
set :domain,    '#{project}.#{client}.sumocoders.eu'
set :deploy_to,   "/home/#{user}/apps/#{client}/#{project}"

server "#{host}", :app, :web, :primary => true

# Cleanup
set :keep_releases,  1
