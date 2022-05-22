@extends('Admin/layouts/master')
@section('title')
    {{($setting->title) ?? ''}} | بيانات المقدم
@endsection
@section('page_name') صور البيانات @endsection
@section('content')
    <div class="row">
        <div class="demo-gallery card">
            <div class="card-header">
                <h3 class="card-title">بيانات شخصية للمقدم {{$provider->name}}</h3>
                <div class="">
{{--                    <button class="btn btn-secondary btn-icon text-white addBtn" data-toggle="modal" data-target="#add_modal">--}}
{{--									<span>--}}
{{--										<i class="fe fe-plus"></i>--}}
{{--									</span> اضافة جديد--}}
{{--                    </button>--}}
                </div>
            </div>
            <div class="card-body">
                <ul id="lightgallery" class="list-unstyled row" lg-uid="lg0">
                    @foreach($images as $image)
                        <li class="col-xs-6 col-sm-4 col-md-4 col-xl-3 mb-5 border-bottom-0" id="image{{$image->id}}"
                            data-responsive="{{$image->image}}" data-src="{{$image->image}}"
                            data-sub-html="<h4></h4><p></p>"
                            lg-event-uid="&amp;1">
                            <a href="#">
                                <img class="img-responsive" src="{{$image->image}}" alt="صورة" style="height: 140px">
                            </a>
                            <button class='btn btn-danger mt-1 deleteBtn'  data-id="{{$image->id}}" style="width: inherit" href = ''>حــذف  <i class='fa fa-trash'></i> </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script>
        $('.deleteBtn').click(function (){
            var id = $(this).attr('data-id'),
                routeOfDelete = "{{route('deleteCommercialImage')}}";
            $.ajax({
                type: 'POST',
                url: routeOfDelete,
                data: {
                    '_token': "{{csrf_token()}}",
                    'id': id,
                },
                success: function (data) {
                    if (data.status === 200) {
                        $('#image'+data.id).hide();
                        toastr.success(data.message)
                    } else {
                        toastr.error("حدث خطأ ما يرجي اعادة المحاولة")
                    }
                }
            });
        });
    </script>
@endsection


