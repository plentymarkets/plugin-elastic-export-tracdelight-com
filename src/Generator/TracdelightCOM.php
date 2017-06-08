<?php

namespace ElasticExportTracdelightCOM\Generator;

use ElasticExport\Helper\ElasticExportCoreHelper;
use ElasticExport\Helper\ElasticExportPriceHelper;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Property\Contracts\PropertyMarketReferenceRepositoryContract;
use Plenty\Modules\Item\Property\Contracts\PropertyNameRepositoryContract;
use Plenty\Modules\Item\Property\Contracts\PropertySelectionRepositoryContract;
use Plenty\Modules\Item\Search\Contracts\VariationElasticSearchScrollRepositoryContract;
use ElasticExport\Helper\ElasticExportStockHelper;
use Plenty\Plugin\Log\Loggable;

/**
 * Class TracdelightCOM
 * @package ElasticExportTracdelightCOM\Generator
 */
class TracdelightCOM extends CSVPluginGenerator
{
	use Loggable;

    const TRACDELIGHT_COM = 130.00;

    const DELIMITER = ';';

	/**
	 * @var array $imageCache
	 */
    private $imageCache;

    /**
     * @var ElasticExportCoreHelper
     */
    private $elasticExportHelper;

    /**
     * AttributeValueNameRepositoryContract $attributeValueNameRepository
     */
    private $attributeValueNameRepository;

    /**
     * PropertySelectionRepositoryContract $propertySelectionRepository
     */
    private $propertySelectionRepository;

    /**
     * @var ArrayHelper
     */
    private $arrayHelper;

    /**
     * @var array
     */
    private $itemPropertyCache = [];

    /**
     * @var array
     */
    private $idlVariations = array();
	/**
	 * @var ElasticExportPriceHelper
	 */
	private $elasticExportPriceHelper;
	/**
	 * @var ElasticExportStockHelper
	 */
	private $elasticExportStockHelper;


	/**
	 * TracdelightCOM constructor.
	 *
	 * @param ArrayHelper $arrayHelper
	 * @param AttributeValueNameRepositoryContract $attributeValueNameRepository
	 * @param PropertySelectionRepositoryContract $propertySelectionRepository
	 */
    public function __construct(ArrayHelper $arrayHelper,
                                AttributeValueNameRepositoryContract $attributeValueNameRepository,
                                PropertySelectionRepositoryContract $propertySelectionRepository)
    {
        $this->arrayHelper                  = $arrayHelper;
        $this->attributeValueNameRepository = $attributeValueNameRepository;
        $this->propertySelectionRepository  = $propertySelectionRepository;
	}
    
    /**
     * Generates and populates the data into the CSV file.
     * 
     * @param VariationElasticSearchScrollRepositoryContract $elasticSearch
     * @param array $formatSettings
     * @param array $filter
     */
    protected function generatePluginContent($elasticSearch, array $formatSettings = [], array $filter = [])
    {
        $this->elasticExportHelper = pluginApp(ElasticExportCoreHelper::class);
        $this->elasticExportPriceHelper = pluginApp(ElasticExportPriceHelper::class);
        $this->elasticExportStockHelper = pluginApp(ElasticExportStockHelper::class);

		$settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');

		$this->setDelimiter(self::DELIMITER);

		$this->addCSVContent([

			// Mandatory fields
			'Artikelnummer',
			'Produkttitel',
			'Bild-URL',
			'Deeplink',
			'Produkt-Kategorie',
			'Produkt-Beschreibung',
			'Preis',
			'Währung',
			'Marke',
			'Versandkosten',
			'Geschlecht', // only mandatory for clothes
			'Grundpreis', // only mandatory for cosmetics

			// Optional fields
			'Streichpreis',
			'Lieferzeit',
			'Produktstamm-ID',
			'EAN',
			'Bild2-URL',
			'Bild3-URL',
			'Bild4-URL',
			'Bild5-URL',
			'Größe',
			'Farbe',
			'Material',
		]);

		if($elasticSearch instanceof VariationElasticSearchScrollRepositoryContract)
		{
			$limitReached = false;
			$lines = 0;
			do
			{
				if($limitReached === true)
				{
					break;
				}

				$resultList = $elasticSearch->execute();

				foreach($resultList['documents'] as $variation)
				{
					if($lines == $filter['limit'])
					{
						$limitReached = true;
						break;
					}

					if(is_array($resultList['documents']) && count($resultList['documents']) > 0)
					{
						if($this->elasticExportStockHelper->isFilteredByStock($variation, $filter) === true)
						{
							continue;
						}

						try
						{
							$this->buildRow($variation, $settings);
							$lines = $lines +1;
						}
						catch(\Throwable $throwable)
						{
							$this->getLogger(__METHOD__)->error('ElasticExportTracdelightCOM::logs.fillRowError', [
								'Error message ' => $throwable->getMessage(),
								'Error line'    => $throwable->getLine(),
								'VariationId'   => $variation['id']
							]);
						}
					}
				}
			}while ($elasticSearch->hasNext());
		}
    }

