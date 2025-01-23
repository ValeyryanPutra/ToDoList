@extends('layouts.app')
@section('content')

    <section class="comp-section">
        <div class="col-md-12">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified">
                    <li class="nav-item"><a class="nav-link active" href="#solid-rounded-justified-tab1"
                            data-bs-toggle="tab">All</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#solid-rounded-justified-tab3"
                            data-bs-toggle="tab">Work</a></li>
                    <li class="nav-item"><a class="nav-link" href="#solid-rounded-justified-tab3"
                            data-bs-toggle="tab">Diary</a></li>
                    <li class="nav-item"><a class="nav-link" href="#solid-rounded-justified-tab3"
                            data-bs-toggle="tab">Practice</a></li>
                </ul>
            </div>
        </div>
    </section>
    <br>
    <h5>Today</h5>
    <div class="card-body">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title">Create first milestone</h5>
                <div class="card-tools">
                    <a href="#" class="btn btn-tool btn-link">#5</a>
                    <a href="#" class="btn btn-tool">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Large Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <button style="border-radius: 20px" type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
        +
    </button>
@endsection