# Create easy_doc database

execute "create ez_doc database" do
  begin
	command '/usr/bin/mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS easy_doc"'
  rescue Exception => msg
    puts msg
  end
end