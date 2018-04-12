<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Config\Backend\Show;

/**
 *
 * Add config for input value config validations
 *
 *
 * NOTICE OF LICENSE
 *
 * @category   SystemCode
 * @package    Systemcode_BrazilCustomerAttributes
 * @author     Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright  System Code LTDA-ME
 * @license    http://opensource.org/licenses/osl-3.0.php
 */
class Customer extends \Magento\Customer\Model\Config\Backend\Show\Customer{
    /**
     * Actions after save
     *
     * @return $this
     */
    public function afterSave()
    {
        $result = parent::afterSave();

        $valueConfig = [
            '' => ['is_required' => 0, 'is_visible' => 0, 'is_unique' => 0],
            'opt' => ['is_required' => 0, 'is_visible' => 1, 'is_unique' => 0],
            '1' => ['is_required' => 0, 'is_visible' => 1, 'is_unique' => 0],
            'req' => ['is_required' => 0, 'is_visible' => 1, 'is_unique' => 0],
            'optuni' => ['is_required' => 0, 'is_visible' => 1, 'is_unique' => 1],
            'requni' => ['is_required' => 0, 'is_visible' => 1, 'is_unique' => 1]
        ];

        $value = $this->getValue();
        if (isset($valueConfig[$value])) {
            $data = $valueConfig[$value];
        } else {
            $data = $valueConfig[''];
        }

        if ($this->getScope() == 'websites') {
            $website = $this->storeManager->getWebsite($this->getScopeCode());
            $dataFieldPrefix = 'scope_';
        } else {
            $website = null;
            $dataFieldPrefix = '';
        }

        foreach ($this->_getAttributeObjects() as $attributeObject) {
            if ($website) {
                $attributeObject->setWebsite($website);
                $attributeObject->load($attributeObject->getId());
            }
            $attributeObject->setData($dataFieldPrefix . 'is_required', $data['is_required']);
            $attributeObject->setData($dataFieldPrefix . 'is_visible', $data['is_visible']);
            $attributeObject->setData($dataFieldPrefix . 'is_unique', $data['is_unique']);
            $attributeObject->save();
        }

        return $result;
    }
}