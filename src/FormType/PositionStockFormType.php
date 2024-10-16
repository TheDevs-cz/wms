<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheDevs\WMS\FormData\PositionStockFormData;

/**
 * @extends AbstractType<PositionStockFormData>
 */
final class PositionStockFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('quantity', IntegerType::class, [
            'label' => 'Změna o počet ks',
            'required' => true,
            'empty_data' => 0,
        ]);

        $builder->add('code', TextType::class, [
            'label' => 'EAN zboží',
            'required' => true,
            'empty_data' => '',
            'attr' => [
                'placeholder' => 'Skenuj ...',
                'data-controller' => 'barcode-scanner',
                'data-action' => 'barcode-scanner#onInput',
                'autofocus' => true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PositionStockFormData::class,
        ]);
    }
}
