<div class="page-wrapper">
    <div class="content">
        @include('dashboard::components.sweetalert-success')
        @include('dashboard::components.sweetalert-error')

        <h1>Hello, {{ $dataUserAuth[0]->name }}</h1>
    </div>
</div>