	/**
	 * @param $variation
	 * @param $settings
	 */
    private function buildRow($variation, $settings)
	{
		$priceList = $this->elasticExportPriceHelper->getPriceList($variation, $settings);

		$price['variationRetailPrice.price'] = $priceList['price'];
		$rrp = $priceList['recommendedRetailPrice'];

		$rrp = $rrp > $price['variationRetailPrice.price'] ? $rrp : '';

		// Get shipping costs
		$shippingCost = $this->elasticExportHelper->getShippingCost($variation['data']['item']['id'], $settings);
		if(!is_null($shippingCost))
		{
			$shippingCost = number_format((float)$shippingCost, 2, '.');
		}
		else
		{
			$shippingCost = '';
		}

		$data = [

			// Mandatory fields
			'Artikelnummer'         => $variation['id'],
			'Produkttitel'          => $this->elasticExportHelper->getMutatedName($variation, $settings),
			'Bild-URL'              => $this->elasticExportHelper->getMainImage($variation, $settings),
			'Deeplink'              => $this->elasticExportHelper->getMutatedUrl($variation, $settings, true, false),
			'Produkt-Kategorie'     => $this->elasticExportHelper->getCategory((int)$variation['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId')),
			'Produkt-Beschreibung'  => $this->elasticExportHelper->getMutatedDescription($variation, $settings, 256),
			'Preis'                 => $price['variationRetailPrice.price'],
			'Währung'               => $priceList['currency'],
			'Marke'                 => $this->elasticExportHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id']),
			'Versandkosten'         => $shippingCost,
			'Geschlecht'            => $this->getProperty($variation, $settings, 'gender'), // only mandatory for clothes
			'Grundpreis'            => $this->elasticExportHelper->getBasePrice($variation, $price, $settings->get('lang')), // only mandatory for cosmetics

			// Optional fields
			'Streichpreis'          => $rrp,
			'Lieferzeit'            => $this->elasticExportHelper->getAvailability($variation, $settings, false),
			'Produktstamm-ID'       => $variation['id'],
			'EAN'                   => $this->elasticExportHelper->getBarcodeByType($variation, $settings->get('barcode')),
			'Bild2-URL'             => $this->getImageByNumber($variation, $settings, 1),
			'Bild3-URL'             => $this->getImageByNumber($variation, $settings, 2),
			'Bild4-URL'             => $this->getImageByNumber($variation, $settings, 3),
			'Bild5-URL'             => $this->getImageByNumber($variation, $settings, 4),
			'Größe'                 => $this->getProperty($variation, $settings, 'size'),
			'Farbe'                 => $this->getProperty($variation, $settings, 'color'),
			'Material'              => $this->getProperty($variation, $settings, 'material')
		];

		$this->addCSVContent(array_values($data));
	}

	/**
	 * Get variation image by number.
	 *
	 * @param array $variation
	 * @param KeyValue $settings
	 * @param int $number
	 * @return string
	 */
	private function getImageByNumber($variation, KeyValue $settings, int $number):string
	{
		if(array_key_exists($variation['id'], $this->imageCache))
		{
			return $this->returnImagePath($variation, $number);
		}

		$this->imageCache[$variation['id']] = $this->elasticExportHelper->getImageListInOrder($variation, $settings, 4, 'variationImages');

		return $this->returnImagePath($variation, $number);
	}

