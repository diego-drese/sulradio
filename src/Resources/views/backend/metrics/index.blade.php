@extends('Admin::layouts.backend.main')
@section('title', 'Metricas')
@section('content')

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="mdi mdi-radio font-20 text-success"></i>
                            <p class="font-16 m-b-5">Emissoras</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{$totalBroadCast}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="fas fa-balance-scale font-20 text-danger"></i>
                            <p class="font-16 m-b-5">Atos</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{$totalAtos}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="mdi mdi-ticket-account font-20 text-info"></i>
                            <p class="font-16 m-b-5">Clientes</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{$totalClients}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <i class="mdi mdi-file-document font-20 text-purple"></i>
                            <p class="font-16 m-b-5">Documentos</p>
                        </div>
                        <div class="col-5">
                            <h1 class="font-light text-right mb-0">{{$totalDocuments}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center m-b-10">
                        <h4 class="card-title">Período</h4>
                        <div class="ml-auto">
                            <div class="btn-group">
                                <input id="date" type="text" name="dates" class="form-control shawCalRanges"
                                       autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Novos Documentos</h4>
                    <div id="document_new" class="">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Ações em Documentos</h4>
                    <div id="document_action" class="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style_head')
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/datatables.css')}}">
    <link rel="stylesheet" href="{{mix('/vendor/oka6/admin/css/daterangepicker.css')}}">
@endsection
@section('script_footer_end')
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/datatables.js')}}></script>
    <script type="text/javascript" src={{mix('/vendor/oka6/admin/js/daterangepicker.js')}}></script>
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var urlBroadcast = '{{route('metrics.broadcast')}}';
            var urlDocumentNew = '{{route('metrics.document.new')}}';
            var urlDocumentAction = '{{route('metrics.document.action')}}';
            function buildPieChart(element, data, fields ){
                am4core.useTheme(am4themes_animated);
                var chart = am4core.create(element, am4charts.PieChart);
                chart.data = data;
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = fields.total;
                pieSeries.dataFields.category = fields.name;
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity = 1;
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;
            }

            function ajaxData(url, filter, callback, element){
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: filter,
                    dataType: 'json',
                    success: function (json) {
                        if(json.result==true){
                            callback(element, json.data, {total:'total', name:'name'});
                        }
                    },
                    error: function(json){
                         toastr.error(json.responseJSON.message, "Error");
                    }
                });
            }

        var intVal = function (i) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                    i : 0;
        };
        Number.prototype.formatMoney = function(c, d, t){
            var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        };
        Number.prototype.formatInteger = function(sep) {
            var nStr = this + '';
            sep = (sep ? sep : ".");

            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + sep + '$2');
            }
            return x1 + x2;
        };
        var daterangepicker = $('#date').daterangepicker({
            startDate: moment().subtract(30, 'days'),
            endDate:  moment(),
            locale: {
                "format": "dd/mm/yy"
            },
            alwaysShowCalendars: false,
            ranges: {
                'Últimos 03 Dias': [moment().subtract(3, 'days'), moment()],
                'Últimos 05 Dias': [moment().subtract(5, 'days'), moment()],
                'Últimos 07 Dias': [moment().subtract(7, 'days'), moment()],
                'Últimos 15 Dias': [moment().subtract(15, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(30, 'days'), moment()],
                'Últimos 60 Dias': [moment().subtract(60, 'days'), moment()],
                'Últimos 90 Dias': [moment().subtract(90, 'days'), moment()],
                'Últimos 180 Dias': [moment().subtract(90, 'days'), moment()],
                'Próximos 30 Dias': [moment().add(30, 'days'), moment()],
                'Próximos 60 Dias': [moment().add(60, 'days'), moment()],
                'Próximos 90 Dias': [moment().add(90, 'days'), moment()],
                'Próximos 180 Dias': [moment().add(180, 'days'), moment()],
            },
            locale:{
                dateFormat: 'dd/mm/yy',
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                nextText: 'Proximo',
                prevText: 'Anterior',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                weekLabel: 'Sem',
                customRangeLabel: 'Período de',
            }
        }, function(start, end) {
        });

        $('#date').change(function () {
            var dateFilter = this.value;
            ajaxData(urlDocumentNew, {date:dateFilter}, buildPieChart, 'document_new');
            ajaxData(urlDocumentAction, {date:dateFilter}, buildPieChart, 'document_action');
        });
        $('#date').trigger('change')
    });


    </script>

@endsection