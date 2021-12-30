<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\LocationCommentRepository;

class LocationCommentService{

    public function __construct(LocationCommentRepository $locationCommentRepository){
        $this->locationCommentRepository = $locationCommentRepository;
    }

    public function getFindLocationComment($id){
        return $this->locationCommentRepository->getFindLocationComment($id);
    }

}
