@extends('SulRadio::backend.layout.main')

@section('title', 'Notificações WhatsApp')

@section('content')
    <div class="row">

        {{-- BLOCO AUTENTICAÇÃO WHATSAPP --}}
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body text-center">

                    <h5 class="mb-3">
                        <i class="fab fa-whatsapp text-success"></i>
                        Sessão WhatsApp
                    </h5>

                    {{-- LOADING --}}
                    <div id="whats-loading">
                        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                        <p class="mt-2">Verificando sessão…</p>
                    </div>

                    {{-- AUTENTICADO --}}
                    <div id="whats-authenticated" class="d-none">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                        <p class="mt-2"><b>WhatsApp autenticado</b></p>

                        <button id="btn-logout" class="btn btn-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Deslogar
                        </button>
                    </div>

                    {{-- QR CODE --}}
                    <div id="whats-qrcode" class="d-none">
                        <p>Escaneie o QR Code com o WhatsApp</p>
                        <img id="qr-image" src="" style="max-width:260px">
                    </div>

                </div>
            </div>
        </div>

        {{-- LISTAGEM --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center m-b-10">
                        <div class="ml-auto"></div>
                    </div>

                    <div class="table-responsive">
                        <table id="table" class="table table-striped table-bordered" role="grid"
                               aria-describedby="file_export_info">
                            <thead>
                            <tr>
                                <th style="width:80px">Ações</th>
                                <th>Usuário</th>
                                <th>Tipo</th>
                                <th>Destino</th>
                                <th>Código</th>
                                <th>Mensagem</th>
                                <th>Status</th>
                                <th>Enviado em</th>
                            </tr>
                            <tr>
                                <th><span class="btn btn-primary btn-xs" id="clearFilter"><span class="fas fa-sync-alt"></span> <b>Limpar</b></span></th>
                                <th>---</th>
                                <th><input class="fieldSearch form-control" placeholder="Buscar Tipo"></th>
                                <th><input class="fieldSearch form-control" placeholder="Buscar Destinatário"></th>
                                <th><input class="fieldSearch form-control" placeholder="Buscar Código"></th>
                                <th>---</th>
                                <th>---</th>
                                <th>---</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/select2.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/bootstrap-switch.css')}}">
    <style>
        .table td, .table th {
            padding: 0.5em;
        }
        #table_ticket_filter{
            display: none;
        }
    </style>
@endsection
@section('script_footer_end')
    <script src="{{ mix('/vendor/oka6/admin/js/datatables.js') }}"></script>
    <script>
        let qrInterval = null;
        let logoutInProgress = false;
        let countdownInterval = null;
        let countdownSeconds = 30;

        function loadWhatsappStatus() {
            if (logoutInProgress) return;

            $.get('{{ route('ticket.config.whatsapp.qr') }}', function (data) {
                $('#whats-loading').addClass('d-none');

                if (data.authenticated) {
                    $('#whats-qrcode').addClass('d-none');
                    $('#whats-authenticated').removeClass('d-none');
                    clearInterval(qrInterval);
                } else if (data.qr) {
                    $('#whats-qrcode').removeClass('d-none');
                    $('#qr-image').attr('src', data.qr);
                }
            }).fail(function () {
                $('#whats-loading').html('<p class="text-danger">Erro ao conectar com o WhatsApp</p>');
            });
        }

        function startCountdown(seconds) {
            countdownSeconds = seconds;

            $('#whats-loading')
                .removeClass('d-none')
                .html(
                    `<p class="text-warning">
                    Deslogando WhatsApp… aguarde
                    <strong><span id="countdown">${countdownSeconds}</span>s</strong>
                 </p>`
                );

            countdownInterval = setInterval(function () {
                countdownSeconds--;
                $('#countdown').text(countdownSeconds);

                if (countdownSeconds <= 0) {
                    clearInterval(countdownInterval);
                    location.reload();
                }
            }, 1000);
        }

        $('#btn-logout').on('click', function () {
            if (!confirm('Deseja deslogar o WhatsApp?')) return;

            logoutInProgress = true;
            clearInterval(qrInterval);

            $('#whats-authenticated').addClass('d-none');
            $('#whats-qrcode').addClass('d-none');

            $.get('{{ route('ticket.config.whatsapp.logout') }}', function () {
                startCountdown(30);
            }).fail(function () {
                $('#whats-loading').html('<p class="text-danger">Erro ao deslogar o WhatsApp</p>');
                logoutInProgress = false;
                qrInterval = setInterval(loadWhatsappStatus, 5000);
            });
        });

        loadWhatsappStatus();
        qrInterval = setInterval(loadWhatsappStatus, 5000);
    </script>



    <script>
        $(document).ready(function () {
            var table = $('#table').DataTable({
                language: {!! json_encode(__('datatable')) !!},
                serverSide: true,
                processing: true,
                autoWidth: false,
                orderCellsTop: true,
                stateSave: true,
                searching: true,
                ajax: '{{ route('ticket.config.whatsapp') }}',
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return '<a href="' + data.ticket_url + '" class="badge badge-secondary mr-1 " role="button" aria-pressed="true"><b>Ticket #'+data.ticket_id+'</b></a>';
                        }
                    },
                    { data: "link_user",
                        orderable: false,
                        render: function (data, type, row) {
                            return data;
                        } },
                    { data: "type" , orderable: true },
                    { data: "destination" , orderable: false },
                    { data: "code", orderable: false },
                    { data: "body", orderable: false },
                    { data: "status", orderable: false },
                    { data: "sent_at_formated", orderable: true, name:'sent_at'},

                ],
                order: [[7, 'desc']]

            });

            $('#table thead tr:eq(1) th').each(function (i) {
                $('.fieldSearch', this).on('keyup change', function () {
                    table.column(i).search(this.value).draw();
                });
            });

            $('#clearFilter').click(function () {
                table.state.clear();
                location.reload();
            });

        });
    </script>
@endsection
