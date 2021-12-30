<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\RefurbDocType;
use Prettus\Repository\Eloquent\BaseRepository;

class RefurbDocTypeRepository extends BaseRepository
{

    public function model()
    {
        return RefurbDocType::class;
    }

    public function listDocumentTypes($type) {
        return $this->model->where('refurb_type', $type)->where('is_active',1)->orderBy('order', 'asc')->get();
    }
}
