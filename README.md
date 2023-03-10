# Kazetenn

# What is Kaztenn ?

Kazetenn is a collection of libraries built in Symfony 6 that aims to provide a quick CMS functionality while still
getting symfony's power and modern architecture

# Why ?
- Why not ?
- It is a fun pet project to have

# How to use it ?
Create a symfony project (full or skeleton, it doesn't matter) and use :

````shell
composer require elvandar/kazetenn
````

## Use cases:

### I do not know how all this work, I just want to build a website

Go to the green "<>code" button at the top right of the page, click it and select "Dowload Zip"

unzip it and transfer the folder to your server (There should be plenty of doccumentation on you server renting space)

ensure that PHP is installed

ensure that composer is installed

once the transfer done, go to the ``kazetenn`` folder.

Create a .env file in the ``kazetenn`` folder and complete it whith the following information:
````dotenv
APP_ENV=prod
APP_SECRET="a random secret"
DATABASE_URL="the url to connect to your database, such as mysql://username:password@127.0.0.1/kazetenn"
````

Then, you will want to run the ``composer install`` command.

If you arrived at those steps without error, you should be setup. You can go to ``url_of_your_website/admin`` and start
working.

### I come from Wordpress/Drupal

I am assuming that if you went this far, you intend to do some advanced customisation.

Fire up your favorite Ide, check that composer is installed and build a symfonny project:

[symfony's documentation](https://symfony.com/doc/current/getting_started/index.html)

Once it is done, you can run the

``composer require elvandar/kazetenn``

You are now as set up as the precedent example. I would suggest that you read the documentation of the component you are
interested in to learn about and the symfony documentation.

//todo: build an example project and link the github here as an aid

### I want to use it in my symfony project

Then you probably know and understand everything I already explained. You may want a more custom installation, in which
case.

I would suggest to start small, the bare-minimum  of the installation is the Core component. And you can find a dependency
map [here](Current%20dependency%20map).

From there, you can look at the different components, install the one you want, or build what you need from here.

# How does it work ?

DISCLAIMER: this is still a WORK IN PROGRESS. The following text describe a goal, not a documentation

With this project I have a few objectives:
- provide basic cms functionality (admin + content handling + content display).
- each component who is not necessary should be a separate library and not required.
- if it is fun to re-invent the wheel, do it, it is the perfect opportunity to learn.

How does it work:

Right now there are 4 planned libraries:
- Admin
- Core
- Article
- Pages

### Admin
Admin is a standalone library, it allows a developer to build quickly an administration back-office.

This library is mostly in a satisfactory state right now, you can find its documentation [here](src/Admin/README.md)

### Core
The objective of this library is to be the starting point of a Kazetenn Project. It should include the absolute basics
to make such a project work:

- A content abstraction and the mechanics to build generic contents
- A back office with the necessary views to build generic contents
- A basic user mechanic to protect the back office
- Basic content display mechanic.
- The Core should not handle the declaration of each content. This should be the purpose of future optionnals libraries

The core library is the main working space of the project. Every new functionality is first build here and then if there
is an interest in making it a library, it is then extracted.

### Pages and Articles
Those 2 libraries are meant to provide with the two most common content in a CSM, a Page and an Article, they are also
used as example for other contents libraries

#### page
A page is made to represent the static/semi-static pages of a website (Landing, Faq, Presentation, ...)

they are a basic content with a hierarchy mechanic (parent and child references)

#### article
An artocme is made to represent the main content found on most CMS (Blog posts, news article, ...)

they are a basic content with a category mechanic (one or more category by article, filter by category, ....)

#### Users

Users is an implementation of symfony users with basics roles.

#### Documents

Users is a library to handle files.

# Current dependency map:

````shell
├── Admin
├── Users
├── Documents
└─── Core
    ├── Pages
    ├── Articles
````

# 
