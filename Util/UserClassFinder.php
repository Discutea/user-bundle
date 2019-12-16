<?php


namespace Discutea\UserBundle\Util;

use Discutea\UserBundle\Entity\DiscuteaUserInterface;
use Discutea\UserBundle\Entity\User as Model;

class UserClassFinder
{
    /**
     * @return |null
     * @throws \ReflectionException
     */
    public static function getClass()
    {
        $classes = get_declared_classes();

        foreach($classes as $class) {
            $reflect = new \ReflectionClass($class);
            if($reflect->implementsInterface(DiscuteaUserInterface::class) && get_parent_class($class) === Model::class) {
                return $class;
            }
        }

        return null;
    }
}
