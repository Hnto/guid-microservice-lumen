# GUID generator

### Docker setup

_**For the application setup see the README file in the src/ folder**_

#
# Installation with the install wizard:
- run the command "composer install"
- run the command "php guid install" and follow the setup

_**if you are running in production you must manually migrate the database (see step 3 --> dash 2 --> dash 1)**_

#
# Installation done manually:
### Step 1 (in root folder)
- change the ".env.example" file in the root to ".env"
- modify the DB_PASS and DB_ROOT_PASS into something secure

### Step 2 (in src/ folder)
- change the "src/.env.dist" file to "src/.env"
- modify the places where it says "#CHANGE"
    - APP_ENV (production | local | development)
    - APP_DEBUG (true | false)
    - APP_KEY (must be a 32 chars long random string)
    - DB_USERNAME (the username used in the .env of the container)
    - DB_PASSWORD (the password used in the .env of the container)
       
### Step 3 (in root folder)
- run the command `docker-compose up -d --build`
    - it can take up to 5 or 10 minutes
- run the command `docker exec -it guid_workspace bash` to ssh into the workspace container
    - run the command `bin/console doctrine:database:create` to create the database
    - run the command `bin/console doctrine:migrations:migrate` to migrate the tables
        - If you go to 127.0.0.1:{PORT} -> login -> insert a new user with an api key
    
Your containers are now ready and the mysql database can be handled with adminer.
The application is available on the host `127.0.0.1:{PORT}` and adminer on `127.0.0.1:{PORT}`

#
# UPDATING:
- run the command "php guid update"
    - the environment will be determined by the APP_ENV value in your "src/.env" file
        - for production updates, migrations must be done manually

# Facts
- The mysql data and configuration is saved in the `./mysql` folder
- The logs are saved in the `./logs` folder
