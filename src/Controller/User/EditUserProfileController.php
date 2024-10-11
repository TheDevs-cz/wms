<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TheDevs\WMS\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TheDevs\WMS\FormData\UserProfileFormData;
use TheDevs\WMS\FormType\UserProfileFormType;
use TheDevs\WMS\Message\User\EditUserProfile;

final class EditUserProfileController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/edit-profile', name: 'edit_profile')]
    public function __invoke(Request $request, #[CurrentUser] User $user): Response
    {
        $formData = UserProfileFormData::fromUser($user);
        $form = $this->createForm(UserProfileFormType::class, $formData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bus->dispatch(
                new EditUserProfile(
                    $user->getUserIdentifier(),
                    $formData->name,
                ),
            );

            $this->addFlash('success', 'Profil upraven!');

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('edit_user_profile.html.twig', [
            'form' => $form,
        ]);
    }
}
