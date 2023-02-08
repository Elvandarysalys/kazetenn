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

# Current dependency map:


````shell
Admin
└── Core
    ├── Pages
    ├── Articles
````
