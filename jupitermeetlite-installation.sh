#!/bin/bash
# Copyright 2025 TheRTCLabs - All Rights Reserved
# Author: The RTC Labs (Ishan Shah)
# You should have received a copy of JupiterMeet license with
# this file. If not, please write to: hello@jupiters.tech, or visit: jupiters.tech

DOMAIN_ARG=${1:-""}
EMAIL_ARG=${2:-""}

# colors
NOCOLOR='\033[0m'
LIGHTRED='\033[1;31m'
LIGHTGREEN='\033[1;32m'
LIGHTBLUE='\033[1;34m'
LIGHTCYAN='\033[1;36m'
YELLOW='\033[1;33m'

# jupitermeet installation for ubuntu 24.04
function ubuntu_24_04 {

    # welcome message
    echo ""
    echo -e "${LIGHTCYAN}WELCOME TO THE JupiterMeet Lite INSTALLATION!${NOCOLOR}"
    echo ""
    # variables
    if [ -f /root/.my.cnf ]; then
        echo "Found existing MySQL configuration. Reusing existing credentials."
        a=$(grep -i '^password=' /root/.my.cnf | head -n 1 | cut -d'=' -f2- | tr -d ' "')
    fi

    if [ -z "${a:-""}" ]; then
        pw_chars="A-Za-z0-9_@.-"
        while true; do
            a=$(tr -dc "$pw_chars" </dev/urandom 2>/dev/null | head -c 30)
            if [[ "$a" =~ [A-Z] ]] && [[ "$a" =~ [a-z] ]] && [[ "$a" =~ [0-9] ]] && [[ "$a" =~ [_@.-] ]]; then
                break
            fi
        done
    fi
    b=$(tr -dc 'A-Z0-9a-z' </dev/urandom 2>/dev/null | head -c 10)
    c=$(tr -dc 'A-Z0-9a-z' </dev/urandom 2>/dev/null | head -c 16)

    domain=$DOMAIN_ARG
    email=$EMAIL_ARG

    if [ -z "$domain" ] || [ -z "$email" ]; then
        echo "ERROR: Domain and email must be passed as CLI arguments."
        exit 1
    fi

    # update system
    export DEBIAN_FRONTEND=noninteractive

    apt update -y

    apt install software-properties-common -y

    apt install expect -y

    echo ">>> Running system upgrade..."
    apt-get -y -o Dpkg::Options::="--force-confold" -o Dpkg::Options::="--force-confdef" full-upgrade

    # install php8.3
    add-apt-repository ppa:ondrej/php -y
    apt install php8.3 -y
    apt install php8.3-mbstring php8.3-mysqli php8.3-curl php8.3-dom php8.3-xml php8.3-xmlwriter php8.3-common php8.3-zip php8.3-bcmath php8.3-gettext -y

    # install apache with certbot
    apt install python3-certbot-apache -y

    # install mysql
    apt install mysql-server -y

    echo ">>> Securing MySQL server..."
    systemctl start mysql || true

    if mysql --no-defaults -u root -e "status" >/dev/null 2>&1; then
        echo ">>> Setting root password..."
        mysql --no-defaults -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${a}';" 2>/dev/null || \
        mysql --no-defaults -u root -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('${a}');" 2>/dev/null || true
    elif [ -f /etc/mysql/debian.cnf ]; then
        echo ">>> Setting root password via debian-sys-maint..."
        mysql --defaults-extra-file=/etc/mysql/debian.cnf -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${a}';" 2>/dev/null || true
    fi

    if mysql --no-defaults -u root -p"${a}" -e "status" >/dev/null 2>&1; then
        MYSQL_CMD="mysql --no-defaults -u root -p${a}"
    elif [ -f /etc/mysql/debian.cnf ]; then
        MYSQL_CMD="mysql --defaults-extra-file=/etc/mysql/debian.cnf"
    else
        MYSQL_CMD="mysql"
    fi

    $MYSQL_CMD -e "DELETE FROM mysql.user WHERE User='';"
    $MYSQL_CMD -e "DROP DATABASE IF EXISTS test;"
    $MYSQL_CMD -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
    $MYSQL_CMD -e "FLUSH PRIVILEGES;"

    cat > /root/.my.cnf << EOF
[client]
user=root
password=$a
EOF
    chmod 600 /root/.my.cnf

    # install phpmyadmin
    echo "phpmyadmin phpmyadmin/dbconfig-install boolean false" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2" | debconf-set-selections

    apt-get install phpmyadmin -y

    phpenmod mbstring

    ln -s /usr/share/phpmyadmin /var/www/html

    systemctl restart apache2

    # install nodejs and npm
    apt install curl -y

    curl -sL https://deb.nodesource.com/setup_24.x | bash -
    apt install nodejs -y
    npm i -g pm2

    # Prepare npm and pm2 environments to avoid permission errors
    mkdir -p /var/www/.pm2 /var/www/.npm
    chown -R www-data:www-data /var/www/.pm2 /var/www/.npm

    # install turn
    apt install coturn -y

    # create database
    $MYSQL_CMD -e "create database if not exists jupitermeet_lite;"
    $MYSQL_CMD -e "alter database jupitermeet_lite character set utf8 collate utf8_general_ci;"
    $MYSQL_CMD -e "use mysql; alter user 'root'@'localhost' identified with mysql_native_password BY '${a}'; flush privileges;" 2>/dev/null || \
    $MYSQL_CMD -e "use mysql; alter user 'root'@'localhost' identified with caching_sha2_password BY '${a}'; flush privileges;" 2>/dev/null || \
    $MYSQL_CMD -e "use mysql; alter user 'root'@'localhost' identified BY '${a}'; flush privileges;" 2>/dev/null || true

    # configure apache
    touch /etc/apache2/sites-available/$domain.conf

    echo "<VirtualHost *:80>

ServerAdmin webmaster@localhost

ServerName $domain
ServerAlias www.$domain

DocumentRoot /var/www/html/jupitermeet_lite/public

<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>

</VirtualHost>" > /etc/apache2/sites-available/$domain.conf

    a2ensite $domain.conf
    a2dissite 000-default.conf

    a2enmod rewrite

    systemctl restart apache2

    # setting up ssl
    certbot_ok=0
    if certbot --apache --non-interactive --agree-tos --keep-until-expiring -d $domain -m $email; then
        certbot_ok=1
    else
        echo ">>> WARNING: Certbot could not issue an SSL certificate (see error above)."
        echo ">>> JupiterMeet Lite will be installed over HTTP. You can run certbot manually later:"
        echo ">>>   certbot --apache -d $domain -m $email"
    fi

    if [ $certbot_ok -eq 1 ]; then
        head -n -1 /etc/apache2/sites-available/$domain.conf > /etc/apache2/sites-available/$domain.conf.temp
        mv /etc/apache2/sites-available/$domain.conf.temp /etc/apache2/sites-available/$domain.conf
        echo "Redirect permanent / https://$domain/

</VirtualHost>" >> /etc/apache2/sites-available/$domain.conf

        echo ">>> Setting up certificate hooks for Node.js app..."
        mkdir -p /var/lib/jupitermeet_lite/certs
        chown www-data:www-data /var/lib/jupitermeet_lite/certs
        chmod 700 /var/lib/jupitermeet_lite/certs

        cat > /etc/letsencrypt/renewal-hooks/post/copy-certs.sh << 'EOF'
#!/bin/bash
DOMAIN="yourdomain.com"
CERT_DEST="/var/lib/jupitermeet_lite/certs"
PM2_APP_NAME="jupitermeet_lite-server"

cp /etc/letsencrypt/live/$DOMAIN/fullchain.pem $CERT_DEST/
cp /etc/letsencrypt/live/$DOMAIN/privkey.pem $CERT_DEST/

chown www-data:www-data $CERT_DEST/*.pem
chmod 600 $CERT_DEST/*.pem

# Reload PM2 application
cd /var/www/html/jupitermeet_lite/server || exit 0
sudo -u www-data pm2 reload $PM2_APP_NAME || true
EOF
        sed -i "s/yourdomain.com/$domain/g" /etc/letsencrypt/renewal-hooks/post/copy-certs.sh
        chmod +x /etc/letsencrypt/renewal-hooks/post/copy-certs.sh

        # Initial manual run
        bash /etc/letsencrypt/renewal-hooks/post/copy-certs.sh
    fi

    systemctl restart apache2

    # setting up installation folder
    mkdir /var/www/html/jupitermeet_lite
    chown $USER:www-data /var/www/html/jupitermeet_lite

    apt install unzip -y

    # quiet extraction to prevent unresponsiveness
    echo ">>> Extracting JupiterMeet Lite files (quiet mode)..."
    unzip -q -o jupitermeet_lite.zip -d /var/www/html/jupitermeet_lite

    cd /var/www/html/jupitermeet_lite || { echo "ERROR: Failed to enter install directory"; exit 1; }

    # Configure Laravel session security and APP_URL
    if [ -f config/session.php ]; then
        sed -i "s/'same_site' => 'none'/'same_site' => env('SESSION_SAME_SITE', 'lax')/g" config/session.php
    fi

    if [ -f .env ]; then
        sed -i '/SESSION_SECURE_COOKIE/d' .env
        sed -i '/SESSION_SAME_SITE/d' .env
        sed -i '/APP_URL/d' .env
        echo ">>> Configuring session cookies and URL for secure HTTPS deployment..."
        echo "SESSION_SECURE_COOKIE=true" >> .env
        echo "SESSION_SAME_SITE=none" >> .env
        echo "APP_URL=https://$domain" >> .env
    fi

    chmod -R 775 .
    chown -R www-data:www-data . 2>/dev/null || chown -R $USER:www-data . || true

    echo ">>> Setting up PM2 startup..."
    cd /var/www/html/jupitermeet_lite/server
    sudo -u www-data npm install
    
    pm2_startup_output=$(env PATH=$PATH:/usr/bin pm2 startup systemd -u www-data --hp /var/www)
    startup_cmd=$(echo "$pm2_startup_output" | grep "sudo env" | head -n 1)
    if [ -n "$startup_cmd" ]; then
        eval "${startup_cmd#sudo }"
    fi

    cat > /var/www/html/jupitermeet_lite/start-pm2.sh << 'EOF'
#!/bin/bash
export HOME=/var/www
export PATH=$PATH:/usr/local/bin:/usr/bin
cd /var/www/html/jupitermeet_lite/server
pm2 restart jupitermeet_lite-server || pm2 start app.js --name jupitermeet_lite-server
pm2 save
EOF
    chmod +x /var/www/html/jupitermeet_lite/start-pm2.sh
    chown www-data:www-data /var/www/html/jupitermeet_lite/start-pm2.sh
    sudo -u www-data bash /var/www/html/jupitermeet_lite/start-pm2.sh

    # installation log
    echo ""
    echo -e "${LIGHTGREEN}INSTALLATION HAS BEEN SUCCESSFULLY COMPLETED!${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}Installation Log${NOCOLOR}"
    echo ""
    if dpkg -s php8.3 &> /dev/null
    then
    echo -e "PHP8.3    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "PHP8.3    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s apache2 &> /dev/null
    then
    echo -e "Apache    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Apache    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s certbot &> /dev/null
    then
    echo -e "Certbot   : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Certbot   : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s mysql-server &> /dev/null
    then
    echo -e "MySQL     : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "MySQL     : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s phpmyadmin &> /dev/null
    then
    echo -e "phpMyAdmin: ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "phpMyAdmin: ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s nodejs &> /dev/null
    then
    echo -e "NodeJS    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NodeJS    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if npm -v &> /dev/null
    then
    echo -e "NPM       : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NPM       : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if dpkg -s coturn &> /dev/null
    then
    echo -e "TURN      : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "TURN      : ${LIGHTRED}Not installed${NOCOLOR}"
    fi

    # print paths and credentials
    echo ""
    echo -e "${LIGHTRED}IMPORTANT: ${NOCOLOR}Please copy and save following paths and credentials for your future reference."
    echo ""
    _proto="https"; [ "${certbot_ok:-0}" -eq 0 ] && _proto="http"
    echo -e "${LIGHTCYAN}JupiterMeet Lite${NOCOLOR}
URL     : ${YELLOW}${_proto}://$domain/install${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}MySQL${NOCOLOR}
Database: ${YELLOW}jupitermeet_lite${NOCOLOR}
User    : ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}phpMyAdmin${NOCOLOR}
URL     : ${YELLOW}${_proto}://$domain/phpmyadmin${NOCOLOR}
Username: ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    if [ "${certbot_ok:-0}" -eq 1 ]; then
        echo -e "${LIGHTCYAN}SSL${NOCOLOR}
Cert    : ${YELLOW}/etc/letsencrypt/live/$domain/fullchain.pem${NOCOLOR}
Pkey    : ${YELLOW}/etc/letsencrypt/live/$domain/privkey.pem${NOCOLOR}"
        echo ""
    fi
}

# JupiterMeet Lite installation for centos 7
function centos_7 {

    # welcome message
    echo ""
    echo -e "${LIGHTCYAN}WELCOME TO THE JupiterMeet Lite INSTALLATION!${NOCOLOR}"
    echo ""
    # variables
    if [ -f /root/.my.cnf ]; then
        echo "Found existing MySQL configuration. Reusing existing credentials."
        a=$(grep -i '^password=' /root/.my.cnf | head -n 1 | cut -d'=' -f2- | tr -d ' "')
    fi

    if [ -z "${a:-""}" ]; then
        pw_chars="A-Za-z0-9_@.-"
        while true; do
            a=$(tr -dc "$pw_chars" </dev/urandom 2>/dev/null | head -c 30)
            if [[ "$a" =~ [A-Z] ]] && [[ "$a" =~ [a-z] ]] && [[ "$a" =~ [0-9] ]] && [[ "$a" =~ [_@.-] ]]; then
                break
            fi
        done
    fi
    b=$(tr -dc 'A-Z0-9a-z' </dev/urandom 2>/dev/null | head -c 10)
    c=$(tr -dc 'A-Z0-9a-z' </dev/urandom 2>/dev/null | head -c 16)

    domain=$DOMAIN_ARG
    email=$EMAIL_ARG

    if [ -z "$domain" ] || [ -z "$email" ]; then
        echo "ERROR: Domain and email must be passed as CLI arguments."
        exit 1
    fi

    # update system
    export DEBIAN_FRONTEND=noninteractive

    yum update -y

    yum install centos-release-scl -y
    yum install devtoolset-8 -y
    source /opt/rh/devtoolset-8/enable

    yum install expect -y

    # install php8.3
    yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -y
    yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm -y

    yum install yum-utils -y

    yum-config-manager --enable remi-php81

    yum install php php-mbstring php-mysqli php-curl php-dom php-xml php-xmlwriter php-common php-json php-zip php-bcmath php-gettext -y

    # install apache with certbot
    yum install python3-certbot-apache -y

    systemctl enable httpd.service

    systemctl start httpd.service

    # install mysql
    yum install mariadb-server mariadb -y

    systemctl enable mariadb.service

    systemctl start mariadb.service

    # install phpmyadmin
    yum install phpmyadmin -y

    # install nodejs and npm
    yum install curl -y

    curl -sL https://rpm.nodesource.com/setup_24.x | bash -

    yum install nodejs -y

    npm i -g pm2

    # Prepare npm and pm2 environments to avoid permission errors
    mkdir -p /usr/share/httpd/.pm2 /usr/share/httpd/.npm
    chown -R apache:apache /usr/share/httpd/.pm2 /usr/share/httpd/.npm

    #yum install npm -y

    # install turn
    yum install coturn -y

    # Secure MySQL directly via SQL
    echo ">>> Securing MySQL server..."
    if mysql --no-defaults -u root -e "status" >/dev/null 2>&1; then
        mysql --no-defaults -u root -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('${a}');" 2>/dev/null || true
    fi

    if mysql --no-defaults -u root -p"${a}" -e "status" >/dev/null 2>&1; then
        MYSQL_CMD="mysql --no-defaults -u root -p${a}"
    else
        MYSQL_CMD="mysql"
    fi

    $MYSQL_CMD -e "DELETE FROM mysql.user WHERE User='';"
    $MYSQL_CMD -e "DROP DATABASE IF EXISTS test;"
    $MYSQL_CMD -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
    $MYSQL_CMD -e "FLUSH PRIVILEGES;"

    cat > /root/.my.cnf << EOF
[client]
user=root
password=$a
EOF
    chmod 600 /root/.my.cnf

    # create database
    $MYSQL_CMD -e "create database if not exists jupitermeet_lite;"
    $MYSQL_CMD -e "alter database jupitermeet_lite character set utf8 collate utf8_general_ci;"
    $MYSQL_CMD -e "set password for 'root'@'localhost' = password('${a}'); flush privileges;" 2>/dev/null || true

    # configure apache
    mkdir /etc/httpd/sites-available
    mkdir /etc/httpd/sites-enabled

    echo "IncludeOptional /etc/httpd/sites-enabled/*.conf" >> /etc/httpd/conf/httpd.conf

    echo "<VirtualHost *:80>

    ServerName $domain
    ServerAlias www.$domain
    DocumentRoot /var/www/html/jupitermeet_lite/public

<Directory /var/www/html>
    AllowOverride All
    Require all granted
</Directory>

</VirtualHost>"> /etc/httpd/sites-available/$domain.conf

    sudo ln -s /etc/httpd/sites-available/$domain.conf /etc/httpd/sites-enabled/$domain.conf

    mv /etc/httpd/conf.d/welcome.conf /etc/httpd/conf.d/welcome.conf_backup

    systemctl restart httpd.service

    # configure phpmyadmin
    rm -rf /etc/httpd/conf.d/phpMyAdmin.conf

    echo "# phpMyAdmin - Web based MySQL browser written in php
#
# Allows only localhost by default
#
# But allowing phpMyAdmin to anyone other than localhost should be considered
# dangerous unless properly secured by SSL

Alias /phpMyAdmin /usr/share/phpMyAdmin
Alias /phpmyadmin /usr/share/phpMyAdmin

<Directory /usr/share/phpMyAdmin/>
   AddDefaultCharset UTF-8

   <IfModule mod_authz_core.c>
     # Apache 2.4
     <RequireAny>
       Require ip 127.0.0.1
       Require ip ::1
       Require all granted
     </RequireAny>
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from 127.0.0.1
     Allow from ::1
   </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     <RequireAny>
       Require ip 127.0.0.1
       Require ip ::1
     </RequireAny>
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from 127.0.0.1
     Allow from ::1
   </IfModule>
</Directory>

# These directories do not require access over HTTP - taken from the original
# phpMyAdmin upstream tarball
#
<Directory /usr/share/phpMyAdmin/libraries/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     Require all denied
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from None
   </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/lib/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     Require all denied
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from None
   </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/frames/>
   <IfModule mod_authz_core.c>
     # Apache 2.4
     Require all denied
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from None
   </IfModule>
</Directory>

# This configuration prevents mod_security at phpMyAdmin directories from
# filtering SQL etc.  This may break your mod_security implementation.
#
#<IfModule mod_security.c>
#    <Directory /usr/share/phpMyAdmin/>
#        SecRuleInheritance Off
#    </Directory>
#</IfModule>" > /etc/httpd/conf.d/phpMyAdmin.conf

    ln -s /usr/share/phpMyAdmin /var/www/html

    systemctl restart httpd.service

    # setting up ssl
    certbot_ok=0
    if certbot --apache --non-interactive --agree-tos --keep-until-expiring -d $domain -m $email; then
        certbot_ok=1
    else
        echo ">>> WARNING: Certbot could not issue an SSL certificate (see error above)."
        echo ">>> JupiterMeet Lite will be installed over HTTP. You can run certbot manually later:"
        echo ">>>   certbot --apache -d $domain -m $email"
    fi

    if [ $certbot_ok -eq 1 ]; then
        sudo ln -sf /etc/httpd/sites-available/$domain-le-ssl.conf /etc/httpd/sites-enabled/$domain-le-ssl.conf

        echo ">>> Setting up certificate hooks for Node.js app..."
        mkdir -p /var/lib/jupitermeet_lite/certs
        chown apache:apache /var/lib/jupitermeet_lite/certs
        chmod 700 /var/lib/jupitermeet_lite/certs

        cat > /etc/letsencrypt/renewal-hooks/post/copy-certs.sh << 'EOF'
#!/bin/bash
DOMAIN="yourdomain.com"
CERT_DEST="/var/lib/jupitermeet_lite/certs"
PM2_APP_NAME="jupitermeet_lite-server"

cp /etc/letsencrypt/live/$DOMAIN/fullchain.pem $CERT_DEST/
cp /etc/letsencrypt/live/$DOMAIN/privkey.pem $CERT_DEST/

chown apache:apache $CERT_DEST/*.pem
chmod 600 $CERT_DEST/*.pem

# Reload PM2 application
cd /var/www/html/jupitermeet_lite/server || exit 0
sudo -u apache pm2 reload $PM2_APP_NAME || true
EOF
        sed -i "s/yourdomain.com/$domain/g" /etc/letsencrypt/renewal-hooks/post/copy-certs.sh
        chmod +x /etc/letsencrypt/renewal-hooks/post/copy-certs.sh

        # Initial manual run
        bash /etc/letsencrypt/renewal-hooks/post/copy-certs.sh
    fi

    systemctl stop firewalld

    systemctl restart httpd

    # renew ssl
    crontab -l | { cat; echo "0 0 */60 * * certbot renew >> /run/log/certbot-cron.log 2>&1"; } | crontab -

        # configure turn
    mv /etc/coturn/turnserver.conf /etc/coturn/turnserver.conf.temp

    echo "cert=/etc/letsencrypt/live/$domain/fullchain.pem
pkey=/etc/letsencrypt/live/$domain/privkey.pem
user=$b:$c
realm=$domain
lt-cred-mech
" > /etc/coturn/turnserver.conf

    cat /etc/coturn/turnserver.conf.temp >> /etc/coturn/turnserver.conf

    rm -rf /etc/coturn/turnserver.conf.temp

    # setting up installation folder
    mkdir /var/www/html/jupitermeet_lite
    chown $USER:apache /var/www/html/jupitermeet_lite

    yum install unzip -y

    # quiet extraction to prevent unresponsiveness
    echo ">>> Extracting JupiterMeet Lite files (quiet mode)..."
    unzip -q -o jupitermeet_lite.zip -d /var/www/html/jupitermeet_lite

    cd /var/www/html/jupitermeet_lite || { echo "ERROR: Failed to enter install directory"; exit 1; }

    # Configure Laravel session security and APP_URL
    if [ -f config/session.php ]; then
        sed -i "s/'same_site' => 'none'/'same_site' => env('SESSION_SAME_SITE', 'lax')/g" config/session.php
    fi

    if [ -f .env ]; then
        sed -i '/SESSION_SECURE_COOKIE/d' .env
        sed -i '/SESSION_SAME_SITE/d' .env
        sed -i '/APP_URL/d' .env
        echo ">>> Configuring session cookies and URL for secure HTTPS deployment..."
        echo "SESSION_SECURE_COOKIE=true" >> .env
        echo "SESSION_SAME_SITE=none" >> .env
        echo "APP_URL=https://$domain" >> .env
    fi

    chmod -R 775 .
    chown -R apache:apache . 2>/dev/null || chown -R root:apache . || true

    echo ">>> Setting up PM2 startup..."
    cd /var/www/html/jupitermeet_lite/server
    sudo -u apache npm install

    pm2_startup_output=$(env PATH=$PATH:/usr/bin pm2 startup systemd -u apache --hp /usr/share/httpd)
    startup_cmd=$(echo "$pm2_startup_output" | grep "sudo env" | head -n 1)
    if [ -n "$startup_cmd" ]; then
        eval "${startup_cmd#sudo }"
    fi

    cat > /var/www/html/jupitermeet_lite/start-pm2.sh << 'EOF'
#!/bin/bash
export HOME=/usr/share/httpd
export PATH=$PATH:/usr/local/bin:/usr/bin
cd /var/www/html/jupitermeet_lite/server
pm2 restart jupitermeet_lite-server || pm2 start app.js --name jupitermeet_lite-server
pm2 save
EOF
    chmod +x /var/www/html/jupitermeet_lite/start-pm2.sh
    chown apache:apache /var/www/html/jupitermeet_lite/start-pm2.sh
    sudo -u apache bash /var/www/html/jupitermeet_lite/start-pm2.sh

    # installation log
    echo ""
    echo -e "${LIGHTGREEN}INSTALLATION HAS BEEN SUCCESSFULLY COMPLETED!${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}Installation Log${NOCOLOR}"
    echo ""
    if rpm -q php &> /dev/null
    then
    echo -e "PHP8.3    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "PHP8.3    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q httpd &> /dev/null
    then
    echo -e "Apache    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Apache    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q certbot &> /dev/null
    then
    echo -e "Certbot   : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "Certbot   : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q mariadb &> /dev/null
    then
    echo -e "MySQL     : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "MySQL     : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q phpMyAdmin &> /dev/null
    then
    echo -e "phpMyAdmin: ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "phpMyAdmin: ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q nodejs &> /dev/null
    then
    echo -e "NodeJS    : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NodeJS    : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if npm --version &> /dev/null
    then
    echo -e "NPM       : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "NPM       : ${LIGHTRED}Not installed${NOCOLOR}"
    fi
    echo ""
    if rpm -q coturn &> /dev/null
    then
    echo -e "TURN      : ${LIGHTGREEN}Installed${NOCOLOR}"
    else
    echo -e "TURN      : ${LIGHTRED}Not installed${NOCOLOR}"
    fi

    # print paths and credentials
    echo ""
    echo -e "${LIGHTRED}IMPORTANT: ${NOCOLOR}Please copy and save following paths and credentials for your future reference."
    echo ""
    _proto="https"; [ "${certbot_ok:-0}" -eq 0 ] && _proto="http"
    echo -e "${LIGHTCYAN}JupiterMeet Lite${NOCOLOR}
URL     : ${YELLOW}${_proto}://$domain/install${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}MySQL${NOCOLOR}
Database: ${YELLOW}jupitermeet_lite${NOCOLOR}
User    : ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    echo -e "${LIGHTCYAN}phpMyAdmin${NOCOLOR}
URL     : ${YELLOW}${_proto}://$domain/phpmyadmin${NOCOLOR}
Username: ${YELLOW}root${NOCOLOR}
Password: ${YELLOW}$a${NOCOLOR}"
    echo ""
    if [ "${certbot_ok:-0}" -eq 1 ]; then
        echo -e "${LIGHTCYAN}SSL${NOCOLOR}
Cert    : ${YELLOW}/etc/letsencrypt/live/$domain/fullchain.pem${NOCOLOR}
Pkey    : ${YELLOW}/etc/letsencrypt/live/$domain/privkey.pem${NOCOLOR}"
        echo ""
    fi
}

