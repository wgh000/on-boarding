<?php

namespace wgh000\onBoarding;

/**
 *  On Boarding validator
 *
 *  Use this class in order to validate if, given country of residence, mobile country and passport issuing country,
 *  user can freely register to the application or need more controls.
 *
 * @author Simone Cabrino
 */
class OnBoarding
{

    protected $name;
    protected $phoneCountry;
    protected $phoneInEurope;
    protected $phoneInHighRisk;
    protected $documentCountry;
    protected $documentInEurope;
    protected $documentInHighRisk;
    protected $addressCountry;
    protected $addressInEurope;
    protected $addressInHighRisk;

    private $europe = ['AUT', 'BEL', 'BGR', 'HRV', 'CYP', 'CZE', 'DNK', 'EST', 'FIN', 'FRA', 'DEU', 'GRC', 'HUN',
        'ISL', 'IRL', 'ITA', 'LVA', 'LIE', 'LTU', 'LUX', 'MLT', 'MCO', 'NLD', 'NOR', 'POL', 'PRT', 'ROU', 'SVK',
        'SVN', 'ESP', 'SWE', 'CHE', 'GBR'];

    private $highRisk = ['AFG', 'DZA', 'AIA', 'BHR', 'BGD', 'BMU', 'VGB', 'CYM', 'EGY', 'ETH', 'GIB', 'GGY', 'HKG',
        'IDN', 'IRN', 'IRQ', 'JOR', 'KWT', 'LBN', 'LBY', 'MAC', 'MYS', 'MLI', 'MRT', 'MSR', 'MAR', 'NGA', 'OMN',
        'PAK', 'PSE', 'QAT', 'SAU', 'SRB', 'LKA', 'SDN', 'SYR', 'MHL', 'TTO', 'TUN', 'TUR', 'TCA', 'ARE', 'VUT', 'YEM'];

    /**
     * Return the result of the analysis
     *
     * @return bool
     */
    public function canProceed(): bool
    {
        if (!$this->isAllCompleted()) {
            throw new \RuntimeException('Please fill all the necessary data');
        }

        if ($this->allInEurope()) {
            return true;
        }

        if ($this->allSameCountry() && $this->isRussia()) {
            if ($this->nameFound()) {
                return false;
            }
            return true;
        }

        if ($this->allSameCountry() && !$this->someInHighRisk()) {
            return true;
        }

        return false;
    }

    public function isAllCompleted(): bool
    {
        if (empty($this->phoneCountry)) {
            return false;
        }

        if (empty($this->documentCountry)) {
            return false;
        }

        if (empty($this->addressCountry)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function allInEurope(): bool
    {
        return $this->phoneInEurope && $this->documentInEurope && $this->addressInEurope;
    }

    /**
     * @return bool
     */
    protected function allSameCountry(): bool
    {
        return $this->phoneCountry === $this->addressCountry && $this->addressCountry === $this->documentCountry;
    }

    /**
     * @return bool
     */
    protected function isRussia(): bool
    {
        return $this->phoneCountry === 'RUS' || $this->addressCountry === 'RUS' || $this->documentCountry === 'RUS';
    }

    /**
     * @return bool
     */
    protected function nameFound(): bool
    {
        $f = new \FileBinarySearch\FileBinarySearch(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'names.txt'), 'strcmp');
        return $f->search($this->name);
    }

    /**
     * @return bool
     */
    protected function someInHighRisk(): bool
    {
        return $this->phoneInHighRisk || $this->documentInHighRisk || $this->addressInHighRisk;
    }

    /**
     * @param mixed $name
     * @return OnBoarding
     */
    public function setName($name): OnBoarding
    {
        $this->name = strtoupper(trim($name));
        return $this;
    }

    /**
     * @param mixed $country
     * @return OnBoarding
     */
    public function setPhoneCountry($country): OnBoarding
    {
        $this->phoneCountry = $country;

        $this->phoneInEurope = $this->inEurope($country);

        $this->phoneInHighRisk = $this->inRiskCountry($country);

        return $this;
    }

    protected function inEurope($country): bool
    {
        return \in_array($country, $this->europe, true);
    }

    protected function inRiskCountry($country): bool
    {
        return \in_array($country, $this->highRisk, true);
    }

    /**
     * @param mixed $country
     * @return OnBoarding
     */
    public function setDocumentCountry($country): OnBoarding
    {
        $this->documentCountry = $country;

        $this->documentInEurope = $this->inEurope($country);

        $this->documentInHighRisk = $this->inRiskCountry($country);

        return $this;
    }

    /**
     * @param mixed $country
     * @return OnBoarding
     */
    public function setAddressCountry($country): OnBoarding
    {
        $this->addressCountry = $country;

        $this->addressInEurope = $this->inEurope($country);

        $this->addressInHighRisk = $this->inRiskCountry($country);

        return $this;
    }

    /**
     * @return bool
     */
    protected function allInHighRisk(): bool
    {
        return $this->phoneInHighRisk && $this->documentInHighRisk && $this->addressInHighRisk;
    }

}