<?php
$routeName = \Illuminate\Support\Facades\Route::current()->getName();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/">Analyzer</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?=$routeName == 'root' ? 'active' : ''?>">
                <a class="nav-link" href="/">Home
                    @if ($routeName == 'root')
                    <span class="sr-only">(current)</span>
                    @endif
                </a>
            </li>
            <li class="nav-item <?=$routeName == 'domains.index' ? 'active' : ''?>">
                <a class="nav-link" href="<?=route('domains.index')?>">Domains
                    @if ($routeName == 'domains.index')
                    <span class="sr-only">(current)</span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</nav>
