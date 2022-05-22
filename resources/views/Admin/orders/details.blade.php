<div class="table-responsive">
    <!--begin::Table-->
    <table class="table table-striped table-bordered w-100">
        <thead>
        <tr class="fw-bolder text-muted bg-light">
            <th class="min-w-25px">المنتج</th>
            <th class="min-w-20px">التصنيف</th>
            <th class="min-w-20px">التصنيف الفرعي</th>
            <th class="min-w-20px">الكمية</th>
        </tr>
        </thead>
        <tbody>
        @foreach($details as $detail)
        <tr>
            <td>{{$detail->product->title_ar}}</td>
            <td>{{$detail->mainCategory->title_ar}}</td>
            <td>{{$detail->subCategory->title_ar}}</td>
            <td>{{$detail->qty}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
</div>
