@extends('layouts.app')
@section('content')
<el-main class="content">
    <el-row type="flex">
        <el-col :span="19">
            <h2>Registro de carros semana</h2>
        </el-col>
    </el-row>
    <el-row type="flex">
        <el-col :span="24">
            <weekly-cars multi-workshop="{{ $multiWorkshop }}" :workshops="{{ $workshops->toJson() }}" :my-user="{{ $myUser->toJson() }}"></weekly-cars>
        </el-col>
    </el-row>
</el-main>
@endsection