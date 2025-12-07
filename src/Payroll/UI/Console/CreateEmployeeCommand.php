<?php

declare(strict_types=1);

namespace App\Payroll\UI\Console;

use App\Payroll\Domain\Model\Employee;
use App\Payroll\Infrastructure\Persistance\Doctrine\Repository\DepartmentRepository;
use App\Payroll\Infrastructure\Persistance\Doctrine\Repository\EmployeeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:employee:create', description: 'Create new employee')]
class CreateEmployeeCommand extends Command
{
    public function __construct(
        private EmployeeRepository   $employeeRepository,
        private DepartmentRepository $departmentRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL)
            ->addArgument('surname', InputArgument::OPTIONAL)
            ->addArgument('remunerationBase', InputArgument::OPTIONAL)
            ->addArgument('departmentId', InputArgument::OPTIONAL)
            ->addArgument('jobStartDate', InputArgument::OPTIONAL);
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();

        $name = $input->getArgument('name') ?? $helper
            ->ask($input, $output, new Question('Name: '));
        $surname = $input->getArgument('surname') ?? $helper
            ->ask($input, $output, new Question('Surname: '));
        $remunerationBase = $input->getArgument('remunerationBase') ?? $helper
            ->ask($input, $output, new Question('Remuneration base: '));
        $departmentId = $input->getArgument('departmentId') ?? $helper
            ->ask($input, $output, new Question('Department id: '));
        $jobStartDate = $input->getArgument('jobStartDate') ?? $helper
            ->ask($input, $output, new Question('Job start date: '));

        $errors = $this->validateArguments(
            $name,
            $surname,
            $remunerationBase,
            $departmentId,
            $jobStartDate,
        );

        $department = $this->departmentRepository->find($departmentId);

        if ($department === null) {
            $output->writeln('<error>Department not found</error>');
            return self::FAILURE;
        }

        if (empty($errors)) {
            $this->employeeRepository->save(
                new Employee(
                    $name,
                    $surname,
                    (float) $remunerationBase,
                    $department,
                    new \DateTime($jobStartDate),
                )
            );

            return self::SUCCESS;
        }

        foreach ($errors as $error) {
            $output->writeln("<error>$error</error>");
        }

        return self::FAILURE;
    }

    /**
     * @return string[]
     */
    private function validateArguments(
        string $name,
        string $surname,
        string $remunerationBase,
        string $departmentId,
        string $jobStartDate,
    ): array
    {
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required to be a string';
        }

        if (empty($surname)) {
            $errors[] = 'Surname is required to be a string';
        }

        if (empty($remunerationBase) || !is_numeric($remunerationBase)) {
            $errors[] = 'Remuneration base is required to be a numeric value';
        }

        if (empty($departmentId) || !is_numeric($departmentId)) {
            $errors[] = 'Remuneration base is required to be a numeric value';
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $jobStartDate)) {
            $errors[] = 'Job start date has invalid format. Should be YYYY-MM-DD';
        }

        return $errors;
    }
}
