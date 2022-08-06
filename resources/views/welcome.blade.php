@extends('layouts.main')
@section('content')
<div class="container mt-4">

    <h2 class="text-center">Distribucion de bombillos</h2>

    <div class="col-12">
        <h3>Instrucciones</h3>
        <ol>
            <li>Dar click en la pestaña ejecución.</li>
            <li>
                <div class="col-6">
                    Seleccionar el archivo txt que se desea evaluar.
                </div>
                <div class="col-6">
                    La matriz debera tener la siguiente estructrura (la matriz puede o no contener espacios).
                    <img src="{{ asset('img/ejemplo.png') }}" alt="" width="50%">
                </div>
            </li>
            <li>Dar click en el boton cargar para que se evalue la matriz.</li>
            <li>
                <div class="col-6">
                    La matriz se representara en una cuadricula de colores donde las celdas serán asignadas a un color según su rol.
                </div>
                <div class="col-6">
                    Las celdas correspondientes a paredes serán de color <strong>negro</strong>, las celdas asignadas a focos serán de color <span class="bg-warning">amarillo</span> y por ultimo las celdas que son iluminadas por el foco serán de color <span class="bg-info">azul</span>.
                </div>
            </li>
            <li>Si se desea cambiar la matriz puede realizarlo siguiendo el mismo procedimiento o si se prefiere puede ser cambiado en /storage/public/files/matriz.txt</li>
        </ol>
    </div>
</div>
@stop