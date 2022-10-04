@extends('adminpanel.master')
@section('title','Sotish')
@section('content')
    <div class="row">

        <!-- /.col-md-6 -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-create">
                        <i class="fa fa-plus"></i> Qo'shish
                    </button>

                    <div class="modal fade" id="modal-create">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Haridor yaratish</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="{{route('sells.store')}}">
                                        @csrf
                                        <div class="card-body">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Haridor nomini kiriting:</label>
                                                <input type="text" name="whom" class="form-control"
                                                       id="exampleInputEmail1">
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Bergan summa:</label>
                                                    <input type="text" name="given_sum" class="form-control"
                                                       id="exampleInputEmail1">
                                            </div>

                                        </div>
                                        <!-- /.card-body -->

                                        <div class="card-footer">

                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Bekor qilish </button>
                                            <button type="submit" class="btn btn-primary">Saqlash</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Haridor Nomi</th>
                            <th>Olgan Summa</th>
                            <th>Qarzdorlik</th>
                            <th>Jami Summa</th>
                            <th>Amallar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sells as $sel)
                            <tr>
                                <td>{{$sel->index +1}}</td>
                                <td>{{$sel->whom}}</td>
                                <td>{{number_format($sel->given_sum,2,',',' ')}}</td>
                                <td>{{number_format($sel->indebtedness,2,',',' ')}}</td>
                                <td>{{number_format($sel->all_sum,2,',',' ')}}</td>

                                <td class="d-flex">

                                    <a href="{{ route("sell_incomes.index",['id' => $sel->id]) }}"
                                       class="btn btn-success m-1">
                                        <i class="fa fa-car"></i>
                                    </a>

                                    <a href="{{ route("sell_provided.index",['id' => $sel->id]) }}"
                                       class="btn btn-info m-1">
                                        <i class="fa fa-clipboard-list"></i>
                                    </a>
                                    <button type="button" onclick="edit({{$sel->id}})" class="btn btn-warning m-1"
                                            data-toggle="modal" data-target="#modal-edit">
                                        <i class="fa fa-pen"></i>
                                    </button>

                                    <form action="{{route('sells.destroy', $sel->id)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger m-1 show_confirm"><i
                                                class="fa fa-trash"></i></button>
                                    </form>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Jami</th>
                            <th>{{ $cnt }} ta firma</th>
                            <th>{{number_format($sum_given,2,',',' ')}}</th>
                            <th>{{number_format($sum_indebtedness,2,',',' ')}}</th>
                            <th>{{number_format($sum_price,2,',',' ')}}</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>


{{--                <div class="modal fade" id="modal-edit">--}}
{{--                    <div class="modal-dialog">--}}
{{--                        <div class="modal-content">--}}
{{--                            <div class="modal-header">--}}
{{--                                <h4 class="modal-title">Firma yaratish</h4>--}}
{{--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                    <span aria-hidden="true">&times;</span>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <div class="modal-body">--}}
{{--                                <form method="post" action="{{route('firms.update',1)}}">--}}
{{--                                    @csrf--}}
{{--                                    @method('PUT')--}}
{{--                                    <input type="hidden" name="id" id="edit_id">--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="edit_name">Firma nomini kiriting:</label>--}}
{{--                                            <input type="text" name="name" class="form-control" id="edit_name">--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                    <!-- /.card-body -->--}}

{{--                                    <div class="card-footer">--}}

{{--                                    </div>--}}
{{--                                    <div class="modal-footer justify-content-between">--}}
{{--                                        <button type="button" class="btn btn-default" data-dismiss="modal">Bekor--}}
{{--                                            qilish--}}
{{--                                        </button>--}}
{{--                                        <button type="submit" class="btn btn-primary">Saqlash</button>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <!-- /.modal-content -->--}}
{{--                    </div>--}}
{{--                    <!-- /.modal-dialog -->--}}
{{--                </div>--}}
            </div>

        </div>
        <!-- /.col-md-6 -->
    </div>
@endsection

@section('custom-scripts')
    <script>
        @if(session('success'))
            toastr.options =
            {
                "closeButton": true,
                "progressBar": true
            }
        toastr.success("{{ session()->get('success') }}");
        @endif

        @if(session('error'))
        session.error("{{$message}}");
        @endif

        {{--let firmes =@json($firmes);--}}

        function edit(id) {

            var firms = firmes[id];

            document.getElementById('edit_name').value = firms['name'];
            document.getElementById('edit_id').value = id;

        }
    </script>

@endsection