<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('lia.show_doc'))
            <strong><a href="https://xsaven.github.io/laravel-intelect-admin/#/{{config('app.locale')=='en' || config('app.locale')=='ru' ? config('app.locale').'/' : ""}}" target="_blank">Documentation</a></strong> |
        @endif
        <strong>Version</strong>&nbsp;&nbsp; {!! config('lia.version') !!}
    </div>
    <!-- Default to the left -->
    <strong>Powered by <a href="https://github.com/Xsaven" target="_blank">Xsaven</a></strong>
</footer>