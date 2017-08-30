<?php

namespace ElasticExportTracdelightCOM;

use Plenty\Modules\DataExchange\Services\ExportPresetContainer;
use Plenty\Plugin\DataExchangeServiceProvider;

/**
 * Class ElasticExportTracdelightCOMServiceProvider
 * @package ElasticExportTracdelightCOM
 */
class ElasticExportTracdelightCOMServiceProvider extends DataExchangeServiceProvider
{
    /**
     * Function for registering the service provider.
     */
    public function register()
    {

    }

    /**
     * Adds the export format to the export container.
     *
     * @param ExportPresetContainer $container
     */
    public function exports(ExportPresetContainer $container)
    {
        $container->add(
            'TracdelightCOM-Plugin',
            'ElasticExportTracdelightCOM\ResultField\TracdelightCOM',
            'ElasticExportTracdelightCOM\Generator\TracdelightCOM',
            '',
            true,
			true
        );
    }
}