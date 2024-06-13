# Inertia.js Hyperf Adapter

---

Visit [inertiajs.com](https://inertiajs.com/) to learn more.


# Todo

- [ ] Programmatically initialize node_modules.


## Installation

- ``` composer require onix-systems-php/hyperf-inertia ```  require package.
- ``` php ./bin/hyperf.php vendor:publish  onix-systems-php/hyperf-inertia``` publish config and middleware from package.
- `npm init` init package.json if not exist
- ```php ./bin/hyperf.php inertia:init ``` Initialize inertia.
- Add commands to the package.json
    ```json
    "scripts": {
      "dev": "vite",
      "build": "vite build",
      "build-ssr": "vite build && vite build --ssr" (optional)
    },
    ```
- ``` npm i ``` install node_modules  packages  
- Run inertia:
  - ``` npm run dev ``` for development
  - ``` npm run build ``` for production
> **Note**
> If you use Svelte, package.json type must be module.
```"type": "module"```

## Middleware

Next we need to setup the Inertia middleware, in file `./config/middlewares.php` . You can accomplish this by publishing the HandleInertiaMiddleware middleware to your application.
> **Note**
    This middleware must be placed before the `validation` middleware.
  ```php 
  'http' => [
        \App\Common\Middleware\HandleInertiaMiddleware::class,
    ],
  ```

## Creating responses

```php
#[GetMapping(path: '/test', options: ['name' => 'test'])]
public function test(RequestInterface $request)
{
    return inertia('Home', ['payload' => 'inertia'])->toResponse($request);
}
```

```php
#[PostMapping(path: '/test', options: ['name' => 'test'])]
public function login(RequestLogin $request, LoginUserService $loginUserService)
{
    try {
        $loginUserService->run(LoginDTO::make($request), $this->authManager->tokenGuard());
    } catch (\Exception $exception) {
        return redirect_with('test', ['alert' => [
            'type' => 'error',
            'message' => $exception->getMessage(),
        ]]);
    }
    return redirect_with('test');
}
```

## Frontend Part

After executing ``php ./bin/hyperf.php inertia:init`` you will get the
folder structure.
```
.
├── storage
│   ├── inertia
│   │    ├── css
│   │    │  └── app.css
│   │    └── js
│   │       ├── Components  
│   │       ├── Layouts  
│   │       ├── Pages
│   │       ├── app.js
│   │       ├── sst.js (optional)
│   │       └── bootstrap.js
│   ├── view
│   │    └── layouts 
│   │       └── app.blade.php
│   └── ...
├── vite.config.json
└── ... 
```
In folders ```Components```, ```Layouts```, ```Pages``` you can create your own components, layouts and pages.

### Ssr
In the ```./bin/hyperf.php inertia:init``` process, you can select ssr.
If you have set ssr separately, you can copy this file from ```hyperf-inertia/src/Commands/Stubs/{your_framework}/ssr.{ext}```
Or read the [inertia](https://inertiajs.com/server-side-rendering) documentation .

## How to run ssr
- ``` npm run build-ssr ``` 
- ``` php ./bin/hyperf.php inertia:start-ssr ``` into container run command to start ssr server.

## Environment variables
- ``` INERTIA_SSR_ENABLED ``` - If you are using ssr, set to true. Bu default false.
- ``` INERTIA_SSR_URL ``` -  URL of the ssr server. By default http://127.0.0.1:13714.
- ``` INERTIA_IS_SECURE ``` - If you are using http, set to false. Bu default true.
- ``` INERTIA_SKIP_URL_PREFIX ``` - If you need to skip url, set to prefix. By default []. Example ```['api/v1']```. All requests starting with this prefix will be skipped in inertia.
