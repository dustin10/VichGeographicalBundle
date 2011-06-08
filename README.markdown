GeographicalBundle
==================

The GeographicalBundle provides automatic geographic coordinate querying for ORM 
entities as well as rendering of javascript maps for the entities in your Symfony2 project using 
annotations. It also allows for object oriented javascript maps to be rendered without 
using any of the coordinate querying features. The bundle uses Google maps by default, but other 
maps are always being integrated and you can always write your own map renderer.

See `Resources/doc/index.rst` for full documentation.

## Currently Supported Map Renderers

The following is a list of currently supported map renderers. Fork this repo and 
add antoher one!

    Google Maps API v3
    Leaflet

## Installation

### Get the bundle

To install the bundle, place it in the `vendor/bundles/Vich/GeographicalBundle` 
directory of your project. You can do this by adding the bundle as a submodule, 
cloning it, or simply downloading the source.

    git submodule add https://github.com/dustin10/GeographicalBundle.git vendor/bundles/Vich/GeographicalBundle

### Add the namespace to your autoloader

Add the `Vich` namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ..
        'Vich' => __DIR__.'/../vendor/bundles'
    ));

### Initialize the bundle

To start using the bundle, register the bundle in your `AppKernel`:

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Vich\GeographicalBundle\VichGeographicalBundle(),
        );
    )

### Configuration

You have to activate the listener for each entity manager. The id is the id of 
the DBAL connection. If you want to use the twig map rendering functions then 
you should also enable them.

    # app/config/config.yml
    vich_geographical:
        orm:
            default: ~
        twig:
            enabled: true

### Annotations

Now you need to annotate the entities you would like to query for coordinates. 
There are two annotations to use, the class annotation `@Vich\Geographical` 
marks the entity as geographical and the `@Vich\GeographicalQuery` annotation 
marks the method in the class whose return value is used as the query to the 
query coordinate service (i.e. the method gets an address to turn into geographical 
coordinates). The following is a working example entity:

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

## Displaying maps with Twig

The Twig extensions in the GeographicalBundle make it easy to render javascript 
maps for your entities. This bundle also allows you to create a map and render it 
without using the coordinate querying or annotation features provided.

See `Resources/doc/index.rst` for full documentation on creating maps.

### Create a Map class

To display a map for your entity first you need to create a class that extends the 
base `Vich\GeographicalBundle\Map\Map` class.

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

            // set some options
            $this->setWidth(400);
            $this->setHeight(250);
        }
    }

The example above will create a map with default options except for the width and 
height which have been set.

Next you will need to declare your map as a service, tag it with the 
`vichgeo.map` tag and give it an alias. Here is an XML example.

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

Next include the services you just defined in your `config.yml` file.

    # app/config/config.yml
    imports:
        - { resource: "@MyBundle/Resources/config/map.xml" }


Now in your Twig template you can render the map using your annotated entity 
or array of entities.

You can include any javascripts the map renderer needs in your `<head>` 
section with the `vichgeo_include_js` Twig function.

    {{ vichgeo_include_js() }}

If your map renderer requires any stylesheets then you can render them in your `<head>` 
section by using the `vichgeo_include_css` function.

    {{ vichgeo_include_css() }}

Now you are ready to render the map. The `vichgeo_map_for` Twig function will render 
the specified using the entity or array of entities passed into the second parameter. 
The function will automatically read the annotations of your entity and fetch the 
location coordinates for the map marker.

    {{ vichgeo_map_for('location', location) }}

Refer to `Resources/doc/index.rst` for full documentation.