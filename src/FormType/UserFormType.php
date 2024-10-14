<?php
declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\UserFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<UserFormData>
 */
final class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('role', ChoiceType::class, [
            'label' => 'Role',
            'required' => true,
            'placeholder' => '- Vybrat -',
            'choices' => [
                'Zákazník' => User::ROLE_CUSTOMER,
                'Skladník' => User::ROLE_WAREHOUSEMAN,
                'Administrátor' => User::ROLE_ADMIN,
            ]
        ]);

        $builder->add('email', TextType::class, [
            'label' => 'E-mail',
            'required' => true,
            'empty_data' => '',
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Jméno',
            'required' => false,
        ]);

        $builder->add('password', PasswordType::class, [
            'label' => 'Heslo',
            'required' => false,
            'empty_data' => null,
            'always_empty' => false,
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserFormData::class,
        ]);
    }
}
