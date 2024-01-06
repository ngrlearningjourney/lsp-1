@if($terpilih === 1)
<input class="form-check-input" name="{{ $name }}" type="checkbox" value="{{ $name }}" id="flexCheckChecked" checked>
@elseif($terpilih === 0)
<input class="form-check-input" name="{{ $name }}" type="checkbox" value="{{ $name }}" id="flexCheckChecked">
@endif