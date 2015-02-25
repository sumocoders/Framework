set :host,          "xxx" # eg. web03.sumocoders.be
set :user,          "xxx" # eg. sumocoders
set :domain,        "xxx" # eg. app.sumoapp.be"
set :deploy_to,     "/home/#{user}/apps/#{client}/#{project}"
set :document_root, "/home/#{user}/#{domain}"

### DO NOT EDIT BELOW ###
server "#{host}", :app, :web, :db, :primary => true
