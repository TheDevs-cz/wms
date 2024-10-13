<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\FormData\PositionFormData;
use TheDevs\WMS\Query\LocationQuery;

/**
 * @extends AbstractType<PositionFormData>
 */
final class PositionFormType extends AbstractType
{
    public function __construct(
        readonly private LocationQuery $locationQuery,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('location', EntityType::class, [
            'class' => Location::class,
            'choice_label' => 'name',
            'choices' => $this->locationQuery->getAll(),
            'label' => 'Lokace',
            'required' => true,
            'placeholder' => '- Vybrat -',
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Název pozice',
            'required' => true,
            'empty_data' => '',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PositionFormData::class,
        ]);
    }
}
