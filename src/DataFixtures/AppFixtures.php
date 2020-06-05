<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $bleuAgro = new Entreprise();
        $bleuAgro->setNom("Bleu Agro");
        $manager->persist($bleuAgro);

        $sfe = new Entreprise();
        $sfe->setNom("SFE");
        $manager->persist($sfe);

        $manager->flush();

        for($i = 0 ; $i < 5 ; $i++)
        {
            $service = new Service();
            $service->setNom("AM_APT_SERVICE_" . $i);
            $service->setEntreprise($bleuAgro);
            $manager->persist($service);
        }

        for($i = 0 ; $i < 3 ; $i++)
        {
            $service = new Service();
            $service->setNom("AM_APT_SERVICE_" . $i);
            $service->setEntreprise($sfe);
            $manager->persist($service);
        }

        $manager->flush();
    }
}
