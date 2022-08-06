@extends('layouts.main')
@section('content')
<div class="container mt-4">

    <h2 class="text-center">Distribucion de bombillos</h2>

    <div class="col-md-12">
        <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ url('store') }}">
            @csrf
            <div class="col-md-6 mb-3">
                <label for="formFile" class="form-label">Carga el archivo que contiene la matriz</label>
                <input class="form-control" type="file" id="file" name="file">
            </div>
            @error('file')
            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="submit">Cargar</button>
            </div>
        </form>
    </div>
    <div class="col-md-12 mt-3">
        <h3>Número de Bombillas mínimas necesarias es de: <strong>{{ $count }}</strong> </h3>
        <h2>Habitacion sin iluminar</h2>
        <table class="table text-center table-bordered">
            <tbody>
                @foreach ($matrizChanged as $columna)
                <tr>
                    @foreach ($columna as $renglon)
                    @if ($renglon == "*" || $renglon == "-")
                    <th height="40px"></th>
                    @elseif ($renglon == 1)
                    <th class="table-dark" height="40px"></th>
                    @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <h2>Habitacion iluminada</h2>
        <table class="table text-center table-bordered">
            <tbody>
                @foreach ($matrizChanged as $columna)
                <tr>
                    @foreach ($columna as $renglon)
                    @if ($renglon == "*")
                    <th class="bg-warning" height="40px"></th>
                    @elseif ($renglon == 1)
                    <th class="table-dark" height="40px"></th>
                    @elseif ($renglon == "-")
                    <th class="bg-info" height="40px"></th>
                    @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop