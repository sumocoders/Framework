namespace :sumo do
  namespace :redirect do
    desc "Enable a redirect page, all traffic will be redirected to this page."
    task :enable do
      # we need to overrule the task, as the dir is not the same as on Fork.
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
