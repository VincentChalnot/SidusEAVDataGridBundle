<?php

namespace Sidus\EAVDataGridBundle\Model;

use Sidus\DataGridBundle\Model\DataGrid as BaseDataGrid;
use Sidus\EAVFilterBundle\Configuration\EAVFilterConfigurationHandler;
use Sidus\EAVModelBundle\Model\FamilyInterface;
use Sidus\EAVModelBundle\Translator\TranslatableTrait;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Extended datagrid configuration for EAV entities
 */
class DataGrid extends BaseDataGrid
{
    use TranslatableTrait;

    /** @var FamilyInterface */
    protected $family;

    /**
     * DataGrid constructor.
     *
     * @param string              $code
     * @param array               $configuration
     * @param TranslatorInterface $translator
     *
     * @throws \Exception
     */
    public function __construct($code, array $configuration, TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
        parent::__construct($code, $configuration);
    }

    /**
     * @return FamilyInterface
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param FamilyInterface $family
     *
     * @return DataGrid
     */
    public function setFamily($family)
    {
        $this->family = $family;
        $filterConfig = $this->getFilterConfig();
        if ($filterConfig instanceof EAVFilterConfigurationHandler) {
            $filterConfig->setFamily($family);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param array  $columnConfiguration
     *
     * @throws \Exception
     */
    protected function createColumn($key, array $columnConfiguration)
    {
        if ($this->translator) {
            $columnConfiguration['translator'] = $this->translator;
        }
        $this->columns[] = new Column($key, $this, $columnConfiguration);
    }
}
