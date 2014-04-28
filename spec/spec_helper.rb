require 'dotenv'
require 'fileutils'

require 'mysql2'

require 'capybara/rspec'
require 'capybara-webkit'
#require 'capybara-screenshot/rspec'

Dotenv.load


# You need to set up this database in mysql.
WP_CONFIG   = ENV.fetch('WP_CONFIG',   '../../../wp-config.php')

DB_HOST     = ENV.fetch('DB_HOST',     'localhost')
DB_USER     = ENV.fetch('DB_USER',     'ivanhoe')
DB_PASSWORD = ENV.fetch('DB_PASSWORD', 'ivanhoe')
DB_NAME     = ENV.fetch('DB_NAME',     'test_ivanhoe')
URL_BASE    = ENV.fetch('URL_BASE',    'http://localhost:8888/ivanhoe')

DB_DUMP     = "./spec/dumps/ivanhoe.sql"


Capybara.default_driver    = :webkit
Capybara.javascript_driver = :webkit

RSpec.configure do |config|

  # Patch wp config for testing.
  config.before(:suite) do |ex|
    Dir.mkdir('./tmp') unless Dir.exists?('./tmp')

    FileUtils.cp(WP_CONFIG, './tmp/wp-config.php')
    File.open(WP_CONFIG, mode='w') do |f|
      IO.readlines('./tmp/wp-config.php').each do |line|
        if line.start_with?("define('DB_NAME'")
          line = "define('DB_NAME',     '#{DB_NAME}');\n# #{line}"
        elsif line.start_with?("define('DB_USER'")
          line = "define('DB_USER',     '#{DB_USER}');\n# #{line}"
        elsif line.start_with?("define('DB_PASSWORD'")
          line = "define('DB_PASSWORD', '#{DB_PASSWORD}');\n# #{line}"
        elsif line.start_with?("define('DB_HOST'")
          line = "define('DB_HOST',     '#{DB_HOST}');\n# #{line}"
        end

        f.write(line)
      end
    end
  end

  config.after(:suite) do |ex|
    FileUtils.cp('./tmp/wp-config.php', WP_CONFIG)
  end

  # Load and tear down database fixtures.
  config.before(:suite) do |ex|
    cxn = Mysql2::Client.new(:host => DB_HOST,
                             :username => DB_USER,
                             :password => DB_PASSWORD)
    cxn.query("CREATE DATABASE #{DB_NAME}")
    puts "Creating database..."
    system("mysql -h#{DB_HOST} -u#{DB_USER} --password=#{DB_PASSWORD} #{DB_NAME} < #{DB_DUMP}")
    cxn.close
  end

  config.after(:suite) do |ex|
    cxn = Mysql2::Client.new(:host => DB_HOST,
                             :username => DB_USER,
                             :password => DB_PASSWORD)

    cxn.query("DROP DATABASE IF EXISTS #{DB_NAME};")
    cxn.close
  end
end
