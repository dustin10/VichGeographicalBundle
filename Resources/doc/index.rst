The GeographicalBundle provides automatic geographic coordinate querying for the 
entities in your Symfony2 project.

Features
========

This bundle allows you to easily query coordinates for your entities using annotations

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
-------------------------------------------------------

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
You will need to use different annotations. Use the class annotation ``@vich:Geographical`` 
to mark the entity as geographical and the ``@vich:GeographicalQuery`` annotation 
to mark the method in the class whose return value is used as the query to the 
query coordinate service. The method should be public and take no parameters and should 
return a string.

Here is an example entity::

    /**
     * @orm:Entity
     *
     * @vich:Geographical
     */
    class Location
    {
        // ..
        
        /**
         * @orm:Column(type="decimal", scale="7")
         */
        protected $latitude

        /**
         * @orm:Column(type="decimal", scale="7")
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
         * @vich:GeographicalQuery
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

Advanced Use
============

Configuring the Latitude and Longitude Properties
-------------------------------------------------

You can configure which properties of your entity are used to store the latitude 
and longitude coordinates. By default the latitude property is named 'latitude' and 
the longitude property is named 'longitude'. You can set these properties using the 
class annotation.

Here is an example::

    /**
     * @orm:Entity
     *
     * @vich:Geographical(lat="mylat", lng="mylng")
     */
    class Location
    {
        // ..
        
        /**
         * @orm:Column(type="decimal", scale="7")
         */
        protected $mylat

        /**
         * @orm:Column(type="decimal", scale="7")
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

    /**
     * @orm:Entity
     *
     * @vich:Geographical(on="update")
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
