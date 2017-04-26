namespace :maintenance do
  task :enable do
    on roles(:web) do
      require 'erb'

      reason = ENV['REASON']
      deadline = ENV['UNTIL']

      result = ERB.new(File.read(__dir__ + "/../assets/maintenance.erb")).result(binding)

      upload! StringIO.new(result), "#{current_path}/web/maintenance.html"
      execute :chmod, "644 #{current_path}/web/maintenance.html"

      htaccess_path = "#{current_path}/web/.htaccess"
      if test("[ -f #{htaccess_path} ]")
        execute :mv, "#{htaccess_path} #{htaccess_path}.backup"
      end

      redirect_to_maintenance = <<-EOF
        RewriteEngine On
        RewriteCond %{REQUEST_URI} !.(css|gif|jpg|png)$
        RewriteCond %{SCRIPT_FILENAME} !maintenance.html
        RewriteRule ^.*$ /maintenance.html [L]
      EOF
      upload! StringIO.new(redirect_to_maintenance), "#{htaccess_path}"
      execute :chmod, "644 #{htaccess_path}"
    end
  end

  task :disable do
    on roles(:web) do
      htaccess_path = "#{current_path}/web/.htaccess"

      execute :rm, "-f #{htaccess_path}"
      execute "rm -f #{current_path}/web/maintenance.html"

      if test("[ -f #{htaccess_path}.backup ]")
        execute :mv, "#{htaccess_path}.backup #{htaccess_path}"
      end
    end
  end
end
