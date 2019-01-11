# Nextfeed 2

Nextfeed is a rss feed agreggator. Initially it was just an EmberJS frontend which consumed feeds from a Nextcloud Newsfeed installation. Unfortunately,
the API was not really handy in terms of consuming and dealing with feeds and articles so Nextfeed 2 is now based on Laravel and Zend-Feed. 

## Websocket integration
Install the server 
`npm install -g laravel-echo-server`

Copy laravel-echo-server.example.json to laravel-echo-server.json and ajust values.

Start artisan queue: `php artisan queue:work`  - its good advise to have this all handled by supervisor.

