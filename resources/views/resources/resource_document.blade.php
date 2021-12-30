@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'resource_doc','data' => ''])

<div class="container prism-content">
    <h3>Resource Documents</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#resource"><strong>Resource</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#categories"><strong>Categories</strong></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div id="resource" class="container tab-pane active">
                <div class="row">
{{--                     @if(in_array(\Auth::user()->id, SMART_SHEET_USER))
                        <div class="col-md-12 mt-5">
                            <div class="table-header normal-table" id="e-learning-header" data-toggle="collapse" data-target="#colap-e-learning">
                                <h6 class="table-heading">{{ env('APP_DOMAIN') ?? 'GSK' }} Problem Management</h6>
                            </div>
                            <div class="panel-collapse collapse show" id="colap-e-learning">
                                <table id="e-learning-table-1" class="table table-striped table-bordered" >
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="https://app.smartsheet.com/sheets/F4rfhgq3PvvmP9xP3cRr2WJ8Mw4VQQXPhFM9r2R1?view=grid">shine Problem Management Executive Access View</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif --}}
                    @if(is_null($template_cat))
                    @else
                        @foreach($template_cat as $key => $category)
                            @include('tables.resource_document', [
                                'title' => $category->category,
                                'tableId' => 'property-historic-table'.$key,
                                'collapsed' => $loop->first ? false : true,
                                'plus_link' => true,
                                'modal_id' => 'resource-doc-add-'.$category->id,
                                'data' => $category->template,
                                'list_doc_permission' => $list_doc_permission,
                                'list_doc_update_permission' => $list_doc_update_permission
                                ])
                                @include('modals.resource_document_add',['color' => 'red', 'modal_id' => 'resource-doc-add-'.$category->id, 'url' => route('ajax.resource_document'), 'id' =>$category->id, 'unique' => \Str::random(5) ])
                        @endforeach
                    @endif
                </div>
            </div>
            <div id="categories" class="container tab-pane fade">
                <div class="row">
                     @include('tables.historical_categories', [
                    'title' => 'Categories Summary',
                    'tableId' => 'category-table',
                    'collapsed' => false,
                    'plus_link' => true,
                    'modal_id' => 'category-add',
                    'data' => $template_cat,
                    'edit_permission' => true
                    ])
                    @include('modals.historical_category',['color' => 'red', 'modal_id' => 'category-add', 'title' => 'Add Category', 'url' => route('ajax.resource_category'), 'type' => '-add'])
                    @include('modals.historical_category',['color' => 'red', 'modal_id' => 'historic-cat-edit', 'title' => 'Edit Category', 'url' => route('ajax.resource_category'), 'type' => '-edit'])
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
