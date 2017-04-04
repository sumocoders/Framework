namespace :sumo do
  namespace :db do
    desc "Create the database if it doesn't exists yet"
    task :create do
      if fetch(:stage).to_s != "staging"
        warn Airbrussh::Colors.red('✘') + " This task will only work on staging"
        exit 1
      end

      on roles(:web) do
        execute "create_db #{(fetch :client)[0,8]}_#{(fetch :project)[0,7]}"
      end
    end

    desc "Get info about the database"
    task :info do
      if fetch(:stage).to_s != "staging"
        warn Airbrussh::Colors.red('✘') + " This task will only work on staging"
        exit 1
      end

      on roles(:web) do
        execute "info_db #{(fetch :client)[0,8]}_#{(fetch :project)[0,7]}"
      end
    end
  end

  namespace :redirect do
    desc "Enable a redirect page, all traffic will be redirected to this page."
    task :enable do
      on roles(:web) do
        execute :mkdir, "-p", "#{shared_path}/redirect/web"
        execute :wget, "-qO", "#{shared_path}/redirect/web/index.php http://static.sumocoders.be/redirect/index.phps"
        execute :wget, "-qO", "#{shared_path}/redirect/web/.htaccess http://static.sumocoders.be/redirect/htaccess"
        execute :sed, "-i", "'s|<real-url>|#{fetch :production_url}|' #{shared_path}/redirect/web/index.php"
        execute :rm, "-f", "#{fetch :deploy_to}/current"
        execute :ln, "-s", "#{shared_path}/redirect #{fetch :deploy_to}/current"
      end
    end
  end
end
