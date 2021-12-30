<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\PropertyCommentRepository;

class PropertyCommentService{

    public function __construct(PropertyCommentRepository $propertyCommentRepository){
        $this->propertyCommentRepository = $propertyCommentRepository;
    }

    public function getFindPropertyComment($id){
        return $this->propertyCommentRepository->getFindPropertyComment($id);
    }

}
