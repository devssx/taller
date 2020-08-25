@extends('layouts.app') 
@section('content')
<receipt-copy :sale="{{ !empty($sale) ? $sale->toJson() : " false " }}"></receipt-copy>
@endsection