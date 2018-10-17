namespace :framework do
  namespace :configure do
    desc <<-DESC
      Configures cachetool
      It make sure the command is mapped correctly and the correct flags are used.
    DESC
    task :cachetool do
      # Set the correct bin
      SSHKit.config.command_map[:cachetool] = "#{fetch :php_bin} #{fetch :deploy_to}/shared/cachetool.phar"
    end
  end
end
