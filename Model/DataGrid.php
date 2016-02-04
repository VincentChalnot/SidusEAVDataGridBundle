<?php

namespace Sidus\EAVDataGridBundle\Model;

use Sidus\DataGridBundle\Model\DataGrid as BaseDataGrid;
use Sidus\EAVFilterBundle\Configuration\FilterConfigurationHandler;
use Sidus\EAVModelBundle\Model\FamilyInterface;

class DataGrid extends BaseDataGrid
{
    /** @var FamilyInterface */
    protected $family;

    /**
     * @return FamilyInterface
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param FamilyInterface $family
     * @return DataGrid
     */
    public function setFamily($family)
    {
        $this->family = $family;
        $filterConfig = $this->getFilterConfig();
        if ($filterConfig instanceof FilterConfigurationHandler) {
            $filterConfig->setFamily($family);
        }
        return $this;
    }
}