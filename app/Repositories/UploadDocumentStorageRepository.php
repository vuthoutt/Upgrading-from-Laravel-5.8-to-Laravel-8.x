<?php
namespace App\Repositories;
use App\Models\UploadDocumentStorage;
use Prettus\Repository\Eloquent\BaseRepository;

class UploadDocumentStorageRepository extends BaseRepository {


    function model()
    {
        return UploadDocumentStorage::class;
    }
}
