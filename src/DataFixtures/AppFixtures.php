<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Country;
use App\Entity\Dictionary\Position;
use App\Entity\Footballer;
use App\Entity\League;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();
        $positions = $manager->getRepository(Position::class)->findAll();
        $clubs = $manager->getRepository(Club::class) -> findAll();

/*
        //Country
        $country = new Country();
        $country->setName("Poland");
        $manager->persist($country);


        //League
        $league = new League();
        $league->setCountry("Polska");
        $league->setName("Testowa liga");
        $manager->persist($league);

        //Clubs
        for ($c = 0; $c < 15; $c++) {

            $club = new Club();

            $city = $faker->city;
            $club->setName("F.C. " . $city);
            $club->setCity($city);
            $club->setStadium($city . " Stadium");
            $club->setLeague($league);

            $manager->persist($club);


            foreach ($positions as $pos) {

                for ($p = 0; $p < 3; $p++) {

                    $fb = new Footballer();
                    $fb->setName($faker->firstNameMale);
                    $fb->setSurname(ucfirst($faker->word));
                    $fb->setBirthdate($faker->dateTime);
                    $fb->setAssists(0);
                    $fb->setGoals(0);
                    $fb->setRedCards(0);
                    $fb->setYellowCards(0);
                    $fb->setPosition($pos);
                    $fb->setClub($club);
                    $fb->setCountry($country);

                    $manager->persist($fb);

                }

            }


            $manager->flush();
        }
*/
/*
        $country = $manager->getRepository(Country::class)->find(1);
        foreach ($clubs as $club)
        {
            foreach ($positions as $pos) {

                for ($p = 0; $p < 3; $p++) {

                    $fb = new Footballer();
                    $fb->setName($faker->firstNameMale);
                    $fb->setSurname(ucfirst($faker->word));
                    $fb->setBirthdate($faker->dateTime);
                    $fb->setAssists(0);
                    $fb->setGoals(0);
                    $fb->setRedCards(0);
                    $fb->setYellowCards(0);
                    $fb->setPosition($pos);
                    $fb->setClub($club);
                    $fb->setCountry($country);

                    $src = __DIR__."/public/upload/images/peson.png";
                    $file = new UploadedFile(
                        $src,
                        'peson.png',
                        'png',
                        filesize($src),
                        null,
                        true
                    );
                    $fb->setImageFile($file);

                    $manager->persist($fb);

                }

            }
        }
*/
    }
}
