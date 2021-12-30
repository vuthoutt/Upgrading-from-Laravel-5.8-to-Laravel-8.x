<div class="row">
    @include('shineCompliance.tables.item_summary', [
        'title' => $title,
        'tableId' => $table_id,
        'collapsed' => false,
        'row_col' => 'pr-0',
        'plus_link' => false,
        'data' => $items_summary_table,
        'pagination_type' => $pagination_type,
        'summary' => true,
        'header' => ['Summary','Area/floor Reference','Room/location Reference','Product/debris type', 'MAS',''],
        'order_table' => 'mas-risk-child'
        ])
</div>
