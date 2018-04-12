<?php
namespace SystemCode\BrazilCustomerAttributes\Model\Config\Source;

/**
 *
 * Add options to select user group assign
 *
 * NOTICE OF LICENSE
 *
 * @category   SystemCode
 * @package    Systemcode_BrazilCustomerAttributes
 * @author     Eduardo Diogo Dias <contato@systemcode.com.br>
 * @copyright  System Code LTDA-ME
 * @license    http://opensource.org/licenses/osl-3.0.php
 */
class Customergroup implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $groups = $objectManager->get('\Magento\Customer\Model\ResourceModel\Group\Collection');
        $groupsArr = [];
        $groupsArr[] = ['value' => '', 'label' => 'Use Default Group'];

        foreach ($groups as $group) {
            if($group->getCode()!="NOT LOGGED IN"){
                $groupsArr[] = ['value' => $group->getId(), 'label' => $group->getCode()];
            }
        }

        return $groupsArr;
     }
}
