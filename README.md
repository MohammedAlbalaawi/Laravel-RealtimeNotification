<p align="center"><img src="https://cdn-icons-png.flaticon.com/512/8297/8297354.png" width="100" alt="Logo"></p>

*add validation, services, ...ect*
*it's a quick code*

## Realtime Notification App
I'll cover Laravel broadcasting in this project using public, private and presence channels,

## Preparing - layout
- in resources folder/views/layouts/app.blade.php *add* in header `@stack('styles')` and `@stack('scripts')` before close body tag
<br />*we'll use it later*

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
-----------------------------------------
## 1- Realtime Notification when Login/Logout
- In resources/views/layouts/app.blade.php `<div id="notification" class="alert mx-3 invisible"></div>`
<br /> This *div* will be visible in realtime when user login or logout
- Create Event: UserSessionChangedEvent
<br /> Add *$message*, *$type* public properties to constactor
<br /> In *broadcastOn()* specify public channel name *notifications*
- Create Listener: UserLoginListeneer
<br /> In *handle()* add `broadcast(new UserSessionChangedEvent("{$event->user->name} is ONLINE",'success'))`
- Create Listener: UserLogoutListeneer
<br /> In *handle()* add `broadcast(new UserSessionChangedEvent("{$event->user->name} is OFFLINE",'danger'))`
- In app folder/Provider/EventServiceProvider.php *add*
```
      Login::class => [
            UserLoginListener::class,
        ],
        Logout::class => [
            UserLogoutListener::class,
        ],
```
- In resources folder/js/app.js *add*
```
Echo.channel('notifications')
    .listen('UserSessionChangedEvent', (e) => {
        const notificationElement = document.getElementById('notification');
        notificationElement.innerText = e.message;

        notificationElement.classList.remove('invisible');
        notificationElement.classList.remove('success');
        notificationElement.classList.remove('danger');

        notificationElement.classList.add('alert-' + e.type);
    });
```
------------------
## 2- Realtime API - CRUD in realtime
- *Create* a user controller `php artisan make:controller Api\UserController --api`
- Add the logic for user crud in UserController.php
```
public function index()
    {
        return User::all();
    }
    
    public function store(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        return User::create($data);
    }

    
    public function show(User $user)
    {
        return $user;
    }
    
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        return $user->update($data);
    }
    
    public function destroy(User $user)
    {
        return $user->delete();
    }
    ```
- In routes/api.php `Route::apiResource('users',UserController::class)`
- In routes/web.php `Route::view('users', 'users.index')->name('users.index')`
<br /> Now we will this list update in realtime when CRUD user
- *Create* 3 Events for user Created, Updated and Deleted and set the public channel name 'users'
- User Model add
```
    protected $dispatchesEvents = [
        'created' => UserCreatedEvent::class,
        'updated' => UserUpdatedEvent::class,
        'deleted' => UserDeletedEvent::class,
    ];
```
- Add
```
    <script type="module">
        const usersElement = document.getElementById('users');

        Echo.channel('users')
            .listen('UserCreatedEvent', (e) =>{
                let element = document.createElement('li');

                element.setAttribute('id', e.user.id);
                element.innerText = e.user.name;

                usersElement.appendChild(element);
            })
            .listen('UserUpdatedEvent', (e) =>{
                const element = document.getElementById(e.user.id);
                element.innerText = e.user.name;

            })
            .listen('UserDeletedEvent', (e) =>{
                const element = document.getElementById(e.user.id);
                element.parentNode.removeChild(element);
            });
    </script>
    ```
    
