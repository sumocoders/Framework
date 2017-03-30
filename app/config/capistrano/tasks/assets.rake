namespace :assets do
  desc "Compile the assets"
  task :compile do
    run_locally do
      execute "gulp build"
    end
  end
  desc "Upload the assets"
  task :upload do
    invoke "assets:compile"
    on roles (:web) do
      within symfony_web_path do
        execute :rm, "-rf", "assets"
        execute :mkdir, "-p", "assets"
      end
      upload! "./web/assets", "#{release_path.to_s}/web", recursive: true
    end
  end
end
