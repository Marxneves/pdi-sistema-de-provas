<?php

namespace App\Packages\Base\Domain\Repository;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function find($id);
}
