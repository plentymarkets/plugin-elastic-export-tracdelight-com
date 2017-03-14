<?php

namespace ElasticExportTracdelightCOM\Generator;

use ElasticExport\Helper\ElasticExportCoreHelper;
use Plenty\Modules\DataExchange\Contracts\CSVPluginGenerator;
use Plenty\Modules\Helper\Services\ArrayHelper;
use Plenty\Modules\Item\DataLayer\Models\Record;
use Plenty\Modules\Item\DataLayer\Models\RecordList;
use Plenty\Modules\Item\Attribute\Contracts\AttributeValueNameRepositoryContract;
use Plenty\Modules\Item\Attribute\Models\AttributeValueName;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\Property\Contracts\PropertySelectionRepositoryContract;
use Plenty\Modules\Item\Property\Models\PropertySelection;


/**
 * Class TracdelightCOM
 * @package ElasticExportTracdelightCOM\Generator
 */
class TracdelightCOM extends CSVPluginGenerator
{
    const TRACDELIGHT_COM = 130.00;

    const DELIMITER = ';';
    
    /**
     * @var ElasticExportCoreHelper
     */
    private $elasticExportCoreHelper;

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
     * @param mixed $resultData
     * @param array $formatSettings
     * @param array $filter
     */
    protected function generatePluginContent($resultData, array $formatSettings = [], array $filter = [])
    {
        $this->elasticExportCoreHelper = pluginApp(ElasticExportCoreHelper::class);

        if(is_array($resultData) && count($resultData['documents']) > 0)
        {
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

            // Create a List with all VariationIds
            $variationIdList = array();
            foreach($resultData['documents'] as $variation)
            {
                $variationIdList[] = $variation['id'];
            }

            // Get the missing ES fields from IDL(ItemDataLayer)
            if(is_array($variationIdList) && count($variationIdList) > 0)
            {
                /**
                 * @var \ElasticExportTracdelightCOM\IDL_ResultList\TracdelightCOM $idlResultList
                 */
                $idlResultList = pluginApp(\ElasticExportTracdelightCOM\IDL_ResultList\TracdelightCOM::class);
                $idlResultList = $idlResultList->getResultList($variationIdList, $settings, $filter);
            }

            // Creates an array with the variationId as key to surpass the sorting problem
            if(isset($idlResultList) && $idlResultList instanceof RecordList)
            {
                $this->createIdlArray($idlResultList);
            }

            foreach($resultData['documents'] as $variation)
            {
                // Get and set the price and rrp
                $price = $this->idlVariations[$variation['id']]['variationRetailPrice.price'];
                $rrp = $this->elasticExportCoreHelper->getRecommendedRetailPrice($this->idlVariations[$variation['id']]['variationRecommendedRetailPrice.price'], $settings);

                $rrp = $rrp > $price ? $rrp : '';

                // Get shipping costs
                $shippingCost = $this->elasticExportCoreHelper->getShippingCost($variation['data']['item']['id'], $settings);
                if(!is_null($shippingCost))
                {
                    $shippingCost = number_format((float)$shippingCost, 2, ',', '');
                }
                else
                {
                    $shippingCost = '';
                }

                $data = [

                    // Mandatory fields
                    'Artikelnummer'         => $variation['id'],
                    'Produkttitel'          => $this->elasticExportCoreHelper->getName($variation, $settings),
                    'Bild-URL'              => $this->elasticExportCoreHelper->getMainImage($variation, $settings),
                    'Deeplink'              => $this->elasticExportCoreHelper->getUrl($variation, $settings, true, false),
                    'Produkt-Kategorie'     => $this->elasticExportCoreHelper->getCategory((int)$variation['data']['defaultCategories'][0]['id'], $settings->get('lang'), $settings->get('plentyId')),
                    'Produkt-Beschreibung'  => $this->elasticExportCoreHelper->getDescription($variation, $settings, 256),
                    'Preis'                 => number_format((float)$price, 2, '.', ''),
                    'Währung'               => $this->idlVariations[$variation['id']]['variationRetailPrice.currency'],
                    'Marke'                 => $this->elasticExportCoreHelper->getExternalManufacturerName((int)$variation['data']['item']['manufacturer']['id']),
                    'Versandkosten'         => $shippingCost,
                    'Geschlecht'            => $this->getProperty($variation, $settings, 'size') ? $this->getProperty($variation, $settings, 'size') : $this->getProperty($variation, $settings, 'gender'), // only mandatory for clothes
                    'Grundpreis'            => $this->elasticExportCoreHelper->getBasePrice($variation, $this->idlVariations[$variation['id']], $settings->get('lang')), // only mandatory for cosmetics
                    
                    // Optional fields
                    'Streichpreis'          => number_format((float)$rrp, 2, '.', ''),
                    'Lieferzeit'            => $this->elasticExportCoreHelper->getAvailability($variation, $settings, false),
                    'Produktstamm-ID'       => $variation['id'],
                    'EAN'                   => $this->elasticExportCoreHelper->getBarcodeByType($variation, $settings->get('barcode')),
                    'Bild2-URL'             => $this->getImageByNumber($variation, $settings, 2),
                    'Bild3-URL'             => $this->getImageByNumber($variation, $settings, 3),
                    'Bild4-URL'             => $this->getImageByNumber($variation, $settings, 4),
                    'Bild5-URL'             => $this->getImageByNumber($variation, $settings, 5),
                    'Größe'                 => $this->getProperty($variation, $settings, 'size'),
                    'Farbe'                 => $this->getProperty($variation, $settings, 'color'),
                    'Material'              => $this->getProperty($variation, $settings, 'material')
                ];

                $this->addCSVContent(array_values($data));
            }
        }
    }

