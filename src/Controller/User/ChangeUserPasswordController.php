<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\ChangePasswordFormData;
use TheDevs\WMS\FormType\ChangePasswordFormType;
use TheDevs\WMS\Message\User\ChangePassword;

final class ChangeUserPasswordController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/change-password', name: 'change_password')]
    public function __invoke(Request $request, #[CurrentUser] UserInterface $user): Response
    {
        $formData = new ChangePasswordFormData();
        $form = $this->createForm(ChangePasswordFormType::class, $formData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch(
                new ChangePassword(
                    $user->getUserIdentifier(),
                    $formData->password,
                ),
            );

            $this->addFlash('success', 'Heslo změněno');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
