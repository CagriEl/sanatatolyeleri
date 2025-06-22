@if($getRecord()->signature)
    <div>
        <img src="{{ $getRecord()->signature }}" alt="İmza" style="max-width: 400px; border: 1px solid #ccc; padding: 5px;">
    </div>
@else
    <p>İmza bulunamadı.</p>
@endif
