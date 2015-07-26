#!/bin/bash

#--------------
# Install MySQL
#--------------

sudo DEBIAN_FRONTEND=noninteractive apt-get -y install mysql-server

# To connect to mysql: mysql -u root -h ${PRIVATE_IP}

# Configure MySQL
#----------------

# Find out the IP associated to eth0
#PRIVATE_IP=$(ip addr list eth0 | grep "inet " | cut -d' ' -f6 | cut -d'/' -f1)

sudo cp /etc/mysql/my.cnf /etc/mysql/my.cnf.bkp
(cat << EOF
[mysql]

# CLIENT #
port                           = 3306
socket                         = /var/lib/mysql/mysql.sock

[mysqld_safe]

socket                         = /var/run/mysqld/mysqld.sock
nice                           = 0

[mysqld]

# GENERAL #
user                           = mysql
default-storage-engine         = InnoDB
socket                         = /var/run/mysqld/mysqld.sock
pid-file                       = /var/run/mysqld/mysqld.pid
bind-address                   = 0.0.0.0
port                           = 3306

# MyISAM #
key-buffer-size                = 32M
myisam-recover-options         = FORCE,BACKUP
skip-external-locking

# SAFETY #
max-allowed-packet             = 16M
max-connect-errors             = 1000000
skip-name-resolve
sysdate-is-now                 = 1
innodb                         = FORCE

# DATA STORAGE #
datadir                        = /var/lib/mysql
tmpdir                         = /tmp
basedir                        = /usr
lc-messages-dir                = /usr/share/mysql

# BINARY LOGGING #
log-bin                        = /var/lib/mysql/mysql-bin
expire-logs-days               = 14
sync-binlog                    = 1

# CACHES AND LIMITS #
tmp-table-size                 = 32M
max-heap-table-size            = 32M
query-cache-type               = 0
query-cache-size               = 0
max-connections                = 500
thread-stack                   = 192K
thread-cache-size              = 50
open-files-limit               = 65535
table-definition-cache         = 1024
table-open-cache               = 2048

# INNODB #
innodb-flush-method            = O_DIRECT
innodb-log-files-in-group      = 2
innodb-log-file-size           = 5M
innodb-flush-log-at-trx-commit = 1
innodb-file-per-table          = 1
innodb-buffer-pool-size        = 250M

# LOGGING #
log-error                      = /var/log/mysql/error.log
log-queries-not-using-indexes  = 1
slow-query-log                 = 1
slow-query-log-file            = /var/log/mysql/mysql-slow.log

[mysqldump]

quick
quote-names
max_allowed_packet             = 16M

[isamchk]

key_buffer                     = 16M

#
# * IMPORTANT: Additional settings that can override those from this file!
#   The files must end with '.cnf', otherwise they will be ignored.
#
!includedir /etc/mysql/conf.d/
EOF
) | sudo tee /etc/mysql/my.cnf

# Restart the database to apply the configuration
sudo service mysql restart

# Create backup user
(cat << EOF
use mysql
GRANT LOCK TABLES, SELECT ON *.* TO 'backup'@'%' IDENTIFIED BY 'db_password';
flush privileges;
exit
EOF
) | sudo mysql -u root -h 127.0.0.1

# Create website databases and grant DB access to corresponding drupal users
(cat << EOF
use mysql
CREATE DATABASE db_name CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES ON \`db_name\`.* TO 'db_user'@'%' IDENTIFIED BY 'db_password';
flush privileges;
exit
EOF
) | sudo mysql -u root -h 127.0.0.1

# To connect to the database from the webserver host: mysql -h ${PRIVATE_IP} -u ${USER} -p

# TODO: Allow to import existing Drupal database

#---------
# Backups
#---------

# Implement backup for MySQL
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y automysqlbackup
sudo sed -i 's/^#USERNAME=.*$/USERNAME=backup/' /etc/default/automysqlbackup
sudo sed -i 's/^#PASSWORD=.*$/PASSWORD=db_password/' /etc/default/automysqlbackup
sudo sed -i 's/^\(DBHOST\s*=\s*\).*$/\1127.0.0.1/' /etc/default/automysqlbackup

# Backup data to an  Amazon AWS S3 bucket encrypted using a password.

# Install s3cmd for backups
sudo DEBIAN_FRONTEND=noninteractive apt-get -y install s3cmd

# Configure s3cmd
S3_ACCESS_KEY="s3_access_key"
S3_SECRET_KEY="s3_secret_key"
S3_ENCRYPTION_PASSWORD="s3_encryption_password"
S3_BUCKET_NAME="s3_bucket_name"
(cat << EOF
[default]
access_key = $S3_ACCESS_KEY
bucket_location = US
cloudfront_host = cloudfront.amazonaws.com
default_mime_type = binary/octet-stream
delete_removed = False
dry_run = False
enable_multipart = True
encoding = UTF-8
encrypt = False
follow_symlinks = False
force = False
get_continue = False
gpg_command = /usr/bin/gpg
gpg_decrypt = %(gpg_command)s -d --verbose --no-use-agent --batch --yes --passphrase-fd %(passphrase_fd)s -o %(output_file)s %(input_file)s
gpg_encrypt = %(gpg_command)s -c --verbose --no-use-agent --batch --yes --passphrase-fd %(passphrase_fd)s -o %(output_file)s %(input_file)s
gpg_passphrase = $ENCRYPTION_PASSWORD
guess_mime_type = True
host_base = s3.amazonaws.com
host_bucket = %(bucket)s.s3.amazonaws.com
human_readable_sizes = False
invalidate_on_cf = False
list_md5 = False
log_target_prefix =
mime_type =
multipart_chunk_size_mb = 15
preserve_attrs = True
progress_meter = True
proxy_host =
proxy_port = 0
recursive = False
recv_chunk = 4096
reduced_redundancy = False
secret_key = $S3_SECRET_KEY
send_chunk = 4096
simpledb_host = sdb.amazonaws.com
skip_existing = False
socket_timeout = 300
urlencoding_mode = normal
use_https = True
verbosity = WARNING
website_endpoint = http://%(bucket)s.s3-website-%(location)s.amazonaws.com/
website_error =
website_index = index.html
EOF
) | sudo tee /home/ubuntu/.s3cfg

# Secure config file
sudo chmod 400 ~/.s3cfg

# Set up cron job to run the backup task every day
(cat << EOF
#!/bin/bash
test -x /usr/bin/s3cmd || exit 0
/usr/bin/s3cmd --config=/home/ubuntu/.s3cfg sync /var/lib/automysqlbackup/ s3://$S3_BUCKET_NAME/automysqlbackup/
EOF
) | sudo tee /etc/cron.daily/s3sync

sudo chmod +x /etc/cron.daily/s3sync

# To trigger a manual backup
# s3cmd --config=/home/ubuntu/.s3cfg sync /var/lib/automysqlbackup/ s3://$S3_BUCKET_NAME/automysqlbackup/

# To trigger a manual restore
# s3cmd --config=/home/ubuntu/.s3cfg sync s3://$S3_BUCKET_NAME/automysqlbackup/ /var/lib/automysqlbackup/

# Backups will accumulate over time (sync is configured not to delete removed
# files automatically). To clean up old backups do:
# /usr/bin/s3cmd --config=/home/ubuntu/.s3cfg --delete-removed sync /var/lib/automysqlbackup/ s3://$S3_BUCKET_NAME/automysqlbackup/

