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
end
