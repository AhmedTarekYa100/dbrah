@extends('Admin/layouts/master')
@section('title')
    {{($setting->title) ?? ''}} | الطلبات السابقة
@endsection
@section('page_name') الطلبات السابقة @endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">طلبات العملاء السابقة</h3>
{{--                    <div class="text-gray">طلبات حالية</div>--}}
                    <div class="">

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-20px">العميل</th>
                                <th class="min-w-20px">الهاتف</th>
                                <th class="min-w-20px">وقت الطلب</th>
                                <th class="min-w-20px">الحالة</th>
                                <th class="min-w-20px">التفاصيل</th>
                                <th class="min-w-20px">فاتورة</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit MODAL -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">تفاصيل الطلب</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Edit MODAL CLOSED -->

    </div>
    @include('Admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'user_id', name: 'user_id'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'details', name: 'details',orderable: false, searchable: false},
            {data: 'bill', name: 'bill',orderable: false, searchable: false},
        ]
        showData('{{route('endedOrders')}}', columns);

        // Show Details Modal
        $(document).on('click', '.detailsBtn', function () {
            var id = $(this).data('id')
            var url = "{{route('orderDetails',':id')}}";
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')

            setTimeout(function () {
                $('#modal-body').load(url)
            }, 500)
        })
    </script>
@endsection


