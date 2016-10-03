# General info
This is a football match prediction game.
The concept is pretty simple, you and your buddies battle it out to see who's best at predicting match final scores.
Each player gives score predictions to upcoming matches, and then points are awarded as follows:
* **5 points** for correct prediction of tournament champion/winner.
* **3 points** for exact match score prediction, examples:
    + You predicted 1-0, final score 1-0
    + You predicted 1-2, final score 1-2
    + You predicted 3-3, final score 3-3
* **1 point** for match outcome predicted, examples:
    + You predicted 1-0, final score 3-1  (home team win)
    + You predicted 1-2, final score 0-2  (away team win)
    + You predicted 2-2, final score 1-1  (draw)
* **0 points** for wrong match score/outcome prediction :)

The application is fetching real-world football fixtures and scores via a free football API (http://api.football-data.org)

# Installation and Environment Setup
* install the following package/dependency managers and tools:
    + composer (https://getcomposer.org/)
    + npm (https://docs.npmjs.com/getting-started/installing-node)
    + bower (https://bower.io)
    + gulp (http://gulpjs.com)
* After all of the above are installed successfully, run the following:
    + composer install
    + bower install
    + gulp

# Application parameters and initial setup
* Setup parameters in **app/config/parameters.yml**. This file is gitignored so you need to refer to **app/config/parameters.yml.dist**:
    + DB: host,port, db name, username, password)
    + mail: protocol, host, port, username, parameters, sender_address
    + football API: token
    + Slack: Webhook URL, channel
* Create database:
    + `php bin/console doctrine:database:create`
* Database schema update:
    + `php bin/console doctrine:schema:update --force`
* Open the web application, and singup/create username
* Give yourself ADMIN access from the command line:
    + `php bin/console fos:user:promote <username> ROLE_ADMIN`
* initial tournament setup
    + create tournament (ADMIN PANEL -> TOURNAMENTS)
    + map tournament to API (ADMIN PANEL -> API MAPPINGS)
* API fetch - update teams (ADMIN PANEL -> DATA UPDATES -> UPDATE TEAMS)
* API fetch - update fixtures (ADMIN PANEL -> DATA UPDATES -> UPDATE MATCH FIXTURES)
* Manual fixture/result add or change (ADMIN PANEL -> MATCHES)

//TODO
# Contribution
* fork
* do some changes
* pull request (with description of changes)