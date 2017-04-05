namespace :migrations do
  desc "Execute the mogrations if needed"
  task :migrate do
    on roles(:web) do
      command = "#{fetch :php_bin} #{current_path}/app/console --env=prod doctrine:migrations:migrate --no-interaction"
      status = capture("#{command} --dry-run")

      if status.include? "Executing dry run of migration"
        execute command
      end
    end
  end
end
