# This stuff should be moved into a gem
def get_value_from_parameters(key)
  set :path, "./app/config/parameters.yml"

  if File.exists?(path)
    parameters = YAML.load_file(path).fetch("parameters", nil)

    unless parameters.nil?
      return parameters.fetch(key, nil)
    end
  end

  return nil
end

def get_value_from_remote_parameters(key)
  set :path, "app/config/parameters.yml"

  begin
    remoteParameters = capture("cat #{shared_path}/#{path}")
  rescue
    return nil
  end

  parameters = YAML.load(remoteParameters).fetch("parameters", nil)

  unless parameters.nil?
    return parameters.fetch(key, nil)
  end

  return nil
end

def ask(question, default)
  message = "\033[46m#{question}\033[0m"
  unless default.empty?
      message += " (\033[33m#{default}\033[0m)"
  end
  message += ":"

  answer = Capistrano::CLI.ui.ask message
  if answer.empty?
    answer = default
  end

  answer
end

namespace :sumo do
  namespace :setup do
    desc "Create the client folder if it doesn't exist yet"
    task :client_folder do
      run "mkdir -p `dirname #{document_root}`"
    end
  end
  namespace :db do
    desc "Create the database if it doesn't exists yet"
    task :create do
      capifony_pretty_print "--> Creating database"
      database_information = capture("create_db #{client[0,8]}_#{project[0,7]}")
      capifony_puts_ok

      puts database_information
    end
    desc "Get info about the database"
    task :info do
      capifony_pretty_print "--> Grabbing information about the database"
      database_information = capture("info_db #{client[0,8]}_#{project[0,7]}")
      capifony_puts_ok

      puts database_information
    end
  end
  namespace :deploy do
    desc "This will check if we are able to deploy. As we need to be on the
        correct branch and it should be up-to-date"
    task :before_deploy do
      capifony_pretty_print "--> Checking if all conditions are met before deploying"

      working_branch = (run_locally "git rev-parse --abbrev-ref HEAD").strip
      branch_to_deploy = branch.strip

      # check if we are on the same branch
      unless working_branch == branch_to_deploy
        message = <<-EOF
            Your current branch (#{working_branch}) is not the same as the
            branch (#{branch_to_deploy}) that will be deployed.
        EOF
        error = CommandError.new(message)
        raise error
      end

      # check if the branch is up to date
      run_locally "git remote update"
      local_commit = run_locally "git rev-parse #{working_branch}"
      remote_commit = run_locally "git rev-parse #{branch_to_deploy}"
      unless local_commit == remote_commit
        message = <<-EOF
            Your current branch (#{working_branch}) is not up to date with the
            remote branch (#{branch_to_deploy}).
        EOF
        error = CommandError.new(message)
        raise error
      end

      capifony_puts_ok
    end
  end
end

namespace :sumodev do
  namespace :db do
    desc "Create the database if it doesn't exists yet"
    task :create do
      sumo.db.create
    end
    desc "Get info about the database"
    task :info do
      sumo.db.info
    end
  end
end

namespace :framework do
  namespace :setup do
    desc "link the document root to the current/web-folder"
    task :link_document_root do
      # create symlink for document_root if it doesn't exists
      documentRootExists = capture("if [ ! -e #{document_root} ]; then ln -sf #{current_path}/web #{document_root}; echo 'no'; fi").chomp

      unless documentRootExists == "no"
        warn "Warning: Document root (#{document_root}) already exists"
        warn "to link it to the deploy, issue the following command:"
        warn "	ln -sf #{current_path} #{document_root}"
      end
    end
    desc "create a user"
    task :create_user do
      username = ask("Please choose a username", "sumocoders")
      email = ask("Please choose an email", "accounts@sumocoders.be")
      password = ask("Please choose a password", "")

      if password.empty?
        warn "The password is required."
      else
        capifony_pretty_print "--> Creating user #{username}"
        run %{
            cd #{current_path} &&
            php app/console -q --env=prod fos:user:create #{username} #{email} #{password}
        }
        capifony_puts_ok
      end
    end
  end

  namespace :errbit do
    desc "Notify Errbit about a new deploy"
    task :notify do
      capifony_pretty_print "--> Notifying Errbit"
      set :errbit_api_key, get_value_from_remote_parameters("errbit_api_key")

      unless errbit_api_key.nil?
        require 'active_support/core_ext/object'

        parameters = {
          :api_key => errbit_api_key,
          :deploy => {
            :rails_env => stage,
            :local_username => ENV["USER"],
            :scm_repository => repository,
            :scm_revision => current_revision
          }
        }

        run_locally "curl -d '#{parameters.to_query}' -sS https://errors.sumocoders.be/deploys.txt"
        capifony_puts_ok
      else
        warn "errbit_api_key not provided in parameters.yml"
      end
    end
  end

  namespace :maintenance do
    desc "[internal] redirect all incoming traffic to the maintenance.html-page"
    task :enable do
      redirect_to_maintenance = <<-EOF
        RewriteEngine On
        RewriteCond %{REQUEST_URI} !.(css|gif|jpg|png)$
        RewriteCond %{SCRIPT_FILENAME} !maintenance.html
        RewriteRule ^.*$ /maintenance.html [L]
      EOF
      run %{
        if [ -f #{current_release.shellescape}/web/.htaccess ] && ! [ -f #{current_release.shellescape}/web/.htaccess.old ]; then mv #{current_release.shellescape}/web/.htaccess #{current_release.shellescape}/web/.htaccess.old; fi
      }
      put redirect_to_maintenance, "#{current_release.shellescape}/web/.htaccess"
    end
    desc "[internal] restore the original .htaccess"
    task :disable do
      run %{
        if [ -f #{current_release.shellescape}/web/.htaccess ] && [ -f #{current_release.shellescape}/web/.htaccess.old ]; then rm #{current_release.shellescape}/web/.htaccess; fi
      }
      run %{
        if [ -f #{current_release.shellescape}/web/.htaccess.old ]; then mv #{current_release.shellescape}/web/.htaccess.old #{current_release.shellescape}/web/.htaccess; fi
      }
    end
  end

  namespace :redirect do
    desc "Enable a redirect page, all traffic will be redirected to this page."
    task :enable do
      production_url = ask("What is the production url (include the protocol)?", "http://#{domain}")

      capifony_pretty_print "--> Enabling the redirect page"

      run %{
        mkdir -p #{shared_path}/redirect/web &&
        wget --quiet -O #{shared_path}/redirect/web/index.php http://static.sumocoders.be/redirect/index.phps &&
        wget --quiet -O #{shared_path}/redirect/web/.htaccess http://static.sumocoders.be/redirect/htaccess
      }

      run "if [ -f #{shared_path}/redirect/web/index.php ]; then sed -i 's|<real-url>|#{production_url}|' #{shared_path}/redirect/web/index.php; fi"
      run %{
        rm -f #{current_path} &&
        ln -s #{shared_path}/redirect #{current_path}
      }

      capifony_puts_ok
    end
  end

  namespace :assets do
    desc "run gulp build to compile the assets"
    task :precompile do
      if File.exist?("Gruntfile.coffee")
        capifony_pretty_print "--> Running grunt build"
        run_locally "grunt build"
        capifony_puts_ok
      end
      if File.exist?("gulpfile.js")
        capifony_pretty_print "--> Running gulp build"
        run_locally "gulp build"
        capifony_puts_ok
      end
    end
    desc "upload the assets into the release"
    task :upload do
      framework.assets.precompile
      capifony_pretty_print "--> Removing existing assets-folder"
      run %{
        rm -rf #{current_release.shellescape}/web/assets &&
        mkdir -p #{current_release.shellescape}/web/assets
      }
      capifony_puts_ok

      capifony_pretty_print "--> Uploading assets"
      top.upload "./web/assets", "#{current_release.shellescape}/web/assets"
      capifony_puts_ok
    end
  end
end

# found in https://gist.github.com/jakzal/1400923
namespace :symfony do
  namespace :assets do
    desc "Updates assets version"
    task :update_version do
        capifony_pretty_print "--> Update assets version"
        run "sed -i 's/\\(assets_version: \\)\\(.*\\)$/\\1 #{real_revision}/g' #{current_release}/app/config/config.yml"
        capifony_puts_ok
    end
  end
end

before 'symfony:cache:warmup', 'symfony:assets:update_version'
before 'deploy:update_code', 'symfony:cache:clear'

after "deploy", "deploy:cleanup", "framework:errbit:notify"
after 'deploy:setup', 'framework:setup:link_document_root'
after 'deploy:update_code', 'framework:assets:upload'
after 'deploy:web:disable', 'framework:maintenance:enable'
after 'deploy:web:enable', 'framework:maintenance:disable'

# Disable the site before doing database/stuff
before "database:copy:to_local", "deploy:web:disable"
before "database:copy:to_remote", "deploy:web:disable"
before "database:dump:remote", "deploy:web:disable"
before "deploy:migrate", "deploy:web:disable"
before "deploy:migrations", "deploy:web:disable"
before "symfony:doctrine:database:create", "deploy:web:disable"
before "symfony:doctrine:database:drop", "deploy:web:disable"
before "symfony:doctrine:init:acl", "deploy:web:disable"
before "symfony:doctrine:load_fixtures", "deploy:web:disable"
before "symfony:doctrine:migrations:migrate", "deploy:web:disable"
before "symfony:doctrine:migrations:status", "deploy:web:disable"
before "symfony:doctrine:mongodb:indexes:create", "deploy:web:disable"
before "symfony:doctrine:mongodb:indexes:drop", "deploy:web:disable"
before "symfony:doctrine:mongodb:load_fixtures", "deploy:web:disable"
before "symfony:doctrine:mongodb:schema:create", "deploy:web:disable"
before "symfony:doctrine:mongodb:schema:drop", "deploy:web:disable"
before "symfony:doctrine:mongodb:schema:update", "deploy:web:disable"
before "symfony:doctrine:schema:create", "deploy:web:disable"
before "symfony:doctrine:schema:drop", "deploy:web:disable"
before "symfony:doctrine:schema:update", "deploy:web:disable"
before "symfony:propel:build:sql_load", "deploy:web:disable"
before "symfony:propel:database:create", "deploy:web:disable"
before "symfony:propel:database:drop", "deploy:web:disable"

# Re-enable the site after doing database/stuff
after "database:copy:to_local", "deploy:web:enable"
after "database:copy:to_remote", "deploy:web:enable"
after "database:dump:remote", "deploy:web:enable"
after "deploy:migrate", "deploy:web:enable"
after "deploy:migrations", "deploy:web:enable"
after "symfony:doctrine:database:create", "deploy:web:enable"
after "symfony:doctrine:database:drop", "deploy:web:enable"
after "symfony:doctrine:init:acl", "deploy:web:enable"
after "symfony:doctrine:load_fixtures", "deploy:web:enable"
after "symfony:doctrine:migrations:migrate", "deploy:web:enable"
after "symfony:doctrine:migrations:status", "deploy:web:enable"
after "symfony:doctrine:mongodb:indexes:create", "deploy:web:enable"
after "symfony:doctrine:mongodb:indexes:drop", "deploy:web:enable"
after "symfony:doctrine:mongodb:load_fixtures", "deploy:web:enable"
after "symfony:doctrine:mongodb:schema:create", "deploy:web:enable"
after "symfony:doctrine:mongodb:schema:drop", "deploy:web:enable"
after "symfony:doctrine:mongodb:schema:update", "deploy:web:enable"
after "symfony:doctrine:schema:create", "deploy:web:enable"
after "symfony:doctrine:schema:drop", "deploy:web:enable"
after "symfony:doctrine:schema:update", "deploy:web:enable"
after "symfony:propel:build:sql_load", "deploy:web:enable"
after "symfony:propel:database:create", "deploy:web:enable"
after "symfony:propel:database:drop", "deploy:web:enable"
