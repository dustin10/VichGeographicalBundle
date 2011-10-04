VichGeographicalBundle
======================

The VichGeographicalBundle provides automatic geographic coordinate querying for ORM 
entities and ODM documents. The bundle also provides functionality for rendering 
of JavaScript maps for these entities in your Symfony2 project using annotations. 
It also allows for object oriented JavaScript maps to be rendered without 
using any of the coordinate querying features. The bundle uses Google maps by 
default, but other maps are always being integrated and you can always write your 
own map renderer.

## Currently Supported Map Renderers

The following is a list of currently supported JavaScript map renderers. Please 
do not hesitate to fork this repo and add antoher one!

```
Google Maps API v3
Bing Maps v7
Leaflet
```

## Installation

### Get the bundle

To install the bundle, place it in the `vendor/bundles/Vich/GeographicalBundle` 
directory of your project. You can do this by adding the bundle to your deps file, 
as a submodule, cloning it, or simply downloading the source.

Add to `deps` file:

```
[VichGeographicalBundle]
    git=git://github.com/dustin10/VichGeographicalBundle.git
    target=/bundles/Vich/GeographicalBundle
```

Or you may add the bundle as a git submodule:

``` bash
$ git submodule add https://github.com/dustin10/VichGeographicalBundle.git vendor/bundles/Vich/GeographicalBundle
```

### Add the namespace to your autoloader

Next you should add the `Vich` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Vich' => __DIR__.'/../vendor/bundles'
));
```

### Initialize the bundle

To start using the bundle, register the bundle in your application's kernel class:

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Vich\GeographicalBundle\VichGeographicalBundle(),
    );
)
```

### Configuration

The only required configuration option is the `db_driver`. You must specify either 
`orm` or `mongodb`.

``` yaml
# app/config/config.yml
vich_geographical:
    db_driver: orm # or mongodb
```

**Note:**

```
A verbose configuration reference including all configuration options and their 
default values is included at the bottom of this document.
```

VichGeographicalBundle Annotations
==================================

Now you need to annotate the entities or documents you would like to query for coordinates. 
There are two annotations to use, the class annotation `@Geographical` 
marks the entity as geographical and the `@GeographicalQuery` annotation 
marks the method in the class whose return value is used as the query to the 
coordinate querying service (i.e. the method returns an address string which the 
coordinate query service will turn into geographical coordinates). The following 
is a working example ORM entity:

``` php
<?php

use Doctrine\ORM\Mapping as ORM;
use Vich\GeographicalBundle\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Geographical
 */
class Location
{
    // ..

    /**
     * @ORM\Column(type="decimal", scale="7")
     */
    protected $latitude

    /**
     * @ORM\Column(type="decimal", scale="7")
     */
    protected $longitude

    // ..

    /**
     * Notice the latitude property must have a setter
     */
    public function setLatitude($value)
    {
        $this->latitude = $value;
    }

    /**
     * Notice the longitude property must have a setter
     */
    public function setLongitude($value)
    {
        $this->longitude = $value;
    }

    /**
     * @Vich\GeographicalQuery
     *
     * This method builds the full address to query for coordinates.
     */
    public function getAddress()
    {
        return sprintf(
            '%s, %s, %s %s',
            $this->address,
            $this->city,
            $this->state,
            $this->zipCode
        );
    }
}
```

You can configure which properties of your entity are used to store the latitude 
and longitude coordinates. By default the latitude property is named `latitude` and 
the longitude property is named `longitude`. You can set these properties using the 
class annotation.

Below is an ORM example:

