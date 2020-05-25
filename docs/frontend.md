# Frontend Framework
One of the other goals of the Foundry Framework is to provide building blocks to creating any type of "application".

Using VueJS plugins and components makes this possible, and most of any Foundry Framework frontend development around applications uses this approach. 

## VueJS Packages
Each Foundry Framework Module can optionally provide a VueJS plugin that can be used in a themes dependencies and imported to that code base.

To achieve the import of these, we use symlinks as well as yarn workspaces to attache them as dependencies to the various frontend projects.

## Package Parts
Packages are built into main 3 types of Vue Components:

- Apps
- Widgets
- Components

### Components
Components provide the basic building blocks to some functionality. Like a button, table list, paginator, and others.

### Widgets
Widgets are a set of Components grouped together to perform some level of grouped functionality. Like managing a list of products or tags. Widgets would do server side calls and additional logic and leave the rendering mainly up to the components imported.

### Apps
Apps are one or more Widgets wrapped into an "application" with the idea that a frontend theme would import and connect various "applications" together to make up one large application, such as a CMS or CRM.

## Package Rules

1. The ```index.js``` file of a package should typically control what components, widgets, and applications are provided by a package. Importing a component directly should be avoided as this breaks the plugin's control over what component is loaded.
2. App names and filenames must end in `App`. E.G. `ProductsApp`.
3. Widget names and filenames must end in `Widget`. E.G. `ProductsWidget`.
4. Component names don't need to end in `Component` but should be prefixed with the Packages name to avoid confusion. E.G. `ProductsTable`.
5. All packages should assume the end application will use Vue Router and must work using named routes. Each app registered therefor must provide it's base route name(s).
