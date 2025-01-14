<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller\Scan;

use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScanInfoController extends AbstractController
{
    #[Route(path: '/scan/info', name: 'scan_info')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        return $this->render('scan/info.html.twig');
    }
}
