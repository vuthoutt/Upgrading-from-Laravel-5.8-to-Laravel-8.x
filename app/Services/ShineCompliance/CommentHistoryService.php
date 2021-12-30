<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\AreaRepository;

class CommentHistoryService{

    private $areaRepository;

    public function __construct(AreaRepository $areaRepository){

        $this->areaRepository = $areaRepository;
    }

    public function findDecommissionComment($comment_id){
        return $this->areaRepository->findDecommissionComment($comment_id);
    }
}
