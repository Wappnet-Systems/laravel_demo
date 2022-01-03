@extends('front.layouts.app_dashboard')

@section('title', env("APP_NAME")."- Notification ")

@push('css')
    <link href="{{ asset('admin_asset/assets/plugins/bower_components/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="container notification-container">
        <!-- Start section -->
        <div class="box-space bg-white mb-4">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 mb-0">
                    <h5 class="font-bold text-capitalize text-center txt-gold">Notification</h5>
                </div>
            </div>
        </div>
        <div class="box-space bg-white mb-4">
            @if (count($list))
                <div class="col-sm-12 text-right clear_all_btn">
                    <button type="button" onclick="remove_notification('all')" class="btn btn-gold ">Clear All</button>
                </div>
            @endif
            @if (count($list))
                @foreach ($list as $key => $value)
                    <div class="dropdown-item-box mt-3 d-flex-cut-center">
                        <div class="dropdown-item d-flex align-items-center position-relative pl-0 pr-0 dropdown_item_lenght notification_div_{{ $value['id'] }}" href="javascript:void(0)">
                            <div class="mr-3 pl-2">
                                <div class="icon-circle bg-gold">
                                    <i class="fa fa-info text-white"></i>
                                </div>
                            </div>
                            <div class="note-message">
                                <div class="small text-gray-500">{{ date('F d,Y  h:i A', strtotime($value['created_at'])) }}</div>
                                {!! $value['messages'] !!}
                            </div>
                            @if (isset($value['user_friend_request']) && !empty($value['user_friend_request']))
                                @if($value['user_friend_request']['status'] == 'Pending')
                                    <div class="user-follow-request">
                                        <button class="btn btn-sm btn-gold text-right" onclick="requRespFuntion(this, 'Accepted')" data-id="{{ $value['user_friend_request']['id'] }}" data-note-id="{{ $value['id'] }}">Accept Request</button>
                                        <button class="btn btn-sm btn-gray text-right" onclick="requRespFuntion(this, 'Rejected')" data-id="{{ $value['user_friend_request']['id'] }}" data-note-id="{{ $value['id'] }}">Reject Request</button>
                                    </div>
                                    <div class="remove-notification d-none" id="remove-note-{{ $value['id'] }}" style="position:absolute; right:5px;">
                                        <img class="img-fluid" src="{{ asset('front_asset/dashboard/assets/images/timeline/close-icon-16x16.svg') }}" alt="cp" onclick="remove_notification('{{ $value['id'] }}')">
                                    </div>
                                @else
                                    <div class="remove-notification" id="remove-note-{{ $value['id'] }}" style="position:absolute; right:5px;">
                                        <img class="img-fluid" src="{{ asset('front_asset/dashboard/assets/images/timeline/close-icon-16x16.svg') }}" alt="cp" onclick="remove_notification('{{ $value['id'] }}')">
                                    </div>
                                @endif
                            @else
                                <div class="remove-notification" id="remove-note-{{ $value['id'] }}" style="position:absolute; right:5px;">
                                    <img class="img-fluid" src="{{ asset('front_asset/dashboard/assets/images/timeline/close-icon-16x16.svg') }}" alt="cp" onclick="remove_notification('{{ $value['id'] }}')">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class='alert alert-info'>Empty Notification</p>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('front_asset/dashboard/assets/js/custom-dashboar-script.js') }}" charset="utf-8" async defer></script>
    <script src="{{ asset('admin_asset/assets/plugins/bower_components/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
        });

        function requRespFuntion(e, type)
        {
            var noteId = $(e).attr('data-note-id');

            $.ajax({
                url: "{{ route('front.notification.follower.status.change') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                dataType : "JSON",
                data : {
                    id : $(e).attr('data-id'),
                    status : type,
                },
                beforeSend: function() {
                    $("#preloader-active").css('display', 'block');
                },
                success: function(resp) {
                    if (resp.code == 200) {
                        $.toast({
                            heading: 'Success',
                            text: resp.message,
                            showHideTransition: 'slide',
                            position: 'top-right',
                            icon: 'success'
                        });

                        $(e).parents('.user-follow-request').remove();

                        $('#remove-note-' + noteId).removeClass('d-none');
                    }
                },
                complete: function(resp) {
                    if (resp.responseJSON.code == 400) {
                        $.toast({
                            heading: 'Error',
                            text: resp.responseJSON.message,
                            showHideTransition: 'fade',
                            position: 'top-right',
                            icon: 'error'
                        });
                    };

                    $("#preloader-active").css('display', 'none');
                }
            });
        }

        function remove_notification(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('front.remove_notification') }}",
                data : {
                    "_token" : "{{ csrf_token() }}",
                    notification_id : id,
                },
                dataType : "JSON",
                success: function(data) {
                    if (data.status === true) {
                        if (id == "all") {
                            location.reload();
                        } else {
                            $(".notification_div_"+id).remove();
                        }

                        if ($(".dropdown_item_lenght").length == 0) {
                            $(".clear_all_btn").remove();
                        }
                    } else {
                        $.toast({
                            heading: 'Error',
                            text: data.messages,
                            position: 'top-right',
                            loaderBg:'#ff6849',
                            icon: 'error',
                            hideAfter: 5000
                        });
                    }
                }
            });
        }
    </script>
@endsection
