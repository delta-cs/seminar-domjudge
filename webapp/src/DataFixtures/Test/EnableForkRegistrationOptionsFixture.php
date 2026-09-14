<?php declare(strict_types=1);

namespace App\DataFixtures\Test;

use App\Entity\Configuration;
use Doctrine\Persistence\ObjectManager;

/**
 * The fork puts three self-registration fields behind config options that
 * default to off: the custom team name, and the "add new school" and "no
 * school" affiliation choices. Upstream's form offers all of them
 * unconditionally, so its registration tests need them turned on.
 */
class EnableForkRegistrationOptionsFixture extends AbstractTestDataFixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ([
            'allow_custom_team_name_registration',
            'allow_create_affiliation_during_registration',
            'allow_registration_without_affiliation',
        ] as $name) {
            $configuration = $manager->getRepository(Configuration::class)
                ->findOneBy(['name' => $name]) ?? new Configuration();
            $configuration
                ->setName($name)
                ->setValue(true);
            $manager->persist($configuration);
        }
        $manager->flush();
    }
}
