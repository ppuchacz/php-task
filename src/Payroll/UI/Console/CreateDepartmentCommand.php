<?php

declare(strict_types=1);

namespace App\Payroll\UI\Console;

use App\Payroll\Domain\Model\BonusType;
use App\Payroll\Domain\Model\Department;
use App\Payroll\Infrastructure\Persistance\Doctrine\Repository\DepartmentRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:department:create', description: 'Create new department')]
class CreateDepartmentCommand extends Command
{
    public function __construct(
        private DepartmentRepository $departmentRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL)
            ->addArgument('bonusType', InputArgument::OPTIONAL)
            ->addArgument('maxBonusYears', InputArgument::OPTIONAL)
            ->addArgument('bonusAmount', InputArgument::OPTIONAL);
    }

    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();

        $name = $input->getArgument('name') ?? $helper
            ->ask($input, $output, new Question('Name: '));
        $bonusType = $input->getArgument('bonusType') ?? $helper
            ->ask($input, $output, new ChoiceQuestion('Bonus Type (fixed | percent): ', [BonusType::FIXED->value, BonusType::PERCENT->value]));
        $maxBonusYears = $input->getArgument('maxBonusYears') ?? $helper
            ->ask($input, $output, new Question('Max bonus years: '));
        $bonusAmount = $input->getArgument('bonusAmount') ?? $helper
            ->ask($input, $output, new Question('Bonus amount: '));

        $errors = $this->validateArguments(
            $name,
            $bonusType,
            $maxBonusYears,
            $bonusAmount,
        );

        if (empty($errors)) {
            $this->departmentRepository->save(
                new Department(
                    $name,
                    BonusType::from($bonusType),
                    (int) $maxBonusYears,
                    (int) $bonusAmount,
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
        string $bonusType,
        string $maxBonusYears,
        string $bonusAmount,
    ): array
    {
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required to be a string';
        }

        if (empty($bonusType) || !in_array($bonusType, ['fixed', 'percent'])) {
            $errors[] = 'Bonus type value must be one of - "fixed" or "percent"';
        }

        if (empty($maxBonusYears) || !is_numeric($maxBonusYears)) {
            $errors[] = 'Maximum bonus years parameter is required to be a numeric value';
        }

        if (empty($bonusAmount) || !is_numeric($bonusAmount)) {
            $errors[] = 'Bonus amount is required to be a numeric value';
        }

        return $errors;
    }
}
