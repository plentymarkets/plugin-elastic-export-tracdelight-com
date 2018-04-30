<?php

namespace ElasticExportTracdelightCOM\ResultField;

use Plenty\Modules\Cloud\ElasticSearch\Lib\ElasticSearch;
use Plenty\Modules\Cloud\ElasticSearch\Lib\Source\Mutator\BuiltIn\LanguageMutator;
use Plenty\Modules\DataExchange\Contracts\ResultFields;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Search\Mutators\BarcodeMutator;
use Plenty\Modules\Item\Search\Mutators\DefaultCategoryMutator;
use Plenty\Modules\Item\Search\Mutators\ImageMutator;
use Plenty\Modules\Item\Search\Mutators\KeyMutator;

/**
 * Class TracdelightCOM
 * @package ElasticExportTracdelightCOM\ResultField
 */
class TracdelightCOM extends ResultFields
{
    const TRACDELIGHT_COM = 130.00;

    /**
     * @var ArrayHelper
     */
    private $arrayHelper;

    /**
     * TracdelightCOM constructor.
     *
     * @param ArrayHelper $arrayHelper
     */
    public function __construct(ArrayHelper $arrayHelper)
    {
        $this->arrayHelper = $arrayHelper;
    }

    /**
     * Creates the fields set to be retrieved from ElasticSearch.
     *
     * @param array $formatSettings
     * @return array
     */
    public function generateResultFields(array $formatSettings = []):array
    {
        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');

        $reference = $settings->get('referrerId') ? $settings->get('referrerId') : self::TRACDELIGHT_COM;

		$this->setOrderByList([
			'path' => 'item.id',
			'order' => ElasticSearch::SORTING_ORDER_ASC]);

        $itemDescriptionFields = ['texts.urlPath', 'texts.keywords', 'texts.lang'];

        $itemDescriptionFields[] = ($settings->get('nameId')) ? 'texts.name' . $settings->get('nameId') : 'texts.name1';

        if($settings->get('descriptionType') == 'itemShortDescription'
            || $settings->get('previewTextType') == 'itemShortDescription')
        {
            $itemDescriptionFields[] = 'texts.shortDescription';
        }
        if($settings->get('descriptionType') == 'itemDescription'
            || $settings->get('descriptionType') == 'itemDescriptionAndTechnicalData'
            || $settings->get('previewTextType') == 'itemDescription'
            || $settings->get('previewTextType') == 'itemDescriptionAndTechnicalData')
        {
            $itemDescriptionFields[] = 'texts.description';
        }
        if($settings->get('descriptionType') == 'technicalData'
            || $settings->get('descriptionType') == 'itemDescriptionAndTechnicalData'
            || $settings->get('previewTextType') == 'technicalData'
            || $settings->get('previewTextType') == 'itemDescriptionAndTechnicalData')
        {
            $itemDescriptionFields[] = 'texts.technicalData';
        }

        // Mutators
        /**
         * @var ImageMutator $imageMutator
         */
        $imageMutator = pluginApp(ImageMutator::class);
        if($imageMutator instanceof ImageMutator)
        {
            $imageMutator->addMarket($reference);
        }

		/**
		 * @var BarcodeMutator $barcodeMutator
		 */
		$barcodeMutator = pluginApp(BarcodeMutator::class);
		if($barcodeMutator instanceof BarcodeMutator)
		{
			$barcodeMutator->addMarket($reference);
		}

		/**
		 * @var DefaultCategoryMutator $defaultCategoryMutator
		 */
		$defaultCategoryMutator = pluginApp(DefaultCategoryMutator::class);

		if($defaultCategoryMutator instanceof DefaultCategoryMutator)
		{
			$defaultCategoryMutator->setPlentyId($settings->get('plentyId'));
		}

        /**
         * @var LanguageMutator $languageMutator
         */
		$languageMutator = pluginApp(LanguageMutator::class, ['language' => [$settings->get('lang')]]);

		/**
		 * @var KeyMutator
		 */
		$keyMutator = pluginApp(KeyMutator::class);

		if($keyMutator instanceof KeyMutator)
		{
			$keyMutator->setKeyList($this->getKeyList());
			$keyMutator->setNestedKeyList($this->getNestedKeyList());
		}

        // Fields
        $fields = [
            [
                // Item
                'item.id',
                'item.manufacturer.id',

                // Variation
                'id',
                'variation.availability.id',
                'variation.stockLimitation',

                // Images
                'images.all.urlMiddle',
                'images.all.urlPreview',
                'images.all.urlSecondPreview',
                'images.all.url',
                'images.all.path',
                'images.all.position',

                'images.item.urlMiddle',
                'images.item.urlPreview',
                'images.item.urlSecondPreview',
                'images.item.url',
                'images.item.path',
                'images.item.position',

                'images.variation.urlMiddle',
                'images.variation.urlPreview',
                'images.variation.urlSecondPreview',
                'images.variation.url',
                'images.variation.path',
                'images.variation.position',

                // Unit
                'unit.id',
                'unit.content',

                // Barcodes
                'barcodes.code',
                'barcodes.type',

                // DefaultCategories
                'defaultCategories.id',

                // Attributes
                'attributes.attributeId',
                'attributes.valueId',
                'attributes.attributeValueSetId',

                // Properties
                'properties.property.id',
                'properties.property.valueType',
                'properties.selection.name',
                'properties.selection.lang',
                'properties.texts.value',
                'properties.texts.lang',
                'properties.valueInt',
                'properties.valueFloat',
            ],

            [
                $barcodeMutator,
                $languageMutator,
				$defaultCategoryMutator,
				$keyMutator,
            ],
        ];

        // Get the associated images if reference is selected
        if($reference != -1)
        {
            $fields[1][] = $imageMutator;
        }

        foreach($itemDescriptionFields as $itemDescriptionField)
        {
            // Texts
            $fields[0][] = $itemDescriptionField;
        }

        return $fields;
    }

