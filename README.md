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
    #[GetMapping(path: '/test-feedback')]
    public function test(RequestInterface $request)
    {
        return inertia('Test', [
            'title' => 'Feedback form',
            'formTitle' => "What's on your mind?"
        ])->toResponse($request);
    }
```

```php
    #[PostMapping('/test-feedback')]
    public function testFeedback(RequestInterface $request)
    {
        $rules = [
            'fullname' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:255',
        ];

        $validator = $this->validationFactory->make($request->all(), $rules);
        $validator->validate();

        return redirect_with('test-feedback', [
            'alert' => [
                'message' => 'Feedback was successfully sent',
                'type' => 'success',
            ],
        ]);
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
- ``` INERTIA_SSR_ENABLED ``` - If you are using ssr, set to true. By default false.
- ``` INERTIA_SSR_URL ``` -  URL of the ssr server. By default http://127.0.0.1:13714.
- ``` INERTIA_IS_SECURE ``` - If you are using http, set to false. By default true.
- ``` INERTIA_SKIP_URL_PREFIX ``` - If you need to skip url, set to prefix. Prefixes should be specified as a string using the separator ``,``. By default empty string. Example ``` v1,api ```. All requests starting with this prefix will be skipped in inertia.
- ``` INERTIA_NO_SKIP_EXTRA_PATH ``` When prefix exist in to ``` INERTIA_SKIP_URL_PREFIX ```, but an exception must be made for a separate path. The extra path should be specified as a string using the separator ``,``. By default empty string. Example ``` admin/login,admin/forgot ```.

## Examples of frontend files
In the package you can find example files for the frontend. vendor/onix-systems-php/hyperf-inertia/example There is also an example controller here.
- It is necessary to connect styles in inertia.blade.php ```<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">```

> **Note**
> If you are using arm64, you need to add ``@rollup/rollup-darwin-arm64``, this is only for svelte