<form action="/admin/transfer/{{ $section->alt_name }}/{{ $element->id }}" class="transfer" method="POST">
<div>
    <input name="recipient_id" value="" placeholder="Преемник" id="recipient" class="form-control" />
</div>
<div class="btn-group mt-3">
    <input type="submit" value="Перенести" id="do_transfer" class="btn btn-sm btn-secondary" />
</div>
</form>
