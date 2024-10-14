<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\ImportProductFormData;
use TheDevs\WMS\Query\UserQuery;

/**
 * @extends AbstractType<ImportProductFormData>
 */
final class ImportProductFormType extends AbstractType
{
    public function __construct(
        readonly private UserQuery $userQuery,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'email',
            'choices' => $this->userQuery->getAll(),
            'label' => 'Uživatel',
            'required' => true,
            'placeholder' => '- Vybrat -',
        ]);

        $builder->add('file', FileType::class, [
            'label' => 'XML feed',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImportProductFormData::class,
        ]);
    }
}
