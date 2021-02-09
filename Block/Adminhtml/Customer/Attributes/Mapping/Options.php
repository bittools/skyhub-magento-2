<?php
/**
 * BitTools Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BitTools
 * @package   BitTools_SkyHub
 *
 * @copyright Copyright (c) 2018 B2W Digital - BitTools Platform.
 *
 * Access https://ajuda.skyhub.com.br/hc/pt-br/requests/new for questions and other requests.
 */

namespace BitTools\SkyHub\Block\Adminhtml\Customer\Attributes\Mapping;

class Options extends \Magento\Backend\Block\Template
{
    public function toHtml()
    {
        $url = $this->_urlBuilder->getUrl('*/*/optionsrenderer');
        $mappingAttributeId = $this->getRequest()->getParam('id');

        return '<script type="text/x-magento-init">
        {
            "*": {
                "BitTools_SkyHub/js/customeroptionsreloader": {
                    "AjaxUrl": "' . $url . '",
                    "mappingAttributeId":"' . $mappingAttributeId . '"
                }
            }
        }
</script>';
    }
}
