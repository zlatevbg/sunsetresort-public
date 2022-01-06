<nav id="nav-book">
    <ul>
        <{{ $step == 1 ? 'span' : 'a' }} class="btn step {{ $step == 1 ? 'step-active' : ((session('max-step') >= 1 && $step > 1) ? 'step-visited' : 'disabled') }}" {{ $step == 1 ? 'data-' : '' }}href="{{ \Locales::route('book-test-step', '1') }}">{!! trans(\Locales::getNamespace() . '/messages.step1') !!}</{{ $step == 1 ? 'span' : 'a' }}>
        <{{ $step == 2 ? 'span' : 'a' }} class="btn step {{ $step == 2 ? 'step-active' : ((session('max-step') >= 2 && $step > 2) ? 'step-visited' : 'disabled') }}" {{ $step == 2 ? 'data-' : '' }}href="{{ \Locales::route('book-test-step', '2') }}">{!! trans(\Locales::getNamespace() . '/messages.step2') !!}</{{ $step == 2 ? 'span' : 'a' }}>
        <{{ $step == 3 ? 'span' : 'a' }} class="btn step {{ $step == 3 ? 'step-active' : ((session('max-step') >= 3 && $step > 3) ? 'step-visited' : 'disabled') }}" {{ $step == 3 ? 'data-' : '' }}href="{{ \Locales::route('book-test-step', '3') }}">{!! trans(\Locales::getNamespace() . '/messages.step3') !!}</{{ $step == 3 ? 'span' : 'a' }}>
        <{{ $step == 4 ? 'span' : 'a' }} class="btn step {{ $step == 4 ? 'step-active' : ((session('max-step') >= 4 && $step > 4) ? 'step-visited' : 'disabled') }}" {{ $step == 4 ? 'data-' : '' }}href="{{ \Locales::route('book-test-step', '4') }}">{!! trans(\Locales::getNamespace() . '/messages.step4') !!}</{{ $step == 4 ? 'span' : 'a' }}>
    </ul>
</nav>
