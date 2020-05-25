# Modules
Modules provide a simple approach to extending and enhancing the application. Each Module is in effect a mini Laravel Application and Package, and is installed the same way any other Laravel Package would be.

Modules provide routes, Controllers, Input Types and Input Collections, Services, Repositories, and Models. They can also provide Middleware and any other Laravel feature.

## Installation and Config
All modules are installed into the root modules folder with a folder structure of ```/modules/[author]/[name]```. The author being the company or person that created the module, and the name being the actual name of the module.

### File Structure
Modules are structured the same way that a Laravel Application is. An example of that structure is:

- /app
  - /Console
  - /Http
    - /Controllers
    - /Responses
    - /Requests
  - /Events
  - /Inputs (Foundry Input Types and Input Collections)
  - /Listeners
  - /Models
  - /Providers
    - ModuleProvider.php
  - /Repositories
  - /Services
- /config
- /database
  - /factories
  - /migrations
  - /seeds
- /routes
- /resources
  - /packages (Foundry javascript packages)
  - /views
  - /lang
- /tests
- composer.json

### Composer & Dependencies
Every Module must have a ```composer.json``` file, specify it's dependencies and how the module should be loaded (autoload: psr-4, files, classmap).

An extra key of "type" is also required and must be equal to ```foundry-module```. This is the secret sauce which ensures that when you require a module into any project via's it's composer file, the module will be installed into that projects "modules" directory based on the module name which must also provide ```[author]/[name]```.

This results in your root project then committing that imported code as the projects code, allowing for changes and modifications to be made that are specific to your project as needed.

### Migrations
As in any application, tables should be created. So each module should provide it's own tables for migrating the module and ensuring it has all its tables in place.

When adding migrations to your module, remember to import your migrations table in your ModuleProvider class so that Laravel knows about them.

Tables must be prefixed with the modules name. Therefor in a module called `store` found in `modules/dion/store`, with a table for products, the table name would be `bidding_store_products`. If this becomes too long for a table name, shorter acronym's may be used such as `bdg_store_products` or similar.

## Packages
Packages for a module should typically be VueJS plugins and follow the same principles. 

Packages for a module should be saved in that modules ```resources/packages/[package-name]``` directory.

Whilst the structure of the package can be anything, it is generally good practice to use the default structure as below. This helps to maintain consistency with npm packages as a whole and makes it easier for future adaptations or changes in the Framework itself.

### File Structure
Packages should be typically structured as:

- /src
  - /apps
    - index.js
  - /components
  - /mixins
  - /styles
  - /widgets
  - index.js
- /demo

```/src/apps/index.js``` should return an array of Application names and components. E.G.

```$javascript
import ProjectsApp from 'ProductsApp';
...

export default const [
    {
        name: 'products',
        component: ProjectsApp
    },
    ...
]
```
Then ```src/index.js``` must export the VueJS plugin as well as the "Apps" array above from index.js, and then any other widgets, components, and mixins it wishes to expose. 

### Yarn and Dependencies
All packages should have a package.json file and can also contain the yarn-lock.json file.

The package.json file should contain the list of dependencies the package requires, and these will be interpreted just like any node_module dependencies are.

_Hint:_ A package is actually a normal npm package. The only difference is we import them into the main project through yarn workspaces. See https://classic.yarnpkg.com/en/docs/workspaces/ for more details on how Workspaces work.
