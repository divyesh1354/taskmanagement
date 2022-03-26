@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>{{ __('Tasks') }}</div>
                    <div>
                        <a href="{{ route('tasks.create') }}" class="btn btn-success btn-sm">+ Add Task</a>
                    </div>
                </div>


                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    <table class="table datatable-basic table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Task Name</th>
                            <th>Timestamp</th>
                            <th>Priority</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody id="sortable-list-basic">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#sortable-list-basic").sortable({
                opacity: 0.5,
                cursor: "move",
                placeholder: "sortable-placeholder",
                update: function (event, ui) {
                    var id = $(this).children();
                    let data = [];
                    id.each(function () {
                        data.push($(this).attr('data-ids'));
                    });
                    updateOrder(data);
                }
            });

            // datatable Server Side Rendering
            datatable();

            deleteRecord();
        });

        var dt;
        // Datatable init
        function datatable() {
            let route = '{!! route('tasks.fetch') !!}';
            let data = function (data) {
            }

            let columns = [
                {
                    data: 'id',
                    class: 'text-center',
                    render: function (data) {
                        return (data.id != null) ? data.id : 'Not Available';
                    }
                },
                {
                    data: 'name',
                    render: function (data) {
                        return (data.name != null) ? data.name : 'Not Available';
                    }
                },
                {
                    data: 'timestamp',
                    render: function (data) {
                        return (data.timestamp != null) ? data.timestamp : 'Not Available';
                    }
                },
                {
                    data: 'priority',
                    render: function (data) {
                        return (data.priority != null) ? data.priority : 'Not Available';
                    }
                },
                {
                    'data': 'action',
                    'class': 'text-center',
                    'orderable': false,
                    render: (data) => {
                        return actionList(data);
                    }
                }
            ];
            dt = new DatatableInit(route, data, columns);
            dt.draw();
            return dt;
        }

        function deleteRecord() {
            $(document).on('click', '.confirm', function () {
                let url = $(this).attr('data-url');
                let title = $(this).attr('data-title');
                let message = $(this).attr('data-message');
                alertPopup(url, title, message);
            });
        }

        function updateOrder(ids) {
            axios({
                url: '{{ route('tasks.update.order') }}',
                method: 'POST',
                data: {
                    data: ids
                },
            }).then(function (response) {
                var result = response.data;
                bootbox.alert(result.message);
                dt.draw();
            });
        }

        function actionList(data) {
            let action = '';
            // Edit Option
            editUrl = '{{ route('tasks.edit', ['task' => ':id']) }}'.replace(':id', data.id);
            action += '<a href="' + editUrl + '" class="btn btn-warning btn-sm mr-2">{{ __('Edit') }}</a>';

            // Delete Option
            deleteUrl = '{{ route('tasks.destroy', ['task' => ':id']) }}'.replace(':id', data.id);
            deleteMessage = 'Are you sure you want to delete :name?'.replace(':name', data.name);
            action += '<a href="javascript:;" class="btn btn-danger btn-sm confirm text-white delete_btn" data-url="' + deleteUrl + '" data-title="{{ __("Delete Task?") }}" data-message="' + deleteMessage + '">{{ __("Delete") }}</a>';

            action += '</ul></li></ul>';
            return action;
        }
    </script>
@endsection
