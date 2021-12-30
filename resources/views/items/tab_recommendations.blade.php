<div class="offset-top40">
    @include('forms.form_text',['title' => 'Action/recommendation:', 'data' => optional($item->actionRecommendationView)->action_recommendation])
</div>
@push('javascript')


@endpush