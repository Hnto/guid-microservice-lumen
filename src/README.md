# GUID generator

## Endpoints
Make API requests
The following API endpoints are available
- /guids (GET) shows all non-assigned guids 
- /guids (PUT) creates a new guid 
- /guids (POST) assigns a guid 
- /authenticate (GET) authenticate with api key

## API Documentation
Can be found when starting the server and going to http(s)://HOSTNAME/docs/index.html

## Documentation generator
- install homebrew on mac
    - If you are on another platform go to https://github.com/bukalapak/snowboard for installation instructions
- brew tap bukalapak/packages
- brew install snowboard
- snowboard html -o index.html blueprint/guid.apib (do this in the folder "public/docs/)

### Where to create an endpoint
- Endpoints are defined in **app/Api/Endpoints**
- Example endpoints are available: **resources.php** and **guids.php**
- Endpoints can be created by using the artisan command: php artisan make:endpoint {name}

_Endpoints must implement the EndpointInterface _

### For an endpoint to be available and read by Lumen you need to do the following
- Add the endpoint in the custom/config.php file with the full namespace
- Add the route in the routes/web.php file
 
_The EndpointsServiceProvider will read these endpoints and register them in the application. 
The available factory will then automatically determine which endpoint service is needed by its binded name_

### One central point of location
The app/Api/Http/Controllers/Api/IndexController is the main handler for incoming requests.
It will determine the endpoint requested, the available query parameters and then execute the required binded endpoint.

### Jobs
- CleanupInvalidTokens (deletes expired tokens, runs daily)
- CleanupNonAssignedGuids (deletes non assigned guids older than 10 days, runs daily)

These jobs are scheduled in the laravel scheduler available in "app/Console/Kernel.php".
For the scheduler to run and the jobs to be process you must start the scheduler and the workers.

The scheduler can be started by placing the following command in the crontab:</br >
`* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1`

The workers can be started by running the artisan command in the root directory:<br />
`php artisan queue:work`

It is advised to use a process manager (like supervisor) to manage the workers.
