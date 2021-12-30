<?php
namespace App\Repositories;
use App\Models\DownloadManifest;
use Prettus\Repository\Eloquent\BaseRepository;

class DownloadManifestRepository extends BaseRepository {

    function model()
    {
        return DownloadManifest::class;
    }
}
