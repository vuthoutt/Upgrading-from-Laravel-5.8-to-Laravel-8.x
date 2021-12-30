<?php


namespace App\Repositories\ShineCompliance;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\ShineDocumentStorage;


class ShineDocumentStorageRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    public function model()
    {
        return ShineDocumentStorage::class;
    }
    public function updateOrCreateStorage($condition_data, $data)
    {
        $this->model->updateOrCreate($condition_data,$data);
    }
}
