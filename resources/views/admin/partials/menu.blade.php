@php
$contactUsController = new \App\Admin\Controllers\ContactUsController();
$contact_message_count = $contactUsController->unReadMessagesCount();
$feedbackController = new \App\Admin\Controllers\FeedbackController();
$feed_message_count = $feedbackController->unReadMessagesCount();
@endphp
@if(Admin::user()->visible($item['roles']))
    @if(!isset($item['children']))
        <li>
            @if(url()->isValidUrl($item['uri']))
                <a href="{{ $item['uri'] }}" target="_blank">
            @else
                <a href="{{ admin_base_path($item['uri']) }}">
            @endif
                <i class="fa {{$item['icon']}}">
                    @if($item['title'] == 'Messages' && $contact_message_count > 0)
                        @if($contact_message_count < 99)
                            <span class="badge contact-msg-menu-item">{{$contact_message_count}}</span>
                        @elseif($contact_message_count > 99)
                            <span class="badge contact-msg-menu-item">99+</span>
                        @endif
                    @endif
                    @if($item['title'] == 'Feedbacks' && $feed_message_count > 0)
                        @if($feed_message_count < 99)
                            <span class="badge feedback-msg-menu-item">{{$feed_message_count}}</span>
                        @elseif($feed_message_count > 99)
                            <span class="badge feedback-msg-menu-item">99+</span>
                        @endif
                    @endif
                </i>
                <span>{{$item['title']}}</span>
            </a>
        </li>
    @else
        <li class="treeview">
            <a href="#">
                <i class="fa {{$item['icon']}}"></i>
                <span>{{$item['title']}}</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                @foreach($item['children'] as $item)
                    @include('admin::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif