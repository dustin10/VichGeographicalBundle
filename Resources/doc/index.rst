Features
========

The GeographicalBundle provides automatic geographic coordinate querying for ORM 
entities as well as rendering of javascript maps for the entities in your Symfony2 project using 
annotations. It also allows for object oriented javascript maps to be rendered without 
using any of the coordinate querying features. The bundle uses Google maps by default, but other 
maps are always being integrated and you can always write your own map renderer.

Currently Supported Map Renderers
=================================

The following is a list of currently supported map renderers. Fork this repo and 
add antoher one!

::

    Google Maps API v3
    Leaflet

Installation
============

Add GeographicalBundle to the vendor/bundles/Vich/GeographicalBundle Directory
------------------------------------------------------------------------------

::

    git submodule add git://github.com/dustin10/GeographicalBundle.git vendor/bundles/Vich/GeographicalBundle

Register the Vich Namespace
---------------------------

::

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Vich'  => __DIR__.'/../vendor/bundles',
        // your other namespaces
    ));

Add GeographicalBundle to Your Application Kernel
-------------------------------------------------

::

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Vich\GeographicalBundle\VichGeographicalBundle(),
            // ...
        );
    }

Configure the Bundle
====================

You have to activate the listener for each entity manager. The id is the id of 
the DBAL connection.

in YAML::

    # app/config/config.yml
    vich_geographical:
        orm:
            default: ~


Use the GeographicalBundle Annotations
======================================

Now you need to annotate the entites you would like to query for coordinates. 
You will need to use different annotations. Use the class annotation ``@Vich\Geographical`` 
to mark the entity as geographical and the ``@Vich\GeographicalQuery`` annotation 
to mark the method in the class whose return value is used as the query to the 
query coordinate service. The method should be public and take no parameters and should 
return a string.

