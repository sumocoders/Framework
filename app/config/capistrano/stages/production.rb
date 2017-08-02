set :branch, "master"
server "php71-001.sumohosting.be", user: "tijs", roles: %w{app db web}
set :document_root, "/home/tijs/public_html/framework"
set :deploy_to, "/home/tijs/apps/#{fetch :project}"

set :opcache_reset_strategy, 'fcgi'
set :opcache_reset_fcgi_connection_string, '/usr/local/php71/sockets/$production-user.sock'

### DO NOT EDIT BELOW ###
set :keep_releases,  3
set :php_bin, "php"

SSHKit.config.command_map[:composer] = "#{fetch :php_bin} #{shared_path.join("composer.phar")}"
SSHKit.config.command_map[:php] = fetch(:php_bin)
