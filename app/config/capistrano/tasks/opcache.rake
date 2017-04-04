def url
  if fetch(:stage).to_s != "staging"
    set :url, fetch(:production_url)
  end

  if fetch(:url).empty?
   warn Airbrussh::Colors.red('✘') + " Please specify an url."
   exit
  end

  fetch :url
end

namespace :opcache do
  namespace :file do
    desc "Reset the opcache thru a PHP-file"
    task :reset do
      on roles(:web) do
        execute :touch, "#{fetch :document_root}/php-opcache-reset.php"
        execute :echo, "\"<?php clearstatcache(true); if (function_exists('opcache_reset')) { opcache_reset(); }\" > #{fetch :document_root}/php-opcache-reset.php"
        execute :curl, "-s", "#{url()}/php-opcache-reset.php"
        execute :rm, "#{fetch :document_root}/php-opcache-reset.php"
      end
    end
  end

  namespace :phpfpm do
    desc "Installs cachetool.phar to the shared directory"
    task :install_executable do
      on roles(:web) do
        within shared_path do
          if test "[", "!", "-e", "cachetool.phar", "]"
            execute :curl, "-sO", "http://gordalina.github.io/cachetool/downloads/cachetool.phar"
            execute :chmod, "+x cachetool.phar"
          end
        end
      end
    end

    desc "Reset the opcache with the cachetool.phar"
    task :reset do
      on roles(:web) do
        within shared_path do
          execute "#{fetch :php_bin} #{shared_path}/cachetool.phar opcache:reset --fcgi=#{fetch :fcgi_connection_string}"
        end
      end
    end
  end
end
