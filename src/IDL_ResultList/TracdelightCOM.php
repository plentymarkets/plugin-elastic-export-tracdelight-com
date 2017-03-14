<?php

namespace ElasticExportTracdelightCOM\IDL_ResultList;

use Plenty\Modules\Item\DataLayer\Contracts\ItemDataLayerRepositoryContract;
use Plenty\Modules\Helper\Models\KeyValue;
use Plenty\Modules\Item\DataLayer\Models\RecordList;


/**
 * Class TracdelightCOM
 * @package ElasticExportTracdelightCOM\IDL_ResultList
 */
class TracdelightCOM
{
    const TRACDELIGHT_COM = 130.00;

    /**
     * Creates and retrieves the extra needed data from ItemDataLayer.
     *
     * @param array $variationIds
     * @param KeyValue $settings
     * @param array $filter
     * @return RecordList|string
     */
    public function getResultList($variationIds, $settings, array $filter = [])
    {
        if(is_array($variationIds) && count($variationIds) > 0)
        {
            $searchFilter = array(
                'variationBase.hasId' => array(
                    'id' => $variationIds
                )
            );

            if(array_key_exists('variationStock.netPositive' ,$filter))
            {
                $searchFilter['variationStock.netPositive'] = $filter['variationStock.netPositive'];
            }
            elseif(array_key_exists('variationStock.isSalable' ,$filter))
            {
                $searchFilter['variationStock.isSalable'] = $filter['variationStock.isSalable'];
            }

            $resultFields = array(
                'itemBase' => array(
                    'id',
                ),

                'variationBase' => array(
                    'id',
                ),

                'itemPropertyList' => array(
                    'params' => array(),
                    'fields' => array(
                        'itemPropertyId',
                        'propertyId',
                        'propertyValue',
                        'propertyValueType',
                    )
                ),

                'variationRetailPrice' => array(
                    'params' => array(
                        'referrerId' => $settings->get('referrerId') ? $settings->get('referrerId') : self::TRACDELIGHT_COM,
                    ),
                    'fields' => array(
                        'price',        // price
                        'currency',
                    ),
                ),

                'variationRecommendedRetailPrice' => array(
                    'params' => array(
                        'referrerId' => $settings->get('referrerId') ? $settings->get('referrerId') : self::TRACDELIGHT_COM,
                    ),
                    'fields' => array(
                        'price',        // uvp
                    ),
                ),
            );

            $itemDataLayer = pluginApp(ItemDataLayerRepositoryContract::class);
            /**
             * @var ItemDataLayerRepositoryContract $itemDataLayer
             */
            $itemDataLayer = $itemDataLayer->search($resultFields, $searchFilter);

            return $itemDataLayer;
        }

        return '';
    }
}