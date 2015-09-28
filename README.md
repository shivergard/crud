To Start using it add to composer.json repozitory

    "repositories": [
      {
      "type": "git",
       "url": "git@github.com:shivergard/crud.git"
      }
    ],

and add requirements

  "require": {
    ...
        "shivergard/crud" : "dev-crud_included_50" 
    },

and add service provider

    'providers' => [
    ...
      'Shivergard\Crud\CrudServiceProvider',
    ...