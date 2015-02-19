# Using deployment

We use Capifony as a base, but we extended it with our own specific 
configuration for usage with the framework.

All configuration for the specific stage (staging, production) is done in the 
`app/config/`-folder, wherein each stage has its own file.

General configuration is done in the Capfile.

## Capfile

When you create a project you will need to fill in 3 variables:

* `:client`, the `xxx` should be replaced with the name of the client.
* `:project`, the `xxx` should be replaced with the name of the project.
* `:repository`, the `xxx` should be replaced with the url of the git-repo.

Remark: when choosing a name for the project, please don't use generic names 
as: site, app, ... as it makes no sense when there are multiple projects for 
that client.

## app/config/staging.rb

In the is file you can configure which branch should be deployed on the 
staging-server. By default this is the staging-branch

* `:branch`, if needed you can replace `staging` with your branch.

Remark: when you change this, make sure you notify other people, as when they
deploy and are not working on your branch your changes will be lost. Also make 
sure that you change it back before creating a Pull Request.

## production.rb

In the is file you can configure production specific items. In most cases the 
server-layout will be different from the staging-server, so there is somewhat 
more to configure.

The default production.rb is inspired on the Hostbots-server-layout.

* `set :host`, replace `xxx` with the hostname/ip of the server.
* `set :user`, replace `xxx` with the ssh-username.
* `set :domain`, replace `xxx` with the final domain of the application, 
    without http(s)://.
* `set :deploy_to`, you can alter this if you need to deploy the application in
    a specific directory.
* `set :document_root`, this should reflect the document_root.
