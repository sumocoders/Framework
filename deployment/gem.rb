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

namespace :sumo do
  namespace :setup do
    desc "Create the client folder if it doesn't exist yet"
    task :client_folder do
      run "mkdir -p `dirname #{document_root}`"
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
  end

  namespace :errbit do
    desc "Notify Errbit about a new deploy"
    task :notify do
      capifony_pretty_print "--> Notifying Errbit"
      set :errbit_api_key, get_value_from_parameters("errbit_api_key")

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
        if [ -f #{current_release.shellescape}/web/.htaccess ]; then mv #{current_release.shellescape}/web/.htaccess #{current_release.shellescape}/web/.htaccess.old; fi
      }
      put redirect_to_maintenance, "#{current_release.shellescape}/web/.htaccess"
    end
    desc "[internal] restore the original .htaccess"
    task :disable do
      run %{
        if [ -f #{current_release.shellescape}/web/.htaccess ]; then rm #{current_release.shellescape}/web/.htaccess; fi
      }
      run %{
        if [ -f #{current_release.shellescape}/web/.htaccess.old ]; then mv #{current_release.shellescape}/web/.htaccess.old #{current_release.shellescape}/web/.htaccess; fi
      }
    end
  end

  namespace :assets do
    desc "run grunt build to compile the assets"
    task :precompile do
      if !File.exist?("Gruntfile.coffee")
        logger.important "No Gruntfile.coffee found"
      else
        capifony_pretty_print "--> Running grunt build"
        run_locally "grunt build"
        capifony_puts_ok
      end
    end
    desc "upload the assets into the release"
    task :upload do
      framework.assets.precompile
      capifony_pretty_print "--> Removing existing assets-folder"
      run %{
        rm -rf #{latest_release.shellescape}/web/assets &&
        mkdir -p #{latest_release.shellescape}/web/assets
      }
      capifony_puts_ok

      capifony_pretty_print "--> Uploading assets"
      top.upload "./web/assets", "#{latest_release.shellescape}/web/assets"
      capifony_puts_ok
    end
  end
end

before 'symfony:assetic:dump', 'symfony:cache:clear'

after "deploy", "deploy:cleanup"
after 'deploy:setup', 'framework:setup:link_document_root'
after 'deploy:update_code', 'framework:assets:upload'
after 'deploy:update_code', 'symfony:assetic:dump'
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