    /**
     * Check if gender string is valid.
     * 
     * @param  string $gender
     * @return string
     */
    private function getStandardGender(string $gender = ''):string
    {
        $standardGender = '';

        $genderList = array(
            'male',
            'female',
            'unisex'
        );

        if (strlen($gender) > 0)
        {
            $gender = trim(strtolower($gender));

            if (array_key_exists($gender, $genderList))
            {
                $standardGender = $genderList[$gender];
            }
        }
        return $standardGender;
    }

    /**
     * Get the image by corespondent number.
     *
     * @param array    $variation
     * @param KeyValue $settings
     * @param int $number
     * @return string
     */
    private function getImageByNumber($variation, KeyValue $settings, int $number):string
    {
        $imageList = $this->elasticExportCoreHelper->getImageList($variation, $settings);

        if(count($imageList) > 0 && array_key_exists($number, $imageList))
        {
            return $imageList[$number];
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
     * @param  array    $variation
     * @param  KeyValue $settings
     * @param  string   $property
     * @return string
     */
    private function getProperty($variation, KeyValue $settings, string $property):string
    {
        if (count($this->itemPropertyCache[$variation['id']]) == 0)
        {
            $this->itemPropertyCache[$variation['id']] =  array_merge($this->getVariationAttributes($variation, $settings), $this->getItemPropertyList($variation, $settings));
        }

        if(array_key_exists($property, $this->itemPropertyCache[$variation['id']]))
        {
            return $this->itemPropertyCache[$variation['id']][$property];
        }

        return '';
    }


    /**
     * Get item properties.
     *
     * @param  array $variation
     * @param  KeyValue $settings
     * @return array<string,string>
     */
    protected function getItemPropertyList($variation, KeyValue $settings):array
    {
        $list = [];

        $characterMarketComponentList = $this->elasticExportCoreHelper->getItemCharactersByComponent($this->idlVariations[$variation['id']], self::TRACDELIGHT_COM);

        if(count($characterMarketComponentList))
        {
            foreach($characterMarketComponentList as $data)
            {
                if( (string) $data['characterValueType'] != 'file' &&
                    (string) $data['characterValueType'] != 'empty' &&
                    (string) $data['externalComponent']  != "0")
                {
                    if((string) $data['characterValueType'] == 'selection')
                    {
                        $propertySelection = $this->propertySelectionRepository->findOne((int) $data['characterValue'], $settings->get('lang'));
                        if($propertySelection instanceof PropertySelection)
                        {
                            $list[(string) $data['externalComponent']] = (string) $propertySelection->name;
                        }
                    }
                    else
                    {
                        $list[(string) $data['externalComponent']] = (string) $data['characterValue'];
                    }
                }
            }
        }

        return $list;
    }

    /**
     * Creates an array with the rest of data needed from the ItemDataLayer.
     *
     * @param RecordList $idlResultList
     */
    private function createIdlArray($idlResultList)
    {
        if($idlResultList instanceof RecordList)
        {
            foreach($idlResultList as $idlVariation)
            {
                if($idlVariation instanceof Record)
                {
                    $this->idlVariations[$idlVariation->variationBase->id] = [
                        'itemBase.id' => $idlVariation->itemBase->id,
                        'variationBase.id' => $idlVariation->variationBase->id,
                        'itemPropertyList' => $idlVariation->itemPropertyList,
                        'variationRetailPrice.price' => $idlVariation->variationRetailPrice->price,
                        'variationRetailPrice.currency' => $idlVariation->variationRetailPrice->currency,
                        'variationRecommendedRetailPrice.price' => $idlVariation->variationRecommendedRetailPrice->price,
                    ];
                }
            }
        }
    }
}
