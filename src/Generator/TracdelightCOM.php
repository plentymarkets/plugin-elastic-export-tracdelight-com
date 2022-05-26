<?php

namespace ElasticExportTracdelightCOM\Generator;

use ElasticExport\Helper\ElasticExportCoreHelper;
use ElasticExport\Helper\ElasticExportPriceHelper;
use ElasticExport\Helper\ElasticExportPropertyHelper;
use ElasticExport\Services\FiltrationService;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValue;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Helper\Models\KeyValue;
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
     * @var ElasticExportCoreHelper
     */
    private $elasticExportHelper;

    /**
     * @var ElasticExportStockHelper
     */
    private $elasticExportStockHelper;

    /**
     * @var ElasticExportPriceHelper
     */
    private $elasticExportPriceHelper;

    /**
     * @var ElasticExportPropertyHelper
     */
    private $elasticExportPropertyHelper;

    /**
     * AttributeValueNameRepositoryContract
     */
    private $attributeValueNameRepository;

    /**
     * @var ArrayHelper
     */
    private $arrayHelper;

    /**
     * @var array
     */
    private $shippingCostCache = [];

    /**
     * @var array
     */
    private $attributesCache = [];

    /**
     * @var array
     */
    private $imageCache = [];

    /**
     * @var FiltrationService
     */
    private $filtrationService;

    /**
     * TracdelightCOM constructor.
     *
     * @param ArrayHelper $arrayHelper
     * @param AttributeValueNameRepositoryContract $attributeValueNameRepository
     */
    public function __construct(ArrayHelper $arrayHelper, AttributeValueNameRepositoryContract $attributeValueNameRepository)
    {
        $this->arrayHelper = $arrayHelper;
        $this->attributeValueNameRepository = $attributeValueNameRepository;
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
        $this->elasticExportStockHelper = pluginApp(ElasticExportStockHelper::class);
        $this->elasticExportPriceHelper = pluginApp(ElasticExportPriceHelper::class);
        $this->elasticExportPropertyHelper = pluginApp(ElasticExportPropertyHelper::class);

        $settings = $this->arrayHelper->buildMapFromObjectList($formatSettings, 'key', 'value');
		$this->filtrationService = pluginApp(FiltrationService::class, ['settings' => $settings, 'filterSettings' => $filter]);

        $this->setDelimiter(self::DELIMITER);

        $this->addCSVContent($this->head());

        if($elasticSearch instanceof VariationElasticSearchScrollRepositoryContract)
        {
            // Initiate the counter for the variations limit
            $limitReached = false;
            $limit = 0;

            do
            {
                if($limitReached === true)
                {
                    break;
                }

                // Get the data from Elastic Search
                $resultList = $elasticSearch->execute();

                if(!is_null($resultList['error']) && count($resultList['error'] ?? []) > 0)
                {
                    $this->getLogger(__METHOD__)->error('ElasticExportTracdelightCOM::logs.occurredElasticSearchErrors', [
                        'Error message' => $resultList['error'],
                    ]);

                    break;
                }

                if(is_array($resultList['documents']) && count($resultList['documents'] ?? []) > 0)
                {
                    $previousItemId = null;

                    foreach($resultList['documents'] as $variation)
                    {
                        // Stop and set the flag if limit is reached
                        if($limit == $filter['limit'])
                        {
                            $limitReached = true;
                            break;
                        }

                        // If filtered by stock is set and stock is negative, then skip the variation
                        if ($this->filtrationService->filter($variation))
                        {
                            continue;
                        }

                        try
                        {
                            // Set the caches if we have the first variation or when we have the first variation of an item
                            if($previousItemId === null || $previousItemId != $variation['data']['item']['id'])
                            {
                                $previousItemId = $variation['data']['item']['id'];
                                unset($this->shippingCostCache);

                                // Build the caches arrays
                                $this->buildCaches($variation, $settings);
                            }

                            // Build the new row for printing in the CSV file
                            $this->buildRow($variation, $settings);
                        }
                        catch(\Throwable $throwable)
                        {
                            $this->getLogger(__METHOD__)->error('ElasticExportTracdelightCOM::logs.fillRowError', [
                                'Error message ' => $throwable->getMessage(),
                                'Error line'     => $throwable->getLine(),
                                'VariationId'    => $variation['id']
                            ]);
                        }

                        // New line was added
                        $limit++;
                    }
                }

            } while ($elasticSearch->hasNext());
        }
    }

    /**
     * Creates the header of the CSV file.
     *
     * @return array
     */
    private function head():array
    {
        return array(

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
        );
    }

    /**
     * Creates the variation row and prints it into the CSV file.
     *
     * @param $variation
     * @param KeyValue $settings
     */
    private function buildRow($variation, KeyValue $settings)
    {
        // Get the price list
        $priceList = $this->elasticExportPriceHelper->getPriceList($variation, $settings);

        // Only variations with the Retail Price greater than zero will be handled
        if(!is_null($priceList['price']) && $priceList['price'] > 0)
        {
            // Get shipping cost
            $shippingCost = $this->getShippingCost($variation);

            $data = [

                // Mandatory fields
                'Artikelnummer'         => $variation['id'],
                'Produkttitel'          => $this->elasticExportHelper->getMutatedName($variation, $settings),
                'Bild-URL'              => $this->elasticExportHelper->getMainImage($variation, $settings),
                'Deeplink'              => $this->elasticExportHelper->getMutatedUrl($variation, $settings, true, false),
                'Produkt-Kategorie'     => $this->elasticExportHelper->getCategory((int)$variation['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId')),
                'Produkt-Beschreibung'  => $this->elasticExportHelper->getMutatedDescription($variation, $settings, 256),
                'Preis'                 => $priceList['price'],
                'Währung'               => $priceList['currency'],
                'Marke'                 => $this->elasticExportHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id']),
                'Versandkosten'         => $shippingCost,
                'Geschlecht'            => $this->getProperty($variation, $settings, 'gender'), // only mandatory for clothes
                'Grundpreis'            => $this->elasticExportPriceHelper->getBasePrice($variation, $priceList['price'], $settings->get('lang')), // only mandatory for cosmetics

                // Optional fields
                'Streichpreis'          => ($priceList['recommendedRetailPrice'] > $priceList['price']) ? $priceList['recommendedRetailPrice'] : '',
                'Lieferzeit'            => $this->elasticExportHelper->getAvailability($variation, $settings, false),
                'Produktstamm-ID'       => $variation['data']['item']['id'],
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

        unset($this->imageCache);

        $this->imageCache[$variation['id']] = $this->elasticExportHelper->getImageListInOrder($variation, $settings, 4, ElasticExportCoreHelper::VARIATION_IMAGES);

        return $this->returnImagePath($variation, $number);
    }

    /**
     * Get the variation image path.
     *
     * @param $variation
     * @param int $number
     * @return string
     */
    private function returnImagePath($variation, int $number):string
    {
        if(array_key_exists($number, $this->imageCache[$variation['id']]))
        {
            return $this->imageCache[$variation['id']][$number];
        }

        return '';
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
        if(!array_key_exists($variation['id'], $this->attributesCache))
        {
            unset($this->attributesCache);

            $variationAttributes = [];

            // Go through all the attributes
            foreach($variation['data']['attributes'] as $variationAttribute)
            {
                $attributeValueName = $this->attributeValueNameRepository->findOne($variationAttribute['valueId'], $settings->get('lang'));

                if($attributeValueName instanceof AttributeValueName)
                {
                    if($attributeValueName->attributeValue instanceof AttributeValue)
                    {
                        // Check if the attribute has value for the Tracdelight market
                        if(!empty($attributeValueName->attributeValue->tracdelightValue))
                        {
                            // Check if it has the requested backendName
                            if($attributeValueName->attributeValue->attribute->backendName == 'Gender' ||
                                $attributeValueName->attributeValue->attribute->backendName == 'Size'  ||
                                $attributeValueName->attributeValue->attribute->backendName == 'Color' ||
                                $attributeValueName->attributeValue->attribute->backendName == 'Material'
                            )
                            {
                                $variationAttributes[strtolower($attributeValueName->attributeValue->attribute->backendName)] = $attributeValueName->attributeValue->tracdelightValue;
                            }
                        }
                    }
                }
            }

            $this->attributesCache[$variation['id']] = $variationAttributes;
        }

        return $this->attributesCache[$variation['id']];
    }

    /**
     * Get property.
     *
     * @param array $variation
     * @param KeyValue $settings
     * @param string $backendName
     * @return string
     */
    public function getProperty($variation, KeyValue $settings, string $backendName):string
    {
        // Get the cached properties for the item
        $itemPropertyList = $this->elasticExportPropertyHelper->getItemPropertyList($variation, self::TRACDELIGHT_COM, $settings->get('lang'));

        // Get the cached attributes of the variation
        $variationAttributesList = $this->getVariationAttributes($variation, $settings);

        if(array_key_exists($backendName, $variationAttributesList))
        {
            return (string)$variationAttributesList[$backendName];
        }

        if(array_key_exists($backendName, $itemPropertyList))
        {
            return (string)$itemPropertyList[$backendName];
        }

        return '';
    }

    /**
     * Get the shipping cost.
     *
     * @param $variation
     * @return string
     */
    private function getShippingCost($variation):string
    {
        $shippingCost = null;
        if(isset($this->shippingCostCache) && array_key_exists($variation['data']['item']['id'], $this->shippingCostCache))
        {
            $shippingCost = $this->shippingCostCache[$variation['data']['item']['id']];
        }

        if(!is_null($shippingCost) && $shippingCost > 0)
        {
            return number_format((float)$shippingCost, 2, '.', '');
        }

        return '';
    }

    /**
     * Build the cache array for the item variation.
     *
     * @param array $variation
     * @param KeyValue $settings
     */
    private function buildCaches($variation, KeyValue $settings)
    {
        if(!is_null($variation) && !is_null($variation['data']['item']['id']))
        {
            $shippingCost = $this->elasticExportHelper->getShippingCost($variation['data']['item']['id'], $settings, 0);
            $this->shippingCostCache[$variation['data']['item']['id']] = (float)$shippingCost;
        }
    }
}
