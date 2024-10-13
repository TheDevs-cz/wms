<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\FormData\LocationFormData;
use TheDevs\WMS\Query\WarehouseQuery;

/**
 * @extends AbstractType<LocationFormData>
 */
final class LocationFormType extends AbstractType
{
    public function __construct(
        readonly private WarehouseQuery $warehouseQuery,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('warehouse', EntityType::class, [
            'class' => Warehouse::class,
            'choice_label' => 'title',
            'choices' => $this->warehouseQuery->getAll(),
            'label' => 'Sklad',
            'required' => true,
            'placeholder' => '- Vybrat -',
        ]);

        $builder->add('name', TextType::class, [
            'label' => 'Název lokace',
            'required' => true,
            'empty_data' => '',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LocationFormData::class,
        ]);
    }
}
