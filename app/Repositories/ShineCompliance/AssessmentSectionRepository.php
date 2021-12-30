<?php

namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\AssessmentSection;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentSectionRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return AssessmentSection::class;
    }

    public function getQuestionnaire($assess_id, $assess_type){

        return $this->model->with(['children' => function ($query) {
            // $query->orderBy('order');
        },'children.questions' => function($query) {
            $query->orderBy('order')->orderBy('id');
        },'questions.answerType','children.questions.answer'=> function($query) use($assess_id) {
            $query->where('assess_id', $assess_id);
        },'resultScore'=> function($query) use($assess_id) {
            $query->where(['assess_id' => $assess_id]);
        }, 'questions.answer.answerType'])
        ->where(['type' => $assess_type, 'parent_id' => 0])->get();
    }

    public function getAssessmentResultByAssessId($assess_id)
    {
        return \DB::select('select group_sec.id main_sec, sec.id sub_sec, count(quest.id) as total_question,
            sum(if(val.answer_id = 1, 1, 0)) as total_yes, sum(if(val.answer_id = 2 or val.answer_id = 0, 1, 0)) as total_no,
            sum(quest.score) as total_score
            from cp_assessment_sections group_sec
            left join cp_assessment_sections sec on group_sec.id = sec.parent_id
            left join cp_assessment_questions quest on sec.id = quest.section_id
            left join cp_assessment_values val on quest.id = val.question_id
            where val.assess_id = ?
            group by main_sec, sub_sec with rollup', [$assess_id]);
    }

}
