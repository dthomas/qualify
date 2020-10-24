<?php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class AccountFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->implementsInterface(AccountAwareInterface::class)) {
            return '';
        }

        return "{$targetTableAlias}.account_id = {$this->getParameter('account_id')}";
    }
}
