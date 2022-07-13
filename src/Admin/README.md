# kazetenn-pages

## Description
Kazetenn Admin is a symfony bundle that allow you to handle basic pages programatically.


## Installation
You can use 

`composer require elvandar/kazetenn_core_admin`

to install the bundle. 

## Usage

This bundle provides an administration panel and menu to create a back office.

### configuration:
Here is the configuration reference for the bundle, you can configure it 
in `config/packages/kazetenn_admin.yaml`:

```yaml
kazetenn_admin:
  authorized_roles:     []
  admin_url:            admin
  translation_domain:   kazetenn_admin
  pages:
    name:
      function:             ~
  menu_entries:
    name:
      target:               ''
      display_name:         ''
      translation_domain:   ''
      order:                0
      type:                 route
      authorized_roles:     []
      orientation:          vertical
      children:
        name:
          target:               ''
          order:                0
          display_name:         ''
          translation_domain:   ''
          authorized_roles:     []
          type:                 route
```

Let's go over all of those in detail:
#####  Basic configuration: 
```yaml
kazetenn_admin:
  admin_url:            admin
  translation_domain:   kazetenn_admin
  authorized_roles:     []
  pages:
    name:
      function:             ~
  menu_entries:         []
```

###### admin_url: 

This is the admin menu url prefix. 
This will produce urls under this format:

`http://your_domain/admin_url/`

By default, it is set to 'admin'.

###### translation_domain: 

This will define the translation domain of the overall menu. This will also be the translation domain fallback if 
you do not define it for other menus.

###### authorized_roles: 

This is a collection roles authorized to access the menus. The roles are classics symfony roles. 

If you want to allow non-authenticated users to access the admin menu, you must use the 'ANONYMOUS' role.

Except if the 'ANONYMOUS' role is set, a user must have all the defined roles to access the menu.

If you did not define roles for each individual menu, this array will be used as a fallback.

###### Pages:

This is where you will be able to define the admin pages.

###### menu_entries:

This is where you will be able to define the different menus.

#####  Menu entry configuration:
````yaml
name:
  target:               ''
  display_name:         ''
  translation_domain:   ''
  order:                0
  type:                 route
  authorized_roles:     []
  orientation:          vertical
  children:             []
````

This will allow you to configure every menu entry in your admin page.

###### name:

The name of your entry

###### type:

The type of entry, it can be one of:
* link
* page
* route
* header

This tells the bundle how to interpret the 'target' entry.

The header type is a special type only available for the upper menus. This will render a menu with no link tha is 
 used to create main menus and header menus.

###### target: 

The string that the code will use to render based on the type you provided
* link: a link to an external source (`https://google.com`)
* page: the name of an admin page
* route: a symfony route
* header: nothing

###### display_name:

The name that will be displayed (and translated)

###### translation_domain:

The translation domain used to translate the name. If left empty, the bundle will fallback to the default defined 
domain.

###### order:

The order in which the menu will be rendered. The bundle will throw a warning if 2 menus have the same order.

###### authorized_roles:

This is a collection roles authorized to access the menus. The roles are classics symfony roles.

If you want to allow non-authenticated users to access the admin menu, you must use the 'ANONYMOUS' role.

Except if the 'ANONYMOUS' role is set, a user must have all the defined roles to access the menu.

If you did not define roles for each individual menu, this array will be used as a fallback.

###### orientation:

This will define if your menu will be displayed in the horizontal head menu or in the vertical side menu.
The values are
* horizontal
* vertical

###### children:

Here you can define the sub menus

#####  Children menu entry configuration:
````yaml
name:
      target:               ''
      order:                0
      display_name:         ''
      translation_domain:   ''
      authorized_roles:     []
      type:                 route
````

###### name:

The name of your entry

###### type:

The type of entry, it can be one of:
* link
* page
* route

This tells the bundle how to interpret the 'target' entry.

The header type is a special type only available for the upper menus. This will render a menu with no link tha is
used to create main menus and header menus.

###### target:

The string that the code will use to render based on the type you provided
* link: a link to an external source (`https://google.com`)
* page: the name of an admin page
* route: a symfony route

###### display_name:

The name that will be displayed (and translated)

###### translation_domain:

The translation domain used to translate the name. If left empty, the bundle will fallback to the default defined
domain.

###### order:

The order in which the menu will be rendered. The bundle will throw a warning if 2 menus have the same order.

###### authorized_roles:

This is a collection roles authorized to access the menus. The roles are classics symfony roles.

If you want to allow non-authenticated users to access the admin menu, you must use the 'ANONYMOUS' role.

Except if the 'ANONYMOUS' role is set, a user must have all the defined roles to access the menu.

If you did not define roles for each individual menu, this array will be used as a fallback.

## Display of the menu

There are multiple ways to create a vue within the menu:

##### Custom controller

The bundle comes with an abstract controller (``Kazetenn\Admin\Controller\BaseAdminController``). When you extends 
it, the render method will automatically provides you with arguments containing the menus you registered.

You can use it in combination with the already existing twig templates (`Admin/Resources/views/admin_base.html.twig`)
or create your owns.

##### Pages

The admin pages are a way to ensure that the code you created will be displayed in the admin template.
You can configure thems using this part of the YAML:

````yaml
pages:
    name:
      function: 'FQCN\Of\Your\file::yourFunction'
````

The name will be used to generate the page url.

The function must be within a specific set of criterias:

- It must not have parameters (you can use constructor injection)
- It must return a string containing HTML

If you did so correctly, you can use the ``http://your_domain/admin_url/display_page/page_name`` to access your page.

## Customisation of the admin

The default admin interface has been created using Bulma (https://bulma.io/)

## License
The pages bundle is under MIT liscence
