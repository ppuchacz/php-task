<?php

declare(strict_types=1);

namespace App\Payroll\UI\Console;

use App\Payroll\Infrastructure\Persistance\Doctrine\Repository\DepartmentRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:department:list', description: 'List departments')]
class ListDepartmentCommand extends Command
{
    public function __construct(
        private DepartmentRepository $departmentRepository
    )
    {
        parent::__construct();
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaders([
            'ID',
            'Name',
            'Bonus type',
            'Max bonus years',
            'Bonus amount',
        ]);

        $departments = $this->departmentRepository->findAll();

        foreach ($departments as $department) {
            $table->addRow([
                $department->getId(),
                $department->getName(),
                $department->getBonusType()->value,
                $department->getMaxBonusYears(),
                $department->getBonusAmount(),
            ]);
        }

        $table->render();

        return self::SUCCESS;
    }
}
