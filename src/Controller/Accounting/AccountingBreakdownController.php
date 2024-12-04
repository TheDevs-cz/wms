<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Accounting;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\AccountingQuery;

final class AccountingBreakdownController extends AbstractController
{
    public function __construct(
        readonly private AccountingQuery $accountingQuery,
    ) {
    }

    #[Route(path: '/admin/accounting-breakdown', name: 'accounting_breakdown')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('accounting/breakdown.html.twig', [
            'monthly_accounting' => $this->accountingQuery->getMonthlyAccounting(),
        ]);
    }
}