``` php
<?php

use Doctrine\ORM\Mapping as ORM;
use Vich\GeographicalBundle\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @Vich\Geographical(lat="mylat", lng="mylng")
 */
class Location
{
    // ..

    /**
     * @ORM\Column(type="decimal", scale="7")
     */
    protected $mylat

    /**
     * @ORM\Column(type="decimal", scale="7")
     */
    protected $mylng

    // ..

    /**
     * Notice the mylat property must have a setter
     */
    public function setMylat($value)
    {
        $this->latitude = $value;
    }

    /**
     * Notice the mylng property must have a setter
     */
    public function setMylng($value)
    {
        $this->longitude = $value;
    }

    // ..
    }
```

By default the coordinates are only queried when the entity is first created. If you 
would like the coordinates to be queried every time the entity is updated as well, then 
you can change the `on` option of the Geographical annotation to `update`.

Below is an example ORM entity:

``` php
<?php

use Doctrine\ORM\Mapping as ORM;
use Vich\GeographicalBundle\Annotation as Vich;

/**
 * @ORM\Entity
 *
 * @Vich\Geographical(on="update")
 */
class Location
{
    // ..
```

## Overriding the Coordinate Query Service

You can change the query service used to get the coordinates by creating your own 
class which implements `Vich\GeographicalBundle\QueryService\QueryServiceInterface`. 
By default Google is used. You can then define your class as as service and then 
configure that service using the `query_service` configuration parameter.

``` yaml
# app/config.yml
vich_geographical:
    # ...
    query_service: my_custom_service    
```

Twig Integration
================

The `VichGeographicalBundle` comes fully equipped with Twig functions to render your 
geographically aware entities using several JavaScript map rendering APIs or any 
mapping service you prefer. It also allows you to create and render maps in an 
object oriented way without using the annotation and features of the bundle for 
entities.

### Creating a Map Class

To display a map for your entity first you need to create a class that extends the 
base `Vich\GeographicalBundle\Map\Map` class. A good namespace for your map classes 
is `Map`, but this is not required.

``` php
// src/Acme/DemoBundle/Map/LocationMap.php
<?php

namespace Acme\DemoBundle\Map;

use Vich\GeographicalBundle\Map\Map;

/**
 * LocationMap.
 */
class LocationMap extends Map
{
    /**
     * Constructs a new instance of LocationMap.
     */
    public function __construct()
    {
        parent::__construct();

        // configure your map in the constructor 
        // by setting the options

        $this->setAutoZoom(true);
        $this->setContainerId('map_canvas');
        $this->setWidth(500);
        $this->setHeight(350);
    }
}
```

### Declare the Map as a Service

In order for the map to be available in the Twig templates you need to declare 
your map as a service and then tag it with the `vichgeo.map` tag and give it 
an alias so that you can refer to it in the template.

``` xml
<!-- src/Acme/DemoBundle/Resources/config/map.xml -->
<?xml version="1.0" encoding="UTF-8" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>

        <service id="acme_dmeo.map.location" class="Acme\DemoBundle\Map\LocationMap">
            <tag name="vichgeo.map" alias="location" />
        </service>

    </services>

</container>
```

### Import the Map Services

Now that you have declared your maps as services you need to import them in the 
`config.yml` file of your application.

``` yaml
# app/config/config.yml
imports:
    # other imports here like security.yml and parameters.ini
    - { resource: "@AcmeDemoBundle/Resources/config/map.xml" }
```

### Rendering a Map In Twig

Now that our maps have been declared as services, tagged and imported into the 
application, we are ready to render them.

You can include any javascripts the map renderer needs in your `<head>`
section with the `vichgeo_include_js` Twig function.

``` twig
{{ vichgeo_include_js() }}
```

If your map renderer requires any stylesheets then you can render them in your `<head>` 
section by using the `vichgeo_include_css` function.

``` twig
{{ vichgeo_include_css() }}
```

The `vichgeo_map_for` Twig function will render the map with the alias specified 
by the first parameter and will use the entity or array of entities passed into 
the second parameter. The function will automatically read the annotations of 
your entities and fetch the coordinates for the map marker.

``` twig
{{ vichgeo_map_for('location', location) }}
```

