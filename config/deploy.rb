# config valid only for current version of Capistrano
lock "3.7.1"

set :application, "zalora-crawler"
set :repo_url, "git@github.com:jhontea/zalora-crawler.git"

# Default branch is :master
ask :branch, `git tag`.split("\n").last

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, "/var/www/my_app_name"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# append :linked_files, "config/database.yml", "config/secrets.yml"
set :linked_files, fetch(:linked_files, []).push('.env')

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"
set :linked_dirs, fetch(:linked_dirs, []).push('public/files', 'storage/framework/cache')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5
set :keep_releases, 3

namespace :deploy do

  desc 'Restart application'
  task :restart do
    on roles(:app), in: :sequence, wait: 5 do
      execute "touch #{release_path.join('storage/logs/laravel.log')}"
      execute "chmod +w #{release_path.join('storage')} -R"
      execute "cd '#{release_path}'; composer install"
      execute "cd '#{release_path}'; php artisan migrate -n --force"
    end
  end

  before :publishing, :restart
end
