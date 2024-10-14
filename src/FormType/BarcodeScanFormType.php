<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use TheDevs\WMS\FormData\BarcodeScanFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<BarcodeScanFormData>
 */
class BarcodeScanFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('code', TextType::class, [
            'required' => true,
            'label' => 'Kód',
            'empty_data' => '',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BarcodeScanFormData::class,
        ]);
    }
}
