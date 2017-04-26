namespace :framework do
  namespace :symlink do
    desc "Link the document root to the current/web-folder"
    task :document_root do
      on roles(:web) do
        if test("[ -d #{fetch :document_root} ]")
          warn Airbrussh::Colors.yellow('⚠') + " Document root (#{fetch :document_root}) already exists"
          warn Airbrussh::Colors.yellow('⚠') + " to link it to the deploy, issue the following command:"
          warn Airbrussh::Colors.yellow('⚠') + " ln -sf #{fetch :deploy_to}/current #{fetch :document_root}"
        else
          execute :ln, "-s", "#{fetch :deploy_to}/current/web", "#{fetch :document_root}"
        end
      end
    end
  end
end
