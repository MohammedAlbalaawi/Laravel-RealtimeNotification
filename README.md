<p align="center"><img src="https://cdn-icons-png.flaticon.com/512/8297/8297354.png" width="100" alt="Laravel Logo"></p>


## Realtime Notification App
I'll cover Laravel broadcasting in this project using public, private and presence channels,

## Preparing - auth
- Install authentication system `composer require laravel/ui`
- Scaffolding `php artisan ui bootstrap --auth`
- `npm install` `npm run dev`

## Preparing - pusher
- Sing up in https://pusher.com , *Create* new application and get *App keys* 
- `composer require pusher/pusher-php-server`
- `npm install --save-dev laravel-echo pusher-js`
- In .env *edit* 
```
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
````
- In config folder/app.php *Uncomment* `App\Providers\EventServiceProvider::class`
- In resources folder/js/bootstrap.php *Uncomment* and edit to
```
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```
