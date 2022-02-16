# kazetenn-pages

## Description
Kazetenn Pages is a symfony bundle that allow you to handle basic pages programatically.


## Installation
You can use 

`composer require elvandar/kazetennarticles`

to install the bundle. 

you will also need to configure `stof/doctrineextensionsbundle`:

in `config/packages/stof_doctrine_extensions.yaml`

```yaml
stof_doctrine_extensions:
    orm:
        your_orm:
            timestampable: true
```

## Usage

The bundle provides a simple data model to handle the programatical creation of pages and some routes and views to display those pages.

### configuration:

in `config/packages/kazetenn-pages.yaml`:
```yaml
kazetenn-pages:
    blog_url: ""
```

by default there is no prefix in front the diplay route, however, using this config you can add one.

### the data model

the budle articulates around 2 entities:

##### Page

which represent a page to display

in a page, you can define:

- a title
- a slug
- a parent
- a list of content

this will be used to handle the page display and url

if the page have no parent, her url will be:

`/{blog_url}/{slug}`

if the page have a parent, her url will be:

`/{blog_url}/{parent_slug}/{slug}`

##### PageContent

which handle the content of a page.

in a pageContent, you will can define:

- a content
- a template
- a parent
- an order
- a align (vertical or horizontal)
- a list of content

The content is a text/html. It will always be rendered using the `raw` twig function.

The template allows you to define a twig template to personalize the rendering of the content without storing html in the database.

A pageContent can reference multiple other pageContent (childs). Using the align property, you can define the way a content's childs will be rendered.
Using this, you can easilly create a grid of content, allowing you to easily order you content. To ease this, a pageContent's content property can be null, allowing you to create an ordering pageContent.

Finally, the order property allow you to choose the rendering order of a pageContent between him and same level contents.


## License
The pages bundle is under MIT liscence
