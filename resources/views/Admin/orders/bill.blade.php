@extends('Admin/layouts/master')
@section('title')
    {{($setting->title) ?? ''}} | فاتورة بيع
@endsection
@section('page_name') فاتورة بيع @endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-right"><h3 class="card-title mb-0"> فاتورة رقم {{$order->id}}#</h3></div>
                        <div class="float-left"><p class="mb-1"><span class="font-weight-bold">تاريخ الفاتورة : </span>
                                {{$order['day']}}</p>
                            <p class="mb-0"><span class="font-weight-bold">تاريخ الاستحقاق :</span> {{$offer['day']}}
                            </p></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 "><p class="h3">فاتورة من :</p>
                            {{--                            <iframe src = "https://maps.google.com/maps?q={{$order->provider->latitude}},{{$order->provider->longitude}}&hl=es;z=14&amp;output=embed"></iframe>--}}
                            <address> {{($order->provider->name) ?? ''}}<br>
                                {{($order->provider->nationality->title_ar) ?? ''}}
                                , {{($order->provider->town->title_ar) ?? ''}}<br>
                                <a class="text-dark"
                                   href="tel:{{($order->provider->phone_code.$order->provider->phone) ?? ''}}">{{($order->provider->phone_code.$order->provider->phone) ?? ''}}</a><br>
                                <a class="text-dark"
                                   href="mailto:{{($order->provider->email) ?? ''}}">{{($order->provider->email) ?? ''}}</a>
                            </address>
                        </div>
                        <div class="col-lg-6 text-left"><p class="h3">الي العميل :</p>
                            <address> {{($order->user->name) ?? ''}}<br>
                                {{($order->address->title) ?? ''}}<br>
                                <a class="text-dark"
                                   href="tel:{{($order->user->phone_code.$order->user->phone) ?? ''}}">{{($order->user->phone_code.$order->user->phone) ?? ''}}</a><br>
                                <a class="text-dark"
                                   href="mailto:{{($order->user->email) ?? ''}}">{{($order->user->email) ?? ''}}</a>
                            </address>
                        </div>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-hover mb-0 text-nowrap">
                            <tbody>
                            <tr class=" ">
                                <th class="text-center">م</th>
                                <th>المنتج</th>
                                <th class="text-center">الكمية</th>
                                <th class="text-right">سعر الصنف</th>
                                <th class="text-right">الاجمالي</th>
                            </tr>
                            @foreach($offer->offer_details as $detail)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td><p class="font-w600 mb-1">{{$detail->product->title_ar}}</p>
                                        <div class="text-muted">
                                            <div class="text-muted">
                                                @if($detail->type == 'other')
                                                    المنتج المطلوب غير موجود
                                                @endif
                                                @if($detail->other_product_id != null)
                                                    , تم عرض المنتج {{$detail->other->title_ar}} بدلا منه
                                                @endif
                                                @if($detail->type == 'less')
                                                    الكمية المطلوبة غير متوفرة المتاح حاليا {{$detail->available_qty}} صنف
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{$detail->qty}}</td>
                                    <td class="text-right number-font1">{{$detail->price}}</td>
                                    <td class="text-right number-font1">{{$detail->total_price}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td>{{$offer->offer_details->sum('price')}}</td>
                                <td>{{$offer->offer_details->sum('total_price')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-left">
                    {{--                    <button type="button" class="btn btn-primary mb-1" onclick="javascript:window.print();"><i--}}
                    {{--                            class="si si-wallet"></i> Pay Invoice--}}
                    {{--                    </button>--}}
                    {{--                    <button type="button" class="btn btn-success mb-1" onclick="javascript:window.print();"><i--}}
                    {{--                            class="si si-paper-plane"></i> Send Invoice--}}
                    {{--                    </button>--}}
                    <button type="button" class="btn btn-primary mb-1" onclick="javascript:window.print();">طباعة <i
                            class="fa fa-print"></i>
                    </button>
                </div>
            </div>
        </div><!-- COL-END --> </div>

@endsection
