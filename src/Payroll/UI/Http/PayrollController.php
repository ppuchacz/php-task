<?php

declare(strict_types=1);

namespace App\Payroll\UI\Http;

use App\Payroll\Application\Handler\ListPayrollQueryHandler;
use App\Payroll\Application\Query\ListPayrollQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class PayrollController extends AbstractController
{
    public function __construct(
        private ListPayrollQueryHandler $handler
    ) {
    }

    public function list(
        #[MapQueryParameter] ?string $department = null,
        #[MapQueryParameter] ?string $name = null,
        #[MapQueryParameter] ?string $surname = null,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT)] ?int $page = null,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_INT)] ?int $limit = null,
        #[MapQueryParameter] ?string $orderByColumn = null,
        #[MapQueryParameter] ?string $orderByDirection = null,
): JsonResponse {
        if (!in_array($orderByColumn, ['name', 'surname', 'department', null], true)) {
            return $this->json(['error' => 'Invalid orderByColumn value.'], Response::HTTP_BAD_REQUEST);
        }
        if (!in_array($orderByDirection, ['asc', 'desc', null], true)) {
            return $this->json(['error' => 'Invalid orderByDirection value.'], Response::HTTP_BAD_REQUEST);
        }

        $view = $this->handler->handle(new ListPayrollQuery(
            $department,
            $name,
            $surname,
            $page ?? 1,
            $limit ?? 20,
            $orderByColumn,
            $orderByDirection,
        ));

        return $this->json($view);
    }
}
