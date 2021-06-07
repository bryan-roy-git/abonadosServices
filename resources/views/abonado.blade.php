<div>
    <h1>abonados</h1>
    {{-- {{ $abonado->foto }} --}}
    <img src="{{ $abonado->qr }}">
    {{-- <div class="visible-print text-center">
        {!! QrCode::size(100)->generate($abonado->qr); !!}
        <p>Scan me to return to the original page.</p>
    </div> --}}
</div>