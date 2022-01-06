@if (isset($datatables) && count($datatables) > 0)
    @foreach($datatables as $id => $table)
        <section class="dataTableWrapper table-responsive ajax-lock @if (isset($table['wrapperClass'])){{ $table['wrapperClass'] }}@endif" data-ajax-queue="async-{{ $id }}">
            <header>
                @if (isset($table['title']))<h1>{{ $table['title'] }}</h1>@endif

                @if (isset($table['buttons']))
                <div class="btn-group-wrapper">
                    @foreach($table['buttons'] as $button)
                    <div class="btn-group">
                        @if (isset($button['upload']) || isset($button['reupload']))
                            <div id="{{ $button['id'] }}" data-reupload="{{ isset($button['reupload']) ? 'true' : 'false' }}" @if (isset($table['pageId']))data-page-id="{{ $table['pageId'] }}"@endif data-url="{{ $button['url'] }}" data-table="{{ $id }}" class="btn {{ $button['class'] }}@if (isset($button['single']) && $table['count'] > 0) disabled @endif" data-multiple="{{ (isset($button['single']) || isset($button['reupload'])) ? 'false' : 'true' }}"@if (isset($button['upload-file'])) data-is-file="true"@endif>
                                @if ($button['icon'])<span class="glyphicon glyphicon-{{ $button['icon'] }}"></span>@endif
                                {{ $button['name'] }}
                            </div>
                        @elseif (isset($button['save']))
                            <a data-table="{{ $id }}" href="#" class="btn {{ $button['class'] }}">
                                @if ($button['icon'])<span class="glyphicon glyphicon-{{ $button['icon'] }}"></span>@endif
                                {{ $button['name'] }}
                            </a>
                        @else
                            <a @if (isset($button['id']))id="{{ $button['id'] }}"@endif data-table="{{ $id }}" href="{{ $button['url'] }}" class="btn {{ $button['class'] }}">
                                @if ($button['icon'])<span class="glyphicon glyphicon-{{ $button['icon'] }}"></span>@endif
                                {{ $button['name'] }}
                            </a>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </header>
            <table id="datatable{{ $id }}" class="dataTable table {{ $table['class'] }}">
            @if (isset($table['footer']))
                <tfoot>
                    <tr @if (isset($table['footer']['class']))class="{{ $table['footer']['class'] }}"@endif>
                    @foreach($table['footer']['columns'] as $column)
                        <th @if (isset($column['class']))class="{{ $column['class'] }}"@endif>{!! $column['data'] !!}</th>
                    @endforeach
                    </tr>
                </tfoot>
            @endif
            </table>
        </section>
        @if (count($datatables) > 1)<hr>@endif
    @endforeach
@endif
