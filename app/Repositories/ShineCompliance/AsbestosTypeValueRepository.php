<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\DropdownItemValue\AsbestosTypeValue;
use Prettus\Repository\Eloquent\BaseRepository;

class AsbestosTypeValueRepository extends BaseRepository
{

    public function model()
    {
        return AsbestosTypeValue::class;
    }

}
