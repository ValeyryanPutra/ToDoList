@extends('layouts.apps')
@section('content')


    <div class="row">
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $userCount }}</h3>
                    <p>Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $completedTasks }}</h3>
                    <p>Complete Task</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $incompleteTasks }}</h3>
                    <p>Incomplete Task</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer"> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Donut chart -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="far fa-chart-bar"></i>
                                Donut Chart
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donut-chart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctxDonut = document.getElementById('donut-chart').getContext('2d');
        const donutChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Tugas Selesai', 'Tugas Belum Selesai'],
                datasets: [{
                    data: [{{ $completedTasks }}, {{ $incompleteTasks }}],
                    backgroundColor: ['#28a745', '#dc3545'], // Hijau untuk selesai, merah untuk belum
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>

    {{-- <script>
        const ctx = document.getElementById('task_charts').getContext('2d');

        // Data dari controller
        const completedTasks = @json(array_values($dailyCompletedTasks));

        // Label Hari dalam Bahasa Indonesia
        const taskDates = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        let chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: taskDates,
                datasets: [{
                    label: 'Tugas Selesai',
                    data: completedTasks,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Warna biru muda
                    borderRadius: 5, // Bar sedikit melengkung
                    barThickness: 20 // Lebar bar
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#6c757d', // Warna abu-abu untuk angka
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(200, 200, 200, 0.2)' // Garis grid tipis
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6c757d', // Warna abu-abu untuk label hari
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false // Hilangkan garis vertikal di sumbu X
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan label di atas chart
                    },
                    tooltip: {
                        backgroundColor: '#343a40', // Warna tooltip gelap
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }
            }
        });

        function filterTasksByDate() {
            const selectedDate = document.getElementById('filter_date').value;

            // Simulasi filter data berdasarkan tanggal yang dipilih
            // Ganti dengan pemanggilan API atau query yang sesuai
            const filteredTasks = completedTasks.map((task, index) => selectedDate ? Math.floor(Math.random() * 5) : task);

            chart.data.datasets[0].data = filteredTasks;
            chart.update();
        }
    </script>

    <style>
        #task_charts {
            background-color: #eaf6ff;
            /* Latar belakang biru muda */
            border-radius: 15px;
            /* Sudut membulat */
            padding: 20px;
            /* Padding di dalam kotak */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            /* Efek bayangan lembut */
        }
    </style> --}}
@endsection
