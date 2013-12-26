<?php

namespace PUGX\Godfather;

class ServiceNameConverter
{
    /**
     * A string to underscore.
     *  a namespace like
     *  \Vendor\ABC\UserName should be converted to
     *  vendor.abc.user_name
     *
     * @param string $serviceNameId The string to underscore
     *
     * @return string The underscored string
     */
    public function serviceNameConverter($serviceNameId)
    {
        $replacing = array('\\' => '.');

        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), strtr($serviceNameId, $replacing)));
    }

    /**
     * transform the contextName into a well formatted serviceId
     *   'MyService' => my_service
     *
     * @param $prefix
     * @param $NameSpaces array of name
     *
     * @return string
     */
    public function getServiceNamespace($prefix, $NameSpaces)
    {
        if (is_array($NameSpaces)) {
            $serviceId = '';
            foreach ($NameSpaces as $name) {
                $serviceId .= '.'.$this->serviceNameConverter($name);
            }
        } else {
            $serviceId = '.'.$this->serviceNameConverter($NameSpaces);
        }

        return sprintf("%s%s",  $prefix, $serviceId);
    }
}
