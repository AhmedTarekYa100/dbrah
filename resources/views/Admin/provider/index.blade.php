@extends('Admin/layouts/master')
@section('title')
    {{($setting->title) ?? ''}} | مقدمي الخدمة
@endsection
@section('page_name')
   قائمة مقدمي الخدمات
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">مقدمي الخدمة المسجلين في التطبيق</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-125px">مقدم الخدمة</th>
                                <th class="min-w-50px">الهاتف</th>
                                <th class="min-w-50px">الايميل</th>
                                <th class="min-w-50px">العنوان</th>
                                <th class="min-w-50px">الرقم الضريبي</th>
                                <th class="min-w-50px">البيانات</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متأكد من حذف بيانات <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">تراجع</button>
                        <button type="button" class="btn btn-danger" id="delete_btn">احذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->
    </div>
    @include('Admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'image', name: 'image'},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'town_id', name: 'town_id'},
            {data: 'vat_number', name: 'vat_number'},
            {data: 'commercial_photos', name: 'commercial_photos'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route('provider.index')}}',columns);
        deleteScript('{{route('delete_provider')}}');
    </script>
@endsection
