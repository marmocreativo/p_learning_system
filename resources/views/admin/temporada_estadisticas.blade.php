@extends('plantillas/plantilla_admin')

@section('titulo', 'Cuenta del sistema')

@section('contenido_principal')
    <h1>Estadísticas de la temporada <small>{{$temporada->nombre}}</small></h1>
    <div class="row">
        <div class="col-9">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ route('admin')}}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('temporadas', ['id_cuenta'=>$temporada->id_cuenta])}}">Temporadas</a></li>
                  <li class="breadcrumb-item">Temporada</li>
                </ol>
            </nav>
        </div>
        <div class="col-3">
            <a href="{{ route('temporadas', ['id_cuenta'=> $temporada->id_cuenta]) }}">Lista de temporadas</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div>
                <canvas id="myChart"></canvas>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Visualizaciones</th>
                        <th>Evaluaciones</th>
                        <th>Trivias</th>
                        <th>Jackpots</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resultados as $resultado)
                    <tr>
                        <td>{{$resultado['fecha']}}</td>
                        <td>{{$resultado['visualizaciones']}}</td>
                        <td>{{$resultado['respuestas_evaluaciones']}}</td>
                        <td>{{$resultado['respuestas_trivias']}}</td>
                        <td>{{$resultado['intentos_jackpot']}}</td>
                        @php
                            $total = $resultado['visualizaciones']+$resultado['respuestas_evaluaciones']+$resultado['respuestas_trivias']+$resultado['intentos_jackpot'];
                        @endphp
                        <td>{{$total}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <canvas id="myChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',  // Tipo de gráfico de líneas
            data: {
                labels: @json($fechas),  // Fechas de los días
                datasets: [{
                    label: 'Visualizaciones',
                    data: @json($visualizaciones),  // Datos de visualizaciones
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                },
                {
                    label: 'Evaluaciones',
                    data: @json($evaluaciones),  // Datos de evaluaciones
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                },
                {
                    label: 'Trivias',
                    data: @json($trivias),  // Datos de trivias
                    borderColor: 'rgb(153, 102, 255)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: false,
                },
                {
                    label: 'Jackpots',
                    data: @json($jackpots),  // Datos de jackpots
                    borderColor: 'rgb(255, 159, 64)',
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Estadísticas de la temporada'
                    },
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
