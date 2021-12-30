@if(isset($data))
    <div class="row">
        <div class="col-12 mb-1">
            @include('shineCompliance.tables.property_documents', [
                'title' => $category->name,
                'tableId' => 'property-compliance-category-documents'.$category->id,
                'collapsed' => true,
                'plus_link' => true,
                'modal_id' => 'property-document-add',
                'data' => $category->documents ?? [],
                'order_table' => "[]"
                ])
        </div>
    </div>
@endif
