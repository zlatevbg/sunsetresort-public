@extends(\Locales::getNamespace() . '.master')

@section('content')
    <section class="google-analytics-wrapper">
        <h1>Статистика за периода: {{ \App\Helpers\localizedDate($startDateNew->toDateString()) }} - {{ \App\Helpers\localizedDate() }}</h1>
        <div id="chart-6-container"></div>
        <article class="google-analytics-chart">
            <h1>Трафик <small>Потребители - за последните 30 дни</small></h1>
            <div id="chart-7-container"></div>
        </article>
        <article class="google-analytics-chart">
            <h1>Съдържание <small>По брой разглеждани страници</small></h1>
            <div id="chart-8-container"></div>
        </article>
        <article class="google-analytics-chart">
            <h1>Държави <small>По брой сесии</small></h1>
            <div id="chart-9-container"></div>
        </article>
        <article class="google-analytics-chart">
            <h1>Браузъри <small>По потребители</small></h1>
            <div id="chart-10-container"></div>
        </article>
    </section>

    <section class="google-analytics-wrapper">
        <h1>Статистика за периода: {{ \App\Helpers\localizedDate($startDateOld->toDateString()) }} - {{ \App\Helpers\localizedDate($startDateNew->toDateString()) }}</h1>
        <div id="chart-1-container"></div>
        <article class="google-analytics-chart">
            <h1>Трафик <small>Потребители - за последните 30 дни</small></h1>
            <div id="chart-2-container"></div>
        </article>
        <article class="google-analytics-chart">
            <h1>Съдържание <small>По брой разглеждани страници</small></h1>
            <div id="chart-3-container"></div>
        </article>
        <article class="google-analytics-chart">
            <h1>Държави <small>По брой сесии</small></h1>
            <div id="chart-4-container"></div>
        </article>
        <article class="google-analytics-chart">
            <h1>Браузъри <small>По потребители</small></h1>
            <div id="chart-5-container"></div>
        </article>
    </section>
@endsection

@section('script')
(function(w,d,s,g,js,fs){
g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
js.src='https://apis.google.com/js/platform.js';
fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));

gapi.analytics.ready(function() {

  gapi.analytics.auth.authorize({
    'serverAuth': {
      'access_token': '{{ $accessToken }}'
    }
  });

  var dataChart1 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileOld }}',
      'start-date': '{{ $startDateOld->toDateString() }}',
      'end-date': '{{ $startDateNew->toDateString() }}',
      'metrics': 'ga:users,ga:sessions,ga:pageviews',
    },
    chart: {
      'container': 'chart-1-container',
      'type': 'TABLE',
      'options': {
        'width': '100%',
      }
    }
  });
  dataChart1.execute();

  var dataChart2 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileOld }}',
      'start-date': '{{ $startDateNew->copy()->subMonth()->toDateString() }}',
      'end-date': '{{ $startDateNew->toDateString() }}',
      'metrics': 'ga:users',
      'dimensions': 'ga:date',
    },
    chart: {
      'container': 'chart-2-container',
      'type': 'LINE',
      'options': {
        'width': '100%',
      }
    }
  });
  dataChart2.execute();

  var dataChart3 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileOld }}',
      'start-date': '{{ $startDateOld->toDateString() }}',
      'end-date': '{{ $startDateNew->toDateString() }}',
      'metrics': 'ga:pageviews',
      'dimensions': 'ga:pagePath',
      'sort': '-ga:pageviews',
      'max-results': 8
    },
    chart: {
      'container': 'chart-3-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart3.execute();

  var dataChart4 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileOld }}',
      'start-date': '{{ $startDateOld->toDateString() }}',
      'end-date': '{{ $startDateNew->toDateString() }}',
      'metrics': 'ga:sessions',
      'dimensions': 'ga:country',
      'sort': '-ga:sessions',
      'max-results': 7
    },
    chart: {
      'container': 'chart-4-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart4.execute();

  var dataChart5 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileOld }}',
      'start-date': '{{ $startDateOld->toDateString() }}',
      'end-date': '{{ $startDateNew->toDateString() }}',
      'metrics': 'ga:users',
      'dimensions': 'ga:browser',
      'sort': '-ga:users',
      'max-results': 7
    },
    chart: {
      'container': 'chart-5-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart5.execute();

  var dataChart6 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileNew }}',
      'start-date': '{{ $startDateNew->toDateString() }}',
      'end-date': 'yesterday',
      'metrics': 'ga:users,ga:sessions,ga:pageviews',
    },
    chart: {
      'container': 'chart-6-container',
      'type': 'TABLE',
      'options': {
        'width': '100%',
      }
    }
  });
  dataChart6.execute();

  var dataChart7 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileNew }}',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'metrics': 'ga:users',
      'dimensions': 'ga:date',
    },
    chart: {
      'container': 'chart-7-container',
      'type': 'LINE',
      'options': {
        'width': '100%',
      }
    }
  });
  dataChart7.execute();

  var dataChart8 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileNew }}',
      'start-date': '{{ $startDateNew->toDateString() }}',
      'end-date': 'yesterday',
      'metrics': 'ga:pageviews',
      'dimensions': 'ga:pagePath',
      'sort': '-ga:pageviews',
      'max-results': 8
    },
    chart: {
      'container': 'chart-8-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart8.execute();

  var dataChart9 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileNew }}',
      'start-date': '{{ $startDateNew->toDateString() }}',
      'end-date': 'yesterday',
      'metrics': 'ga:sessions',
      'dimensions': 'ga:country',
      'sort': '-ga:sessions',
      'max-results': 7
    },
    chart: {
      'container': 'chart-9-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart9.execute();

  var dataChart10 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:{{ $profileNew }}',
      'start-date': '{{ $startDateNew->toDateString() }}',
      'end-date': 'yesterday',
      'metrics': 'ga:users',
      'dimensions': 'ga:browser',
      'sort': '-ga:users',
      'max-results': 7
    },
    chart: {
      'container': 'chart-10-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart10.execute();

});
@endsection
