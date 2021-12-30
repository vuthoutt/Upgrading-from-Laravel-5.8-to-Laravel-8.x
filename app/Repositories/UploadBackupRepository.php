<?php
namespace App\Repositories;
use App\Models\UploadBackup;
use Prettus\Repository\Eloquent\BaseRepository;

class UploadBackupRepository extends BaseRepository {

    function model()
    {
        return UploadBackup::class;
    }
}
