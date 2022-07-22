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
    protected UnitTester $tester;
    private Location $validLocation;
    private Location $invalidLocation;

    protected function _before()
    {
        $faker = Factory::create();

        $this->validLocation = new Location();
        $this->invalidLocation = new Location();

        $this->validLocation
            ->setCity($faker->city)
            ->setCityAscii($faker->randomAscii)
            ->setLat($faker->latitude)
            ->setLng($faker->longitude)
            ->setCountry($faker->country)
            ->setIsoTwo($faker->citySuffix)
            ->setIsoThree($faker->countryISOAlpha3)
            ->setAdminName($faker->city)
            ->setCapital($faker->countryCode)
            ->setPopulation($faker->randomDigitNotZero());

        $this->invalidLocation
            ->setCity("")
            ->setCityAscii("")
            ->setLat(0)
            ->setLng(0)
            ->setCountry("")
            ->setIsoTwo($faker->citySuffix)
            ->setIsoThree($faker->countryISOAlpha3)
            ->setAdminName($faker->city)
            ->setCapital($faker->countryCode)
            ->setPopulation($faker->randomDigitNotZero());
    }

    /**
     * @return void
     */
    public function testInvalidValidLocation(): void
    {
        $this->assertIsString($this->validLocation->getCity());
        $this->assertNotEmpty($this->validLocation->getCityAscii());
        $this->assertIsFloat($this->validLocation->getLat());
        $this->assertEmpty($this->invalidLocation->getLng());
        $this->assertIsString($this->invalidLocation->getCountry());
        $this->assertIsNotFloat($this->invalidLocation->getIsoTwo());
        $this->assertIsNotFloat($this->invalidLocation->getIsoThree());
        $this->assertIsNotInt($this->validLocation->getAdminName());
        $this->assertIsNotInt($this->validLocation->getCapital());
        $this->assertIsInt($this->validLocation->getPopulation());
        $this->assertNull($this->validLocation->getId());
    }
}
