<?php

namespace App\Entity\Interfaces;

use App\Entity\Tenant;

interface TenantScopedEntityInterface
{
    public function getTenant(): Tenant;

    public function setTenant(Tenant $tenant): self;
}
