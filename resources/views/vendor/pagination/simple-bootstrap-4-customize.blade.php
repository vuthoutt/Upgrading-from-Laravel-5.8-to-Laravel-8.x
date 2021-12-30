@if(count($pagination))
    <ul class="pagination mt-2" role="navigation">
        @if($pagination['current_page'] == 0)
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">@lang('pagination.first')</span>
            </li>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">@lang('pagination.previous')</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $pagination['first_page_url'] }}" rel="prev">@lang('pagination.first')</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="{{ $pagination['prev_page_url'] }}" rel="prev">@lang('pagination.previous')</a>
            </li>
        @endif
            <input class="span1" style="width: 60px; text-align: center" id="" value="{{$pagination['current_page'] + 1 . "/" . $pagination['total']}}" type="text">
        @if($pagination['current_page'] == ($pagination['total'] -1))
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">@lang('pagination.next')</span>
            </li>
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link">@lang('pagination.last')</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $pagination['next_page_url'] }}" rel="next">@lang('pagination.next')</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="{{ $pagination['last_page_url'] }}" rel="next">@lang('pagination.last')</a>
            </li>
        @endif
    </ul>
@endif
