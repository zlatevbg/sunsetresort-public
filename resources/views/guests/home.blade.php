@extends(\Locales::getNamespace() . '.master')

@section('content')
<article class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>
</article>
@endsection
