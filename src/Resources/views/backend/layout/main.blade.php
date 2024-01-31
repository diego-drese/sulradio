@extends('Admin::layouts.backend.main')
@section('nav_bar_right')
	<?php
		$notifications=get_notification_ticket();
	?>
	@if($notifications)
	<div class="d-flex no-block align-items-center m-b-10">
		<div class="nav-item dropdown border-right mr-2" id="notifications">
			<a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="mdi mdi-bell-outline font-22"></i>
				<span class="badge badge-pill {{$notifications->total_unread>0 ? 'badge-info' : 'badge-success'}}" id="totalNotRead">{{$notifications->total_unread}}</span>
			</a>
			<div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                    <span class="with-arrow">
                                        <span class="bg-primary"></span>
                                    </span>
				<ul class="list-style-none">
					<li>
						<div class="drop-title bg-primary text-white">
							<h4 class="m-b-0 m-t-5" id="infoNotification">{{$notifications->total_unread>1 ? $notifications->total_unread.' Novas' : $notifications->total_unread.' Nova' }}</h4>
							<span class="font-light">Notificações</span>
						</div>
					</li>
					<li>
						<div class="message-center notifications ps-container ps-theme-default" data-ps-id="a1e071c9-8d97-69d6-1de3-39e36537aebe" style="min-width: 300px;">
						@foreach($notifications->items as $item)
							<!-- Message -->
								<a href="{{$item->ticket_id ? route('ticket.ticket',[$item->ticket_id]) : 'javascript:void(0)'}}" class="message-item">
									@if($item->type==\Oka6\SulRadio\Models\SystemLog::TYPE_COMMENT)
										<span class="btn btn-success btn-circle">
                                                                   <i class="fas fa-comment"></i>
                                                                </span>
									@elseif($item->type==\Oka6\SulRadio\Models\SystemLog::TYPE_NEW)
										<span class="btn btn-warning btn-circle">
                                                                   <i class="mdi mdi-ticket"></i>
                                                                </span>
									@elseif($item->type==\Oka6\SulRadio\Models\SystemLog::TYPE_UPDATE)
										<span class="btn btn-info btn-circle">
                                                                   <i class=" fas fa-ticket-alt"></i>
                                                                </span>
									@else
										<span class="btn btn-danger btn-circle">
                                                                   <i class="fa fa-link"></i>
                                                                </span>
									@endif

									<div class="mail-contnet">
										<h5 class="message-title">{{$item->subject}}</h5>
										<span class="mail-desc">{{$item->content}}</span>
										<span class="time">{{$item->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i')}}</span>
									</div>
								</a>
							@endforeach
							<div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
								<div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;">

								</div>
							</div>
							<div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px;">
								<div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;">

								</div>
							</div>
						</div>
					</li>
					<li>
						<a class="nav-link text-center m-b-5 text-dark" href="{{route('notifications.ticket')}}">
							<strong>Ver Todas</strong>
							<i class="fa fa-angle-right"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	@endif
@endsection
@section('script_footer_start')
	<script type="text/javascript">
		$("#notifications").on("show.bs.dropdown", function(event){
			var markRead = $('#totalNotRead').html();
			if(markRead==='0'){
				return true;
			}
			$.ajax({
				url: '{{ route('notification.ticket.mark.read') }}',
				type: "get",
				dataType: 'json',
				data: {},
				beforeSend: function () {

				},
				success: function (data) {
					$('#infoNotification').html('0 Nova')
					$('#totalNotRead').html('0').removeClass('badge-error').addClass('badge-success')
				},
				error: function (erro) {
					toastr.error(erro.responseJSON.message, 'Erro');
				}
			});

		});

		setInterval(function (){
			console.log('Novas');
		},30000)
	</script>
@endsection