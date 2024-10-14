<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\FormData\GeneratePositionsFormData;
use TheDevs\WMS\Query\LocationQuery;

/**
 * @extends AbstractType<GeneratePositionsFormData>
 */
final class GeneratePositionsFormType extends AbstractType
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

        $builder->add('pattern', TextType::class, [
            'label' => 'Šablona názvu pozic',
            'required' => true,
            'empty_data' => '',
            'help' => 'Text <code>{cislo}</code> bude nahrazen pořadovým číslem ze zadané číselné řady. Příklad: <code>AB-{cislo}</code>',
            'help_html' => true,
        ]);

        $builder->add('start', IntegerType::class, [
            'label' => 'Začátek číselné řady',
            'required' => true,
            'empty_data' => 0,
        ]);

        $builder->add('end', IntegerType::class, [
            'label' => 'Konec číselné řady',
            'required' => true,
            'empty_data' => 0,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GeneratePositionsFormData::class,
        ]);
    }
}
