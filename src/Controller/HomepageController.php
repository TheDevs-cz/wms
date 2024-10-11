<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller;

use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TheDevs\WMS\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        return $this->redirectToRoute('projects');
    }
}
