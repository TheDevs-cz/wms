<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\User;

use Nette\Utils\Random;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\UserFormData;
use TheDevs\WMS\FormType\UserFormType;
use TheDevs\WMS\Message\User\AddUser;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class UserForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|User $user = null;

    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    /**
     * @return FormInterface<UserFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new UserFormData();

        return $this->createForm(UserFormType::class, $data);
    }

    #[LiveAction]
    public function handleAdd(): Response
    {
        $this->submitForm();

        /** @var UserFormData $data */
        $data = $this->getForm()->getData();

        assert($data->role !== null);

        $plainTextPassword = $data->password ?? Random::generate(30);

        $this->bus->dispatch(
            new AddUser(
                $data->email,
                $plainTextPassword,
                $data->name,
                $data->role
            ),
        );

        if ($data->password === null) {
            $this->addFlash('success', 'Uživatel přidán s náhodně vygenerovaným heslem: ' . $plainTextPassword);
        } else {
            $this->addFlash('success', 'Uživatel přidán');
        }

        return $this->redirectToRoute('users');
    }
}
