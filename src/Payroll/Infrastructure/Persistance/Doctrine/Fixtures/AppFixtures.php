<?php

namespace App\Payroll\Infrastructure\Persistance\Doctrine\Fixtures;

use App\Payroll\Domain\Model\BonusType;
use App\Payroll\Domain\Model\Department;
use App\Payroll\Domain\Model\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $hrDepartment = new Department(
            "HR",
            BonusType::FIXED,
            5,
            200,
        );

        $marketing = new Department(
            "Marketing",
            BonusType::PERCENT,
            10,
            15,
        );

        $bookkeeping = new Department(
            "Bookkeeping",
            BonusType::FIXED,
            3,
            300,
        );

        $manager->persist($hrDepartment);
        $manager->persist($marketing);
        $manager->persist($bookkeeping);

        $manager->persist(new Employee(
            'Jan',
            'Kowalski',
            3000.00,
            $hrDepartment,
            new \DateTime('-5 years'),
        ));

        $manager->persist(new Employee(
            'Kamil',
            'Nowak',
            5000.00,
            $marketing,
            new \DateTime('-2 years')
        ));

        $manager->persist(new Employee(
            'Piotr',
            'Kosiniak',
            7000.00,
            $bookkeeping,
            new \DateTime('-5 years')
        ));

        $manager->flush();
    }
}