Here is an example entity::

    use Doctrine\ORM\Mapping as ORM;
    use Vich\GeographicalBundle\Annotations as Vich;

    /**
     * @ORM\Entity
     *
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

Advanced Annotation Use
=======================

Configuring the Latitude and Longitude Properties
-------------------------------------------------

You can configure which properties of your entity are used to store the latitude 
and longitude coordinates. By default the latitude property is named 'latitude' and 
the longitude property is named 'longitude'. You can set these properties using the 
class annotation.

Here is an example::

    use Doctrine\ORM\Mapping as ORM;
    use Vich\GeographicalBundle\Annotations as Vich;

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

Configuring When the Coordinates are Queried
--------------------------------------------

By default the coordinates are only queried when the entity is persisted. If you 
would like the coordinates to be queried every time the entity is updated then 
you can change the ``on`` option of the Geographical annotation to ``update``.

Here is an example entity::

    use Doctrine\ORM\Mapping as ORM;
    use Vich\GeographicalBundle\Annotations as Vich;

    /**
     * @ORM\Entity
     *
     * @Vich\Geographical(on="update")
     */
    class Location
    {
        // ..
        

Overriding the Coordinate Query Service
---------------------------------------

You can change the query service used to get the coordinates by creating your own 
class which implements ``Vich\GeographicalBundle\QueryService\QueryServiceInterface``. 
By default Google is used.

in YAML::

    # app/config.yml
    vich_geographical:
        orm:
            default: ~
        class:
            query_service: Foo\BarBundle\QueryService\MyQueryService

Twig Integration
================

The GeographicalBundle comes fully equipped with Twig functions to render your 
geographically aware entities using Google Maps API v3 or any mapping service you like, 
as the map rendering is easily overriden. It also allows you to 
create and render maps in an object oriented way without using the 
annotation and features of the bundle for entities. Note: The Twig extensions 
are NOT enabled by default.

Enabling the Twig Extensions
----------------------------

To gain access to the Twig functions packaged with the bundle you must enable them 
in the configuration file.

in YAML::

    #app/config.yml
    vich_geographical:
        twig:
            enabled: true

Creating a Map Class
--------------------

To display a map for your entity first you need to create a class that extends the 
base ``Vich\GeographicalBundle\Map\Map`` class. A good namespace for your map classes 
is ``Map``, but this is not required.

::

    // src/Vendor/MyBundle/Map/LocationMap.php

    namespace Vich\GeographicalBundleExampleBundle\Map;

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

Declare the Map as a Service
----------------------------

In order for the map to be available in the Twig templates you need to declare 
your map as a service and then tag it with the ``vichgeo.map`` tag and give it 
an alias so that you can refer to it in the template.

in XML::

    # Resources/config/map.xml
    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    
        <services>
        
            <service id="vich_geographical_bundle_example.map.location" class="Vich\GeographicalBundleExampleBundle\Map\LocationMap">
                <tag name="vichgeo.map" alias="location" />
            </service>
        
        </services>
    
    </container>

Import the Map Services
-----------------------

Now that you have declared your maps as services you need to import them in the 
``config.yml`` file of your application.

in YML::

    # app/config/config.yml
    imports:
        - { resource: "@MyBundle/Resources/config/map.xml" }

Rendering a Map In Twig
-----------------------

Now that our maps have been declared as services, tagged and imported into the 
application, we are ready to use render them.

You can include any javascripts the map renderer needs in your ``<head>``
section with the ``vichgeo_include_js`` Twig function.

    {{ vichgeo_include_js() }}

If your map renderer requires any stylesheets then you can render them in your ``<head>`` 
section by using the ``vichgeo_include_css`` function.

    {{ vichgeo_include_css() }}

The ``vichgeo_map_for`` Twig function will render the map with the alias specified 
by the first parameter and will use the entity or array of entities passed into 
the second parameter. The function will automatically read the annotations of 
your entities and fetch the coordinates for the marker.

::

    {{ vichgeo_map_for('location', location) }}

If you have a preconfigured map that you would like to render that doesn't need 
any entities specified, then you can use the ``vichgeo_map`` Twig function.

::

    {{ vichgeo_map('location') }}

Example of a Pre-Configured Map
===============================

A pre-configured map is a map that does not use entities that are marked up with 
the GeographicalBundle annotations. Rendering a pre-configured map is no different 
than rendering a map for entities except for the Twig function used and how you 
add markers to the map.

An example pre-configured map class::

    // src/Vendor/MyBundle/Map/LocationMap.php

    namespace Vich\GeographicalBundleExampleBundle\Map;

    use Vich\GeographicalBundle\Map\Map;
    use Vich\GeographicalBundle\Map\MapMarker;
    use Doctrine\ORM\EntityManager;

    /**
     * PreConfiguredMap.
     */
    class PreConfiguredMap extends Map
    {
        /**
         * Constructs a new instance of LocationMap.
         */
        public function __construct(EntityManager $em)
        {
            parent::__construct();

            // set some options
            $this->setAutoZoom(true);
            $this->setShowMapTypeControl(true);
            $this->setShowZoomControl(true):

            // do something here with the EntityManager to get your entities

            foreach ($entities as $entity) {
                $this->addMarker(new MapMarker($entity->getLat(), $entity->getLng()));
            }
        }
    }

In this class, an example of injecting the EntityManager to fetch some locations 
from the database has been used, but you can get your location info however you see 
fit.

The service definition for this map would be a little different because we have 
injected the EntityManager into it.

in XML::

    # Resources/config/map.xml
    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
    
        <services>
        
            <service id="vich_geographical_bundle_example.map.pre_configured" class="Vich\GeographicalBundleExampleBundle\Map\PreConfiguredMap">
                <tag name="vichgeo.map" alias="pre_configured" />
                <argument type="service" id="doctrine.orm.entity_manager" />
            </service>
        
        </services>
    
    </container>

Instead of using ``vichgeo_map_for`` to render the map, a pre-configured map is 
rendered with ``vichgeo_map``.

::

    {{ vichgeo_map('pre_configured') }}

Creating Your Own Map Renderer
==============================

You can create your own map renderer by creating a class that extends 
``Vich\GeographcialBundle\Map\Renderer\AbstractMapRenderer`` or by implementing 
the ``Vich\GeographicalBundle\Map\Renderer\MapRendererInterface``.

Verbose Configuration Reference
===============================
::

    #app/config.yml
    vich_geographical:
        orm:
            default:
                enabled: true
        twig:
            enabled: true
                
        class:
            query_service: Vich\GeographicalBundle\QueryService\GoogleQueryService
            map_renderer: Vich\GeographicalBundle\Map\Renderer\GoogleMapRenderer

            # jQuery aware google map renderer available
            # map_renderer: Vich\GeographicalBundle\Map\Renderer\jQueryAwareGoogleMapRenderer

            # Leaflet map renderer available
            # map_renderer: Vich\GeographicalBundle\Map\Renderer\LeafletMapRenderer

        # if you specify the Leaflet map renderer then add your api key as follows
        leaflet:
            api_key: my_api_key