    /**
     * Returns the list of keys.
     *
     * @return array
     */
    private function getKeyList()
	{
		return [
			// Item
			'item.id',
			'item.manufacturer.id',

			// Variation
			'variation.availability.id',
			'variation.stockLimitation',

			// Unit
			'unit.content',
			'unit.id',
		];
	}

    /**
     * Returns the list of nested keys.
     *
     * @return mixed
     */
	private function getNestedKeyList()
	{
		return [
			'keys' => [
                // Images
                'images.all',
                'images.item',
                'images.variation',

                // Barcodes
                'barcodes',

                // Default categories
                'defaultCategories',

                // Texts
                'texts',

				// Attributes
				'attributes',

                // Properties
                'properties',
			],

			'nestedKeys' => [
                // Images
                'images.all' => [
                    'urlMiddle',
                    'urlPreview',
                    'urlSecondPreview',
                    'url',
                    'path',
                    'position',
                ],
                'images.item' => [
                    'urlMiddle',
                    'urlPreview',
                    'urlSecondPreview',
                    'url',
                    'path',
                    'position',
                ],
                'images.variation' => [
                    'urlMiddle',
                    'urlPreview',
                    'urlSecondPreview',
                    'url',
                    'path',
                    'position',
                ],

                // Barcodes
                'barcodes' => [
                    'code',
                    'type',
                ],

                // Default categories
                'defaultCategories' => [
                    'id',
                ],

                // Texts
                'texts' => [
                    'urlPath',
                    'name1',
                    'name2',
                    'name3',
                    'shortDescription',
                    'description',
                    'technicalData',
                    'keywords',
                ],

				// Attributes
				'attributes' => [
					'attributeValueSetId',
					'attributeId',
					'valueId',
				],

                // Proprieties
                'properties' => [
                    'property.id',
                    'property.valueType',
                    'selection.name',
                    'selection.lang',
                    'texts.value',
                    'texts.lang',
                    'valueInt',
                    'valueFloat',
                ],
			]
		];
	}
}