<div class="card card-body">
    <span>Public URL</span>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">{{ url('/') }}/</span>
        </div>
        <?php
        $slug_target = $data->slugTarget();
        foreach ($context->structure as $struct) {
            if(str_replace('[]', '', $struct->field()) == $slug_target) {
                if ($struct->translateable()) {
                    $slug_target = $slug_target . '-' . Autocrud::defaultLang();
                }
            }
        }
        ?>
        <input slug-master type="text" name="slug_master" class="form-control" placeholder="your-url-slug" readonly data-target="#input-{{ $slug_target }}" value="{{ $data->getCurrentSlug() }}" {{ $data->hasSavedSlug() ? 'saved-slug' : '' }}>
        <div class="input-group-append">
            <button type="button" class="btn btn-secondary btn-change-slug">Change Manually</button>
        </div>
    </div>
</div>