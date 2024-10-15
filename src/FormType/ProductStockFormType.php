<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\FormData\ProductStockFormData;
use TheDevs\WMS\Query\PositionQuery;
use TheDevs\WMS\Query\StockItemQuery;

/**
 * @extends AbstractType<ProductStockFormData>
 */
final class ProductStockFormType extends AbstractType
{
    public function __construct(
        readonly private PositionQuery $positionQuery,
        readonly private StockItemQuery $stockItemQuery,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $product = $options['product'];
        assert($product instanceof Product);

        if ($options['only_available_positions'] === true) {
            $positions = array_map(
                fn (StockItem $stockItem): Position => $stockItem->position,
                $this->stockItemQuery->getForProduct($product->id),
            );
        } else {
            $positions = $this->positionQuery->getAll();
        }

        $builder->add('position', EntityType::class, [
            'class' => Position::class,
            'choice_label' => 'name',
            'choices' => $positions,
            'label' => 'Pozice',
            'required' => true,
            'placeholder' => '- Vybrat -',
        ]);

        $builder->add('quantity', IntegerType::class, [
            'label' => 'Změna o počet ks',
            'required' => true,
            'empty_data' => 0,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductStockFormData::class,
            'product' => null,
            'only_available_positions' => false,
        ]);
    }
}
