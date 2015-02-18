# This stuff should be moved into a gem

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
