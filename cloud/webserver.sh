#!/bin/bash

#---------------
# Install Apache
#---------------

# Drupal requires gd to resize images
sudo DEBIAN_FRONTEND=noninteractive apt-get -y install apache2 libapache2-mod-php5 php5-gd mysql-client

# Enable modules required by Drupal
#----------------------------------

# Enable PHP
sudo a2enmod php5

# Enable rewrite, so that Drupal can create human-friendly URLs
sudo a2enmod rewrite

# Enable headers, so we can serve gzip compressed CSS and JS files
sudo a2enmod headers

# Enable SSL, so we can serve encrypted content over HTTPS
sudo a2enmod ssl
a2ensite default-ssl

# Generate self-signed certificate
#---------------------------------

sudo mkdir /etc/apache2/ssl
sudo openssl req -x509 -nodes -days 365 -subj '/CN=webserver.domain_name' -newkey rsa:2048 -keyout /etc/apache2/ssl/apache.key -out /etc/apache2/ssl/apache.crt
# TODO: allow existing certificate to be uploaded

# Configure the vHost
#--------------------

(cat << EOF
<VirtualHost *:80>
    ServerAdmin webmaster@domain_name
    DocumentRoot /var/www/drupal
    <Directory /var/www/drupal>
        AllowOverride All
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
) | sudo tee /etc/apache2/sites-available/000-default.conf

(cat << EOF
<IfModule mod_ssl.c>
    <VirtualHost _default_:443>
        ServerAdmin webmaster@domain_name
        DocumentRoot /var/www/drupal
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
        SSLEngine on
        SSLCertificateFile /etc/apache2/ssl/apache.crt
        SSLCertificateKeyFile /etc/apache2/ssl/apache.key
        <FilesMatch "\.(cgi|shtml|phtml|php)$">
                        SSLOptions +StdEnvVars
        </FilesMatch>
        <Directory /usr/lib/cgi-bin>
                        SSLOptions +StdEnvVars
        </Directory>
        BrowserMatch "MSIE [2-6]" \
                        nokeepalive ssl-unclean-shutdown \
                        downgrade-1.0 force-response-1.0
        BrowserMatch "MSIE [17-9]" ssl-unclean-shutdown
    </VirtualHost>
</IfModule>
EOF
) | sudo tee /etc/apache2/sites-available/default-ssl.conf

# Configure PHP
#--------------

# Tweak php.ini as per https://www.drupal.org/requirements/php
sudo sed -i 's/^\(post_max_size\s*=\s*\).*$/\110M/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(upload_max_filesize\s*=\s*\).*$/\110M/' /etc/php5/apache2/php.ini
sudo sed -i 's/;realpath_cache_size/realpath_cache_size/' /etc/php5/apache2/php.ini
sudo sed -i 's/;realpath_cache_ttl/realpath_cache_ttl/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(realpath_cache_size\s*=\s*\).*$/\164k/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(realpath_cache_ttl\s*=\s*\).*$/\13600/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(error_reporting\s*=\s*\).*$/\1E_ALL \& ~E_NOTICE/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(session.cache_limiter\s*=\s*\).*$/\1nocache/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(session.auto_start\s*=\s*\).*$/\10/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(expose_php\s*=\s*\).*$/\1off/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(allow_url_fopen\s*=\s*\).*$/\1off/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(magic_quotes_gpc\s*=\s*\).*$/\1off/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(register_globals\s*=\s*\).*$/\1off/' /etc/php5/apache2/php.ini
sudo sed -i 's/^\(register_globals\s*=\s*\).*$/\1Off/' /etc/php5/apache2/php.ini
sudo sed -i 's/;opcache.enable=0/opcache.enable=1/' /etc/php5/apache2/php.ini

# Enable the opcache module
sudo php5enmod opcache

# Restart apache to apply the changes
sudo service apache2 restart

#----------------
# Install Drupal
#----------------

# Install drush
sudo DEBIAN_FRONTEND=noninteractive apt-get -y install drush

# Create groups for drupal
sudo groupadd drupal

# Create users for drupal
# -- system: system user
# -M: do not create home directory
# -N: do not create group with the same name as the user
# -g: add user to group
sudo useradd -N -g drupal --shell /bin/bash --create-home --home /home/drupal drupal

DRUPAL_SOURCE="drupal_source"

# Deploy the Drupal 7 codebase from drush or git repo
if [[ "${DRUPAL_SOURCE}" == "drush" ]]; then
  drush dl drupal-7.x
  sudo mv drupal-7.x-dev /var/www/drupal
else
  git clone ${DRUPAL_SOURCE} drupal
  sudo mv drupal /var/www/drupal
fi

# Secure it with the appropriate permissions
sudo chown -R drupal.drupal /var/www/drupal
sudo chgrp www-data /var/www/drupal/sites/default
sudo chmod 554 /var/www/drupal/sites/default
sudo mkdir -p /var/www/drupal/sites/default/files
sudo chgrp www-data /var/www/drupal/sites/default/files
sudo chmod 775 /var/www/drupal/sites/default/files

# Create a new settings file and secure it
sudo cp /var/www/drupal/sites/default/default.settings.php /var/www/drupal/sites/default/settings.php
sudo chown drupal.drupal /var/www/drupal/sites/default/settings.php
sudo chmod 644 /var/www/drupal/sites/default/settings.php

# If this is a new install, then perform the automated site install process
if [[ "${DRUPAL_SOURCE}" == "drush" ]]; then
  cd /var/www/drupal
  sudo drush -y site-install standard --account-name=drupal_user --account-pass=drupal_password --db-url=mysql://db_user:db_password@db_ipaddr/db_name
fi

#---------
# Backups
#---------

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
sudo su - drupal -c "cd /var/www/drupal && drush archive-dump"
test -x /usr/bin/s3cmd || exit 0
/usr/bin/s3cmd --config=/home/ubuntu/.s3cfg sync /home/drupal/drush-backups/ s3://$S3_BUCKET_NAME/drush-backups/
EOF
) | sudo tee /etc/cron.daily/drupal-backup

sudo chmod +x /etc/cron.daily/drupal-backup

# To trigger a manual backup
# sudo s3cmd --config=/home/ubuntu/.s3cfg sync /home/drupal/drush-backups/ s3://$S3_BUCKET_NAME/drush-backups/

# To trigger a manual restore
# sudo s3cmd --config=/home/ubuntu/.s3cfg sync s3://$S3_BUCKET_NAME/drush-backups/ /home/drupal/drush-backups/

# Backups will accumulate over time (sync is configured not to delete removed
# files automatically). To clean up old backups do:
# sudo s3cmd --config=/home/ubuntu/.s3cfg --delete-removed sync /home/drupal/drush-backups/ s3://$S3_BUCKET_NAME/automysqlbackup/