If you have a pre-configured map that you would like to render that does not need 
any entities specified, then you can use the `vichgeo_map` Twig function.

``` twig
{{ vichgeo_map('location') }}
```

Example of a Pre-Configured Map
===============================

A pre-configured map is a map that does not use entities that are marked up with 
the VichGeographicalBundle annotations. Rendering a pre-configured map is no different 
than rendering a map for entities except for the Twig function used and how you 
add markers to the map.

An example pre-configured map class:

``` php
// src/Acme/DemoBundle/Map/LocationMap.php
<?php

namespace Vich\DemoBundle\Map;

use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\Marker\MapMarker;
use Doctrine\ORM\EntityManager;

/**
 * PreConfiguredMap.
 */
class PreConfiguredMap extends Map
{
    /**
     * Constructs a new instance of PreConfiguredMap.
     *
     * @param EntityManager $em The entity manager.
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        // set some options
        $this->setAutoZoom(true);
        $this->setShowMapTypeControl(true);
        $this->setShowZoomControl(true);

        // do something here with the EntityManager to get your entities

        foreach ($entities as $entity) {
            $this->addMarker(new MapMarker($entity->getLat(), $entity->getLng()));
        }
    }
}
```

In this map, an example of injecting the EntityManager to fetch some locations 
from the database has been used, but you can get your location info however you see 
fit.

The service definition for this map would be a little different because we have 
injected the EntityManager into it.

``` xml
<!-- src/Acme/DemoBundle/Resources/config/map.xml -->
<?xml version="1.0" encoding="UTF-8" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>

        <service id="acme_demo.map.pre_configured" class="Acme\DemoBundle\Map\PreConfiguredMap">
            <tag name="vichgeo.map" alias="pre_configured" />
            <argument type="service" id="doctrine.orm.entity_manager" />
        </service>

    </services>

</container>
```
Instead of using `vichgeo_map_for` to render the map, a pre-configured map is 
rendered with `vichgeo_map`.

``` twig
{{ vichgeo_map('pre_configured') }}
```

Popup Info Windows
==================

The bundle supports popup info windows when map markers are clicked. Use 
the `setShowInfoWindowsForMarkers` setter of the `Map` class to activate or 
deactivate (default) this feature. A default template for the content of the 
popup has been provided, but it is strongly recommended that you create a twig/php 
template for the popup window as the default one only displays the string 
representation of the entity.

### Configure Your Template

In the bundle configuration, you specify which template the bundle should use to 
generate the html content of your info window popup.

``` yaml
#app/config.yml
vich_geographical:
    # ...
    templating:
        info_window: AcmeDemoBundle:Map:infoWindow.html.twig
```

This example configures the bundle to use the `infoWindow.html.twig` template. Your 
template will be passed the entity that the map marker represents. The template 
variable name is `obj`. Below is the default twig template.

``` twig
{% spaceless %}
    <div class="vich_info_window">
        <span>{{ obj }}</span>
    </div>
{% endspaceless %}
```

Verbose Configuration Reference
===============================

``` yaml
#app/config.yml
vich_geographical:
    db_driver: ~ # You must configure this option
    query_service: vich_geographical.query_service.default
    map_renderer: vich_geographical.map_renderer.google
    icon_generator: vich_geographical.icon_generator.default

    # jQuery aware google map renderer available
    # map_renderer: vich_geographical.map_renderer.jquery_google

    # Bing map renderer available
    # map_renderer: vich_geographical.map_renderer.bing

    # Leaflet map renderer available
    # map_renderer: vich_geographical.map_renderer.leaflet

    templating:
        engine: twig # or php
        info_window: VichGeographicalBundle:InfoWindow:default.html.twig

    # if you specify the Leaflet map renderer then add your api key as follows
    leaflet:
        api_key: my_api_key

    # if you specify the Bing map renderer then add your api key as follows
    bing:
        api_key: my_api_key
```
