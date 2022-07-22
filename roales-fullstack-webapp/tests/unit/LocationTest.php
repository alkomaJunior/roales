<?php
namespace App\Tests;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Location;

class LocationTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

    private Generator $faker;

    private Location $valid_location;
    private Location $invalid_location;
    
    protected function _before()
    {
        $this->faker = Factory::create();

        $this->valid_location = new Location();
        $this->invalid_location = new Location();

        $this->valid_location
            ->setCity($this->faker->city)
            ->setCityAscii($this->faker->randomAscii)
            ->setLat($this->faker->latitude)
            ->setLng($this->faker->longitude)
            ->setCountry($this->faker->country)
            ->setIso2($this->faker->citySuffix)
            ->setIso3($this->faker->countryISOAlpha3)
            ->setAdminName($this->faker->city)
            ->setCapital($this->faker->countryCode)
            ->setPopulation($this->faker->randomDigitNotZero());

        $this->invalid_location
            ->setCity("")
            ->setCityAscii("")
            ->setLat(0)
            ->setLng(0)
            ->setCountry("")
            ->setIso2($this->faker->citySuffix)
            ->setIso3($this->faker->countryISOAlpha3)
            ->setAdminName($this->faker->city)
            ->setCapital($this->faker->countryCode)
            ->setPopulation($this->faker->randomDigitNotZero());
    }

    protected function _after()
    {}

    // tests
    public function testInvalidValidLocation()
    {
        $this->assertIsString($this->valid_location->getCity());
        $this->assertNotEmpty($this->valid_location->getCityAscii());
        $this->assertIsFloat($this->valid_location->getLat());
        $this->assertEmpty($this->invalid_location->getLng());
        $this->assertIsString($this->invalid_location->getCountry());
        $this->assertIsNotFloat($this->invalid_location->getIso2());
        $this->assertIsNotFloat($this->invalid_location->getIso3());
        $this->assertIsNotInt($this->valid_location->getAdminName());
        $this->assertIsNotInt($this->valid_location->getCapital());
        $this->assertIsInt($this->valid_location->getPopulation());
        $this->assertNull($this->valid_location->getId());
    }
}