function print_error_01 {

    echo ""
            echo -e "${LIGHTRED}ERROR: ${NOCOLOR}exiting the installer..."
            echo ""
            echo -e "${YELLOW}NOTE: ${NOCOLOR}Please check the followings;

1) This script and the ${LIGHTRED}jupitermeet_lite.zip${NOCOLOR} file must be in the same directory
2) The zip file must be named as ${LIGHTRED}jupitermeet_lite.zip${NOCOLOR} (case sensitive)"
            echo ""

}

function print_error_02 {

     echo ""
        echo -e "${LIGHTRED}ERROR: ${NOCOLOR}exiting the installer..."
        echo ""
        echo -e "${YELLOW}NOTE: ${NOCOLOR}This automated installer currently supports only the following Linux versions;

> Ubuntu 24.04
> CentOS 7

To install JupiterMeet Lite on other Linux versions, please continue with manual installation."
        echo ""

}

function clearup {
    (
        sleep 5
        # Clean up files first
        rm -f /root/index.php
        rm -f /root/start-setup.sh
        rm -f /root/jupitermeetlite-installation.sh
        rm -f /root/jupitermeet-setup.service
        rm -f /root/jupitermeet-setup.socket
        rm -f /root/validate.sh
        rm -f /root/jupitermeet_lite.zip

        # Close setup port in firewall
        ufw delete allow 8080/tcp 2>/dev/null || true

        # Disable systemd units
        systemctl disable jupitermeet-setup.service jupitermeet-setup.socket 2>/dev/null || true
        rm -f /etc/systemd/system/jupitermeet-setup.service /etc/systemd/system/jupitermeet-setup.socket
        systemctl daemon-reload

        # Stop socket to free port 8080
        systemctl stop jupitermeet-setup.socket 2>/dev/null || true
        
        # Stop service last (kills this script)
        systemctl stop jupitermeet-setup.service 2>/dev/null || true
    ) &
}

# check server os
if [ -f /etc/lsb-release ]; then

    release=$(lsb_release -r)
    vernum=$(cut -f2 <<< "$release")

    # check is it ubuntu 24.04
    if [ $vernum == 24.04 ]; then
        if [ -f jupitermeet_lite.zip ]; then
            ubuntu_24_04
            clearup
        else
            print_error_01
        fi
    else
        print_error_02
    fi
elif [ -f /etc/redhat-release ]; then
    vernum=$(rpm -E %{rhel})
    if [ $vernum == 7 ]; then
        if [ -f jupitermeet_lite.zip ]; then
            centos_7
            clearup
        else
            print_error_01
        fi
    else
        print_error_02
    fi
else
    print_error_02
fi