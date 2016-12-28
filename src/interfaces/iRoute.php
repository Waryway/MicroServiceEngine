<?php
namespace WarywayWebsiteTemplate\interfaces;

interface iRoute{
    /**
     * must return an array [['METHOD', 'route', 'handler'],['METHOD','route','handler']]
     * @return array
     */
    public function getRoute() : array;

}

?>