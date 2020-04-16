@extends('layouts.app') 
@section('content')
{{-- <create-sales ref="create"></create-sales> --}}

<el-row type="flex">
    <el-col :span="24">
        <view-carservices ref="create" :full-mode="false"></view-carservices>
    </el-col>
</el-row>
@endsection