	/**
	 * @param $variation
	 * @param int $number
	 * @return string
	 */
	private function returnImagePath($variation, int $number)
	{
		if(array_key_exists($number, $this->imageCache[$variation['id']]))
		{
			return $this->imageCache[$variation['id']][$number];
		}
		else
		{
			return '';
		}
	}

    /**
     * Get variation attributes.
     *
     * @param  array    $variation
     * @param  KeyValue $settings
     * @return array<string,string>
     */
    private function getVariationAttributes($variation, KeyValue $settings):array
    {
        $variationAttributes = [];

        // Go through all the attributes
        foreach($variation['data']['attributes'] as $variationAttribute)
        {
            $attributeValueName = $this->attributeValueNameRepository->findOne($variationAttribute['valueId'], $settings->get('lang'));

            if($attributeValueName instanceof AttributeValueName)
            {
                // Check if the attribute is available for Tracdelight
                if($attributeValueName->attributeValue->tracdelightValue)
                {
                    // Get the color and size attribute value
                    if(($attributeValueName->attributeValue->attribute->backendName == 'Color' || $attributeValueName->attributeValue->attribute->backendName == 'Size')
                        && !is_null($attributeValueName->attributeValue->tracdelightValue))
                    {
                        $variationAttributes[strtolower($attributeValueName->attributeValue->attribute->backendName)] = $attributeValueName->attributeValue->tracdelightValue;
                    }
                }
            }
        }

        return $variationAttributes;
    }

	/**
	 * Get property.
	 *
	 * @param array $variation
	 * @param KeyValue $settings
	 * @param string $propertyType
	 * @return string
	 */
	public function getProperty($variation, KeyValue $settings, string $propertyType):string
	{
		$itemPropertyList = $this->getItemPropertyList($variation, $settings);

		if(array_key_exists($propertyType, $itemPropertyList))
		{
			return $itemPropertyList[$propertyType];
		}

		return '';
	}

	/**
	 * Returns a list of additional header for the CSV based on
	 * the configured properties and builds also the property data for
	 * further usage. The properties have to have a configuration for BeezUp.
	 *
	 * @param array $variation
	 * @param KeyValue $settings
	 * @return array
	 */
	private function getItemPropertyList($variation, $settings):array
	{
		if(!array_key_exists($variation['data']['item']['id'], $this->itemPropertyCache))
		{
			/**
			 * @var PropertyNameRepositoryContract $propertyNameRepository
			 */
			$propertyNameRepository = pluginApp(PropertyNameRepositoryContract::class);

			/**
			 * @var PropertyMarketReferenceRepositoryContract $propertyMarketReferenceRepository
			 */
			$propertyMarketReferenceRepository = pluginApp(PropertyMarketReferenceRepositoryContract::class);

			if(!$propertyNameRepository instanceof PropertyNameRepositoryContract ||
				!$propertyMarketReferenceRepository instanceof PropertyMarketReferenceRepositoryContract)
			{
				return [];
			}

			$list = array();

			foreach($variation['data']['properties'] as $property)
			{
				if(!is_null($property['property']['id']) &&
					$property['property']['valueType'] != 'file' &&
					$property['property']['valueType'] != 'empty')
				{
					$propertyMarketReference = $propertyMarketReferenceRepository->findOne($property['property']['id'], self::TRACDELIGHT_COM);

					if(
						is_null($propertyMarketReference) ||
						$propertyMarketReference->externalComponent == '0'
					)
					{
						continue;
					}


					if($property['property']['valueType'] == 'text')
					{
						if(is_array($property['texts']) && !is_null($property['texts']['value']))
						{
							$list[(string)$propertyMarketReference->externalComponent] = $property['texts']['value'];
						}
					}
					if($property['property']['valueType'] == 'selection')
					{
						if(is_array($property['selection']) && !is_null($property['selection']['name']))
						{
							$list[(string)$propertyMarketReference->externalComponent] = $property['selection']['name'];
						}
					}
				}
			}
			$this->itemPropertyCache[$variation['data']['item']['id']] = array_merge($this->getVariationAttributes($variation, $settings), $list);
		}
		return $this->itemPropertyCache[$variation['data']['item']['id']];
	}
}
