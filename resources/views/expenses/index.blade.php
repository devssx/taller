@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Ingresos / Gastos</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            @can('listar ingresos')
            <expense-container show-income="{{true}}" multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></expense-container>
            @endcan
            @cannot('listar ingresos')
            <expense-container show-income="{{false}}" multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></expense-container>
            @endcan
        </el-col>
    </el-row>
</el-main>
@endsection