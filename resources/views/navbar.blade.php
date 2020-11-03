<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">Analyzer</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ HtmlHelper::activeClass('root') }}">
                <a class="nav-link" href="{{ route('root') }}">
                    Home {!! HtmlHelper::activeNavLabel('root') !!}
                </a>
            </li>
            <li class="nav-item {{ HtmlHelper::activeClass('domains.index') }}">
                <a class="nav-link" href="{{ route('domains.index') }}">
                    Domains {!! HtmlHelper::activeNavLabel('domains.index') !!}
                </a>
            </li>
        </ul>
    </div>
</nav>
