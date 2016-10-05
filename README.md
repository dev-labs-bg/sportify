# General info
This is a football (soccer :) match prediction game with a simple concept - you and your buddies battle it out to see who's best at predicting match final scores. Each player gives score predictions to upcoming matches, and then points are awarded (check below). There is a Slack integration for standings, results and fixture updates. The application is fetching real-world football fixtures and scores via a free football API (http://api.football-data.org), but custom tournaments can be created from the administration (check below).  

# Requirements to run the project
* PHP, Apache, Mysql
* Symfony [system requirements](http://symfony.com/doc/current/reference/requirements.html)
* [composer](https://getcomposer.org/), 
* [npm](https://docs.npmjs.com/getting-started/installing-node)
* [bower](https://bower.io/) & [gulp](http://gulpjs.com/) installed globally 

# Installation and Environment Setup
The project can be ran without issues on both *unix and Windows (MacOS, Ubuntu and Windows with LAMP tested:). Step by step guide how prepare a 512MB DigitalOcean Ubuntu LAMP 16.04 server in order to set-up the project can be found here.

The steps needed to set-up symfony and all the tools (for both development and production:

`composer install` - for a basic install you just need username and password for mysql, all are written to _app/config/parameters.yml_ check here for those the advanced parameters.
Run:  
`npm install` (if running on a machine with 0.5/1GB RAM add swap)  
`bower install`(you may need --allow-root as a parameter if running as sudo)  
`gulp`

**N.B.**
After doing any changes on **app/config/parameters.yml** run `php bin/console cache:clear --env=prod` for them to take effect.

# Application parameters and initial setup
* Database schema update (migrations):
    + `php bin/console doctrine:schema:update --force`
* Create your admin user (follow the steps):
    + `php bin/console fos:user:create`
* Give yourself Admin access from the command line: 
    + `php bin/console fos:user:promote your-username ROLE_ADMIN`
* You can now access the web interface :)
* If the emails are not configure (smtp credentials not set) you can create additional users with `php bin/console fos:user:create`

### Setting up a tournament - for admins
You can either set-up a tournament and update the scores for each game manually, or if it is a tournament present in the football api (check [here](http://api.football-data.org/v1/competitions), get the data from there.

// TO DO - check if division of info is correct
#### Using the API
* Initial tournament setup
    + create tournament (Admin Panel -> Tournaments)
    + map tournament to API (Admin Panel -> API Mappings)
* API fetch - update teams (Admin Panel -> Data Updates -> Update Teams)
* API fetch - update fixtures (Admin Panel -> Data Updates -> Update Match Fixtures)

#### Not using the API
* Create tournament (Admin Panel -> Tournaments)
* Manual fixture/result add or change (Admin Panel -> Matches)

### Taking part in a tournament - for users

// TO DO
## Using the app in time?

//TO DO


#### Changing advanced app settings:
 

`mailer_host`, `mailer_port`, `mailer_user`, `mailer_password` - SMTP settings in order to have the user registrations enabled on the web front end.

Best practice is to use a transactional mail service, like Mailgun, Amazon SES, etc. We recommend Mailgun as account takes few minutes and the free tier should be enough. Other option is to use [Gmail SMTP settings](https://www.digitalocean.com/community/tutorials/how-to-use-google-s-smtp-server).  

 `football_api.token` - you can get one free from [football-data.org](http://www.football-data.org/register). If you use the service for a longer time, consider [donating](http://api.football-data.org/about) donating :)  
` slack.url, slack.channel` - Generate them in [Slack](https://slack.com/apps/A0F7XDUAZ-incoming-webhooks) in order to get notifications.  
`secret` - Symfony variable - generate a it by going [here](http://nux.net/secret) or running in shell `openssl rand -hex 20`

**N.B.**
After doing any changes on **app/config/parameters.yml** run `php bin/console cache:clear --env=prod` for them to take effect.

//TODO
# Contribution
* fork
* do some changes
* pull request (with description of changes)


## What points are awarded
* **3 points** for exact match score prediction, examples:
    + You predicted 1-0, final score 1-0
    + You predicted 1-2, final score 1-2
    + You predicted 3-3, final score 3-3
* **1 point** for match outcome predicted, examples:
    + You predicted 1-0, final score 3-1  (home team win)
    + You predicted 1-2, final score 0-2  (away team win)
    + You predicted 2-2, final score 1-1  (draw)
* **0 points** for wrong match score/outcome prediction :)
* **5 points** for correct prediction of tournament champion/winner (so, assigned just once for each tournament)

// TO DO  - move to wiki
### DigitalOcean manual
I assume you have created a fresh 16.04 Ubuntu LAMP droplet, and are running everything as the root user. This setup is **not suitable** for a longer production usage, nor we assume any responsibility if you break something :) If you are not sure what a step does - google it :)

If working on a new droplet, you can copy/paste most of the commands below directly - if not, just use them as reference :)

#### Getting the project files
*...in the right place - that way we use the default virtualhost of Apache, so there is no need for a virtualhost to be onfigured.*
```
rm -rf /var/www/html
git clone repo-url /var/www/html
```
#### Enable and install mod_rewrite 
*Unzip is recommended for composer*
```
apt update
apt upgrade
apt install unzip php-xml -y
a2enmod rewrite
phpenmod xml
service apache2 restart
```
#### Enabling swap
*You need this on both 512MB or 1 GB droplets - if not present npm install hangs [source & explanations](https://www.digitalocean.com/community/tutorials/how-to-add-swap-space-on-ubuntu-16-04)*
```
fallocate -l 1G /swapfile
mkswap /swapfile
chmod 600 /swapfile
swapon /swapfile
sudo cp /etc/fstab /etc/fstab.bak
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
sudo cp /etc/sysctl.conf /etc/sysctl.conf.bak
echo 'vm.swappiness=10' | sudo tee -a /etc/sysctl.conf
echo 'vm.vfs_cache_pressure=50' | sudo tee -a /etc/sysctl.conf
```
#### Get composer
*You may also want to validate the install file - check Step 2 from [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-16-04)*
```
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

#### Install node/npm
*[DigitalOcean manual with more details ](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-16-04)*
```
cd ~
curl -sL https://deb.nodesource.com/setup_6.x -o nodesource_setup.sh
sudo bash nodesource_setup.sh
sudo apt-get install nodejs -y
```
#### Install gulp and bower as global packages
`npm install -g gulp bower`

#### Set up mysql user/pass
When prompted for those during `composer install` you can use user root and the mysql root pass generated by DigitalOcean (check it by running `cat /root/.digitalocean_password`)

Still it is best to create database user and pass for each application you are using. In order to do it from the mysql cli run `mysql -uroot -p`, type the root password and run those. **`something-really-random` should be changed** - you can generate a value for it by running `openssl rand -hex 26` beforehand).
```
create database sportify;
CREATE USER 'sportify_usr'@'%' IDENTIFIED BY 'something-really-random';
GRANT ALL ON sportify.* TO 'sportify_usr'@'%';
FLUSH PRIVILEGES;
exit
```
### Run all the shiny tools installed
You will be prompted for configuration data during composer install - chechk the README on what is what there
```
composer install
npm install
bower install --allow-root
gulp
```

#### All files should be owned by the apache user 
`chown -R www-data:www-data /var/www/html/`

Continue with **Application parameters and initial setup** :)


