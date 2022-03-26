@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>{{ __('Edit Task') }}</div>
                </div>


                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif


                    <form id="frmEditTask" method="POST" action="{{ route('tasks.update', ['task' => $task->id]) }}" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $task->name) }}" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="timestamp" class="col-md-4 col-form-label text-md-end">{{ __('Timestamp') }}</label>

                            <div class="col-md-6">
                                <input id="timestamp" type="datetime-local" class="form-control @error('timestamp') is-invalid @enderror" name="timestamp" value="{{ $task->timestamp }}">

                                @error('timestamp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('home') }}">
                                    {{ __('Cancle') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#frmEditTask").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    timestamp: {
                        required: true,
                    }
                }
            });
        });
    </script>
@endsection
