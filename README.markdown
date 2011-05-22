GeographicalBundle
==========

The GeographicalBundle provides automatic geographic coordinate querying for the 
entities in your Symfony2 project.

See `Resources/doc/index.rst` for full documentation.

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
the DBAL connection.

    # app/config/config.yml
    vich_geographical:
        orm:
            default: ~

### Annotations

Now you need to annotate the entites you would like to query for coordinates. 
There are two annotations to use. You will need to use different annotations.  
You will use the class annotation `@vich:Geographical` to mark the entity as 
geographical and the `@vich:GeographicalQuery` annotation to mark the method in 
the class whose return value is used as the query to the query coordinate service. 
The following is a working example entity:

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