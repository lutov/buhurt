<div class="card @include('card.class')">
    <form action="/user/avatar" class="avatar" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <div class="form-group">
                <label for="avatar">Аватар</label>
                <input type="file" class="form-control-file" name="avatar" id="avatar">
            </div>
            <div class="form-group m-0">
                <input type="submit" value="Загрузить" id="upload_avatar" class="btn btn-sm btn-secondary" />
            </div>
        </div>
    </form>
</div>
