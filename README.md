#Kazetenn

#What is Kaztenn ?
Kazetenn is a Symfony 5 bundle that aims to provide a quick CMS functionality while still getting symfony's power.
#Why ?
- I don't like WordPress and Drupal
- It is a fun pet project to have
#How to use it ?
Create a symfony project (full or skeleton, it doesn't matter) and use : 

````shell
composer require elvandar/kazetenn
````

#What does it do ?

Kazetenn provides a simple administration, a page and page content entity and a single view route.

You can access the administration from the ``admin/pages`` url, from there, you will be able to
- create pages
- add content to pages

Pages can have a parent. 

If the parent is null, the page url will be ``/{page_slug}``.

If not, it will be ``/{parent_slug}/{page_slug}``.

Page_contents are ordered and will be displayed accordingly.

Finally, you can specify a page and a page content template. If not, the default one will be used